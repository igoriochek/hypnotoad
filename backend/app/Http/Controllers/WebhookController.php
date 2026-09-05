<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentEvent;
use App\Services\OrderStatus;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderNotificationMail;

class WebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function webhook(Request $request): JsonResponse
    {
        $rawBody = $request->getContent();
        $payload = $request->json()->all();
        $headers = $request->headers->all();

        $provider = $this->paymentService->getProvider();

        // 1. Verify webhook signature
        $verified = $provider->verifyWebhook($headers, $rawBody);

        // 2. Extract event ID for idempotency
        $eventId = $provider->getEventId($payload);

        if (!$eventId) {
            Log::warning('Webhook missing event ID', [
                'provider' => $provider->getProviderName(),
                'payload' => $payload,
            ]);
            return response()->json(['error' => 'MISSING_EVENT_ID'], 400);
        }

        // 3. Idempotency check — if we've already processed this event, return OK
        $existingEvent = PaymentEvent::where('provider_event_id', $eventId)->first();
        if ($existingEvent) {
            Log::info('Duplicate webhook event ignored (idempotent)', [
                'event_id' => $eventId,
                'order_id' => $existingEvent->order_id,
            ]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        // 4. Find the order
        $orderNumber = $provider->getOrderNumber($payload);
        $order = $orderNumber ? Order::where('order_number', $orderNumber)->first() : null;

        // 5. Record the payment event
        $paymentEvent = PaymentEvent::create([
            'provider_event_id' => $eventId,
            'order_id' => $order?->id,
            'event_type' => $provider->getEventType($payload),
            'verified' => $verified,
            'raw_payload' => $rawBody,
            'received_at' => now(),
        ]);

        // 6. If signature verification failed, reject
        if (!$verified) {
            Log::error('Webhook signature verification failed', [
                'event_id' => $eventId,
                'order_number' => $orderNumber,
                'provider' => $provider->getProviderName(),
            ]);
            return response()->json(['error' => 'INVALID_SIGNATURE'], 403);
        }

        // 7. If order not found, log and return 404
        if (!$order) {
            Log::error('Webhook for unknown order', [
                'event_id' => $eventId,
                'order_number' => $orderNumber,
            ]);
            return response()->json(['error' => 'ORDER_NOT_FOUND'], 404);
        }

        // 8. Verify amount matches
        $paidAmountCents = $provider->getPaidAmountCents($payload);
        $currency = $provider->getCurrency($payload);

        if ($paidAmountCents !== null && $paidAmountCents !== $order->total_cents) {
            Log::error('Webhook amount mismatch', [
                'event_id' => $eventId,
                'order_number' => $orderNumber,
                'expected' => $order->total_cents,
                'received' => $paidAmountCents,
            ]);
            $order->update(['status' => OrderStatus::FAILED->value]);
            return response()->json(['error' => 'AMOUNT_MISMATCH'], 422);
        }

        if ($currency && $currency !== $order->currency) {
            Log::error('Webhook currency mismatch', [
                'event_id' => $eventId,
                'order_number' => $orderNumber,
                'expected' => $order->currency,
                'received' => $currency,
            ]);
            $order->update(['status' => OrderStatus::FAILED->value]);
            return response()->json(['error' => 'CURRENCY_MISMATCH'], 422);
        }

        // 9. Process payment status
        $isSuccessful = $provider->isPaymentSuccessful($payload);
        $paymentStatus = $provider->getPaymentStatus($payload);
        $providerPaymentId = $provider->getProviderPaymentId($payload);

        DB::transaction(function () use ($order, $isSuccessful, $paymentStatus, $providerPaymentId, $paymentEvent) {
            if ($isSuccessful) {
                // Only update to paid if not already paid (extra idempotency guard)
                if (!$order->isPaid()) {
                    $order->update([
                        'status' => OrderStatus::PAID->value,
                        'provider_payment_id' => $providerPaymentId ?? $order->provider_payment_id,
                        'paid_at' => now(),
                    ]);
                }
            } else {
                // Map provider status to our status
                $newStatus = match (strtoupper($paymentStatus)) {
                    'CANCELLED', 'CANCELED', 'REJECTED', 'EXPIRED', '0' => OrderStatus::CANCELLED->value,
                    'FAILED', 'REFUNDED' => strtoupper($paymentStatus) === 'REFUNDED'
                        ? OrderStatus::REFUNDED->value
                        : OrderStatus::FAILED->value,
                    default => $order->status,
                };

                if ($newStatus !== $order->status) {
                    $order->update(['status' => $newStatus]);
                }
            }
        });

        // 10. Send confirmation emails only on successful payment
        if ($isSuccessful && $order->fresh()->isPaid()) {
            $this->sendConfirmationEmails($order->fresh());
        }

        Log::info('Webhook processed', [
            'event_id' => $eventId,
            'order_number' => $orderNumber,
            'status' => $order->fresh()->status,
            'verified' => $verified,
        ]);

        return response()->json(['status' => 'processed'], 200);
    }

    private function sendConfirmationEmails(Order $order): void
    {
        $order->load('items');

        // Send to customer
        Mail::to($order->customer_email)->send(
            new OrderConfirmationMail($order)
        );

        // Send to owner (Oksana)
        $notificationEmail = config('payments.notification_email');
        if ($notificationEmail) {
            Mail::to($notificationEmail)->send(
                new OrderNotificationMail($order)
            );
        }
    }
}

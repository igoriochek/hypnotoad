<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use App\Services\PaymentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MontonioProvider implements PaymentProviderInterface
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('payments.providers.montonio');
    }

    public function createPayment(Order $order): string
    {
        $payload = [
            'accessKey' => $this->config['access_key'],
            'merchantData' => [
                'orderNumber' => $order->order_number,
                'currency' => $order->currency,
                'grandTotal' => number_format($order->total_cents / 100, 2, '.', ''),
                'returnUrl' => config('payments.return_urls.success') . '?order=' . $order->order_number,
                'notificationUrl' => route('payments.webhook'),
            ],
            'payment' => [
                'method' => 'paymentInitiation',
                'methodDisplay' => 'Pay by bank',
            ],
            'customer' => [
                'email' => $order->customer_email,
                'firstName' => $order->customer_name,
                'phoneNumber' => $order->customer_phone,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->post(rtrim($this->config['api_url'], '/') . '/v2/payments', $payload);

            if (!$response->successful()) {
                Log::error('Montonio API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order' => $order->order_number,
                ]);
                throw new PaymentException('Montonio API returned ' . $response->status());
            }

            $data = $response->json();

            $paymentId = $data['uuid'] ?? null;
            $checkoutUrl = $data['paymentUrl'] ?? null;

            if (!$checkoutUrl) {
                throw new PaymentException('Montonio response missing paymentUrl');
            }

            $order->update([
                'provider_payment_id' => $paymentId,
                'provider_response' => json_encode($data),
                'status' => \App\Services\OrderStatus::PAYMENT_STARTED->value,
            ]);

            return $checkoutUrl;
        } catch (PaymentException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Montonio request failed', [
                'error' => $e->getMessage(),
                'order' => $order->order_number,
            ]);
            throw new PaymentException('Montonio request failed: ' . $e->getMessage());
        }
    }

    public function verifyWebhook(array $headers, string $rawBody): bool
    {
        // Montonio signs webhooks with HMAC-SHA256 using the secret key.
        // The signature is sent in the X-Montonio-Signature header.
        $signature = $headers['x-montonio-signature'][0]
            ?? $headers['X-Montonio-Signature'][0]
            ?? null;

        if (!$signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $this->config['secret_key']);

        return hash_equals($expected, $signature);
    }

    public function getEventId(array $payload): ?string
    {
        return $payload['uuid'] ?? $payload['orderId'] ?? null;
    }

    public function getOrderNumber(array $payload): ?string
    {
        return $payload['merchantData']['orderNumber']
            ?? $payload['orderNumber']
            ?? null;
    }

    public function isPaymentSuccessful(array $payload): bool
    {
        $status = $this->getPaymentStatus($payload);
        return in_array($status, ['PAVED', 'PAID', 'COMPLETED', 'AUTHORIZED'], true);
    }

    public function getPaymentStatus(array $payload): string
    {
        return strtoupper($payload['status'] ?? $payload['paymentStatus'] ?? 'UNKNOWN');
    }

    public function getProviderPaymentId(array $payload): ?string
    {
        return $payload['uuid'] ?? $payload['paymentId'] ?? null;
    }

    public function getPaidAmountCents(array $payload): ?int
    {
        $amount = $payload['grandTotal'] ?? $payload['amount'] ?? null;
        if ($amount === null) {
            return null;
        }
        return (int) round((float) $amount * 100);
    }

    public function getCurrency(array $payload): ?string
    {
        return $payload['currency'] ?? $payload['merchantData']['currency'] ?? null;
    }

    public function getEventType(array $payload): string
    {
        return $payload['event'] ?? $payload['eventType'] ?? 'payment_status_update';
    }

    public function getProviderName(): string
    {
        return 'montonio';
    }
}

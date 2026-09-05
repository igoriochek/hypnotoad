<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * GET /order/success?order=...
     * Shows only the server-verified order status.
     * Success URL parameters are NOT proof of payment.
     */
    public function success(Request $request): JsonResponse
    {
        $orderNumber = $request->query('order');

        if (!$orderNumber) {
            return response()->json(['error' => 'MISSING_ORDER_NUMBER'], 400);
        }

        $order = Order::with('items')->where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['error' => 'ORDER_NOT_FOUND'], 404);
        }

        // The actual status is determined by the webhook, not by visiting this URL.
        // We return the real DB status so the frontend can show the correct state.
        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'is_paid' => $order->isPaid(),
            'total_formatted' => $order->totalFormatted(),
            'currency' => $order->currency,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'items' => $order->items->map(fn ($item) => [
                'product_code' => $item->product_code,
                'title' => $item->title_snapshot,
                'quantity' => $item->quantity,
                'unit_price_cents' => $item->unit_price_cents,
                'line_total_cents' => $item->line_total_cents,
            ]),
            'requires_shipping' => $order->requiresShipping(),
            'paid_at' => $order->paid_at?->toIso8601String(),
        ]);
    }

    /**
     * GET /order/cancelled?order=...
     * Shows cancelled or incomplete payment.
     */
    public function cancelled(Request $request): JsonResponse
    {
        $orderNumber = $request->query('order');

        if (!$orderNumber) {
            return response()->json(['error' => 'MISSING_ORDER_NUMBER'], 400);
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['error' => 'ORDER_NOT_FOUND'], 404);
        }

        // If the order is somehow paid (e.g. user visited cancelled URL after webhook
        // confirmed payment), still show the real status.
        if ($order->isPaid()) {
            return response()->json([
                'order_number' => $order->order_number,
                'status' => $order->status,
                'is_paid' => true,
                'message' => 'This order has actually been paid. Please check the success page.',
            ]);
        }

        // Update status to cancelled if it was still pending
        if (in_array($order->status, [OrderStatus::PENDING->value, OrderStatus::PAYMENT_STARTED->value])) {
            $order->update(['status' => OrderStatus::CANCELLED->value]);
        }

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->fresh()->status,
            'is_paid' => false,
            'message' => 'Payment was cancelled or not completed.',
        ]);
    }
}

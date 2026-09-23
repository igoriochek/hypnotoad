<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use App\Services\OrderStatus;

/**
 * Local development provider — no external API calls.
 *
 * createPayment() returns a local "test gateway" page where the developer
 * can simulate a successful or cancelled payment. The Pay button fires a
 * REAL signed webhook through the kernel, so the entire pipeline
 * (signature verification, idempotency, status update, emails) is exercised.
 *
 * Enabled only when PAYMENT_PROVIDER=test — never configure this in production.
 */
class TestProvider extends MontonioProvider
{
    public function __construct()
    {
        $this->config = config('payments.providers.test');
    }

    public function createPayment(Order $order): string
    {
        $order->update([
            'provider_payment_id' => 'test-' . $order->order_number,
            'provider_response' => json_encode(['provider' => 'test', 'note' => 'Local test gateway']),
            'status' => OrderStatus::PAYMENT_STARTED->value,
        ]);

        return url('/test-payment?order=' . $order->order_number);
    }

    public function getProviderName(): string
    {
        return 'test';
    }
}

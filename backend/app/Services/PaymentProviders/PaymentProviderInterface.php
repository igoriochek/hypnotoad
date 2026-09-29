<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentProviderInterface
{
    /**
     * Create a payment at the provider's API and return a redirect URL
     * to the provider's hosted Checkout page.
     *
     * @param  Order  $order  The order with items already persisted.
     * @return string  Checkout redirect URL.
     * @throws \App\Services\PaymentException  On API error.
     */
    public function createPayment(Order $order): string;

    /**
     * Verify the incoming webhook signature.
     *
     * @param  Request  $request  The raw incoming webhook request.
     * @return bool
     */
    public function verifyWebhook(Request $request): bool;

    /**
     * Extract the decoded payload from the webhook request.
     * JSON body for POST providers, decoded query params for GET providers.
     *
     * @param  Request  $request
     * @return array
     */
    public function extractPayload(Request $request): array;

    /**
     * Extract the provider's unique event ID from the webhook payload.
     * Used for idempotency — the same event must not be processed twice.
     *
     * @param  array  $payload  Decoded webhook payload.
     * @return string|null
     */
    public function getEventId(array $payload): ?string;

    /**
     * Extract the order number from the webhook payload.
     *
     * @param  array  $payload
     * @return string|null
     */
    public function getOrderNumber(array $payload): ?string;

    /**
     * Determine whether the webhook indicates a successful payment.
     *
     * @param  array  $payload
     * @return bool
     */
    public function isPaymentSuccessful(array $payload): bool;

    /**
     * Extract the payment status string from the webhook.
     *
     * @param  array  $payload
     * @return string
     */
    public function getPaymentStatus(array $payload): string;

    /**
     * Extract the provider's payment ID from the webhook.
     *
     * @param  array  $payload
     * @return string|null
     */
    public function getProviderPaymentId(array $payload): ?string;

    /**
     * Extract the paid amount in cents from the webhook.
     * Used to verify the amount matches the order total.
     *
     * @param  array  $payload
     * @return int|null
     */
    public function getPaidAmountCents(array $payload): ?int;

    /**
     * Extract the currency from the webhook.
     *
     * @param  array  $payload
     * @return string|null
     */
    public function getCurrency(array $payload): ?string;

    /**
     * Extract a human-readable event type from the webhook.
     *
     * @param  array  $payload
     * @return string
     */
    public function getEventType(array $payload): string;

    /**
     * The provider's identifier name (e.g. 'montonio').
     *
     * @return string
     */
    public function getProviderName(): string;
}

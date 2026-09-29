<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use App\Services\PaymentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MakeCommerceProvider implements PaymentProviderInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = config('payments.providers.makecommerce');
    }

    public function createPayment(Order $order): string
    {
        $payload = [
            'transaction' => [
                'amount' => $order->total_cents,
                'currency' => $order->currency,
                'reference' => $order->order_number,
                'merchant_data' => $order->order_number,
                'return_url' => config('payments.return_urls.success') . '?order=' . $order->order_number,
                'notification_url' => route('payments.webhook'),
                'customer' => [
                    'email' => $order->customer_email,
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                ],
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->config['access_key'], $this->config['secret_key'])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->post(rtrim($this->config['api_url'], '/') . '/v2/transactions', $payload);

            if (!$response->successful()) {
                Log::error('MakeCommerce API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order' => $order->order_number,
                ]);
                throw new PaymentException('MakeCommerce API returned ' . $response->status());
            }

            $data = $response->json();
            $checkoutUrl = $data['transaction']['checkout_url']
                ?? $data['checkout_url']
                ?? $data['transaction']['payment_methods']['redirect_url']
                ?? null;

            if (!$checkoutUrl) {
                throw new PaymentException('MakeCommerce response missing checkout_url');
            }

            $paymentId = $data['transaction']['id'] ?? null;

            $order->update([
                'provider_payment_id' => $paymentId,
                'provider_response' => json_encode($data),
                'status' => \App\Services\OrderStatus::PAYMENT_STARTED->value,
            ]);

            return $checkoutUrl;
        } catch (PaymentException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('MakeCommerce request failed', [
                'error' => $e->getMessage(),
                'order' => $order->order_number,
            ]);
            throw new PaymentException('MakeCommerce request failed: ' . $e->getMessage());
        }
    }

    public function verifyWebhook(Request $request): bool
    {
        // MakeCommerce uses HMAC-SHA256 signature in the Authorization header
        // or a custom X-MakeCommerce-Signature header.
        $signature = $request->header('X-MakeCommerce-Signature');

        if (!$signature) {
            // Check Authorization header for "Signature <mac>" format
            $auth = $request->header('Authorization');
            if ($auth && str_starts_with($auth, 'Signature ')) {
                $signature = substr($auth, 10);
            }
        }

        if (!$signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $request->getContent(), $this->config['webhook_secret']);

        return hash_equals($expected, $signature);
    }

    public function extractPayload(Request $request): array
    {
        return $request->json()->all();
    }

    public function getEventId(array $payload): ?string
    {
        return $payload['transaction']['id']
            ?? $payload['id']
            ?? $payload['event_id']
            ?? null;
    }

    public function getOrderNumber(array $payload): ?string
    {
        return $payload['transaction']['reference']
            ?? $payload['transaction']['merchant_data']
            ?? $payload['reference']
            ?? null;
    }

    public function isPaymentSuccessful(array $payload): bool
    {
        $status = $this->getPaymentStatus($payload);
        return in_array($status, ['COMPLETED', 'PAID', 'AUTHORIZED', 'SETTLED'], true);
    }

    public function getPaymentStatus(array $payload): string
    {
        return strtoupper(
            $payload['transaction']['status']
            ?? $payload['status']
            ?? 'UNKNOWN'
        );
    }

    public function getProviderPaymentId(array $payload): ?string
    {
        return $payload['transaction']['id'] ?? $payload['id'] ?? null;
    }

    public function getPaidAmountCents(array $payload): ?int
    {
        $amount = $payload['transaction']['amount'] ?? $payload['amount'] ?? null;
        if ($amount === null) {
            return null;
        }
        // MakeCommerce amount is already in minor units (cents)
        return (int) $amount;
    }

    public function getCurrency(array $payload): ?string
    {
        return $payload['transaction']['currency'] ?? $payload['currency'] ?? null;
    }

    public function getEventType(array $payload): string
    {
        return $payload['event_type'] ?? $payload['type'] ?? 'transaction_status_update';
    }

    public function getProviderName(): string
    {
        return 'makecommerce';
    }
}

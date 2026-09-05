<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use App\Services\PaymentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayseraProvider implements PaymentProviderInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = config('payments.providers.paysera');
    }

    public function createPayment(Order $order): string
    {
        $params = [
            'projectid' => $this->config['access_key'],
            'orderid' => $order->order_number,
            'amount' => number_format($order->total_cents / 100, 2, '.', ''),
            'currency' => $order->currency,
            'accepturl' => config('payments.return_urls.success') . '?order=' . $order->order_number,
            'cancelurl' => config('payments.return_urls.cancelled') . '?order=' . $order->order_number,
            'callbackurl' => route('payments.webhook'),
            'test' => config('app.env') === 'local' ? '1' : '0',
            'payer_email' => $order->customer_email,
            'payer_name' => $order->customer_name,
            'payer_phone' => $order->customer_phone,
            'version' => '1.6',
        ];

        ksort($params);
        $dataString = http_build_query($params);
        $encoded = base64_encode($dataString);
        $signature = md5($encoded . $this->config['secret_key']);

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post(rtrim($this->config['api_url'], '/') . '/api/checkout/v2/create', [
                    'data' => $encoded,
                    'ss1' => $signature,
                ]);

            if (!$response->successful()) {
                Log::error('Paysera API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order' => $order->order_number,
                ]);
                throw new PaymentException('Paysera API returned ' . $response->status());
            }

            $data = $response->json();
            $checkoutUrl = $data['redirect_url'] ?? $data['checkoutUrl'] ?? null;

            if (!$checkoutUrl) {
                throw new PaymentException('Paysera response missing redirect_url');
            }

            $paymentId = $data['transaction_id'] ?? $data['paymentId'] ?? null;

            $order->update([
                'provider_payment_id' => $paymentId,
                'provider_response' => json_encode($data),
                'status' => \App\Services\OrderStatus::PAYMENT_STARTED->value,
            ]);

            return $checkoutUrl;
        } catch (PaymentException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Paysera request failed', [
                'error' => $e->getMessage(),
                'order' => $order->order_number,
            ]);
            throw new PaymentException('Paysera request failed: ' . $e->getMessage());
        }
    }

    public function verifyWebhook(array $headers, string $rawBody): bool
    {
        // Paysera sends signed callbacks. The data is base64-encoded and
        // signed with ss1 (MD5) and optionally ss2 (RSA).
        $data = $headers['data'][0] ?? $_POST['data'] ?? null;
        $ss1 = $headers['ss1'][0] ?? $_POST['ss1'] ?? null;

        if (!$data || !$ss1) {
            // Try to parse from raw body as form-encoded
            parse_str($rawBody, $parsed);
            $data = $parsed['data'] ?? null;
            $ss1 = $parsed['ss1'] ?? null;
        }

        if (!$data || !$ss1) {
            return false;
        }

        $expected = md5($data . $this->config['secret_key']);

        return hash_equals($expected, $ss1);
    }

    public function getEventId(array $payload): ?string
    {
        return $payload['requestid'] ?? $payload['transactionId'] ?? null;
    }

    public function getOrderNumber(array $payload): ?string
    {
        return $payload['orderid'] ?? $payload['orderNumber'] ?? null;
    }

    public function isPaymentSuccessful(array $payload): bool
    {
        $status = $this->getPaymentStatus($payload);
        return in_array($status, ['1', 'PAID', 'COMPLETED', 'EXECUTED'], true);
    }

    public function getPaymentStatus(array $payload): string
    {
        // Paysera uses numeric status: 0 = not paid, 1 = paid, 2 = payment in progress
        $status = $payload['status'] ?? $payload['paymentStatus'] ?? '0';
        return (string) $status;
    }

    public function getProviderPaymentId(array $payload): ?string
    {
        return $payload['requestid'] ?? $payload['transactionId'] ?? null;
    }

    public function getPaidAmountCents(array $payload): ?int
    {
        $amount = $payload['amount'] ?? null;
        if ($amount === null) {
            return null;
        }
        // Paysera amount is in format "132.00"
        return (int) round((float) $amount * 100);
    }

    public function getCurrency(array $payload): ?string
    {
        return $payload['currency'] ?? null;
    }

    public function getEventType(array $payload): string
    {
        return 'payment_callback';
    }

    public function getProviderName(): string
    {
        return 'paysera';
    }
}

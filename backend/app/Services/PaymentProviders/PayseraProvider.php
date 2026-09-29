<?php

namespace App\Services\PaymentProviders;

use App\Models\Order;
use App\Services\OrderStatus;
use App\Services\PaymentException;
use Illuminate\Http\Request;

/**
 * Paysera WebToPay integration (spec v1.6).
 *
 * Flow: we build a signed redirect URL to https://www.paysera.com/pay/
 * (no server-side API call). Paysera then:
 *   - redirects the customer back to accepturl/cancelurl
 *   - calls callbackurl (GET ?data=..&ss1=..&ss2=..) — the webhook that
 *     actually confirms payment. Callback expects a plain "OK" response.
 *
 * Signing: data = base64url(http_build_query(params)), sign = md5(data . project_password).
 */
class PayseraProvider implements PaymentProviderInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = config('payments.providers.paysera');
    }

    public function createPayment(Order $order): string
    {
        $projectId = $this->config['project_id'] ?? null;
        $password = $this->config['project_password'] ?? null;

        if (!$projectId || !$password) {
            throw new PaymentException('Paysera is not configured (missing project_id or project_password)');
        }

        $params = [
            'projectid' => $projectId,
            'orderid' => $order->order_number,
            'accepturl' => config('payments.return_urls.success') . '?order=' . $order->order_number,
            'cancelurl' => config('payments.return_urls.cancelled') . '?order=' . $order->order_number,
            'callbackurl' => route('payments.webhook'),
            'version' => '1.6',
            'amount' => $order->total_cents,
            'currency' => $order->currency,
            'lang' => strtoupper($order->locale ?? 'lt'),
            'paytext' => 'Užsakymas ' . $order->order_number,
            'p_email' => $order->customer_email,
            'test' => $this->config['test'] ? '1' : '0',
        ];

        $data = self::base64UrlEncode(http_build_query($params));
        $sign = md5($data . $password);

        $order->update([
            'provider_payment_id' => null,
            'provider_response' => json_encode(['provider' => 'paysera', 'test' => (bool) $this->config['test']]),
            'status' => OrderStatus::PAYMENT_STARTED->value,
        ]);

        return rtrim($this->config['pay_url'], '/') . '/?data=' . $data . '&sign=' . $sign;
    }

    public function verifyWebhook(Request $request): bool
    {
        $data = $request->input('data');
        $ss1 = $request->input('ss1');

        if (!$data || !$ss1) {
            return false;
        }

        $expected = md5($data . $this->config['project_password']);

        return hash_equals($expected, $ss1);
    }

    public function extractPayload(Request $request): array
    {
        $data = $request->input('data');
        if (!$data) {
            return [];
        }

        $decoded = base64_decode(self::base64UrlDecode($data), true);
        if ($decoded === false) {
            return [];
        }

        parse_str($decoded, $payload);

        return $payload;
    }

    public function getEventId(array $payload): ?string
    {
        // Paysera retries the same callback until it gets "OK" — combine the
        // payment id with status so a later status change is a new event.
        $id = $payload['id'] ?? $payload['requestid'] ?? null;
        if (!$id) {
            return null;
        }

        return $id . ':' . ($payload['status'] ?? '');
    }

    public function getOrderNumber(array $payload): ?string
    {
        return $payload['orderid'] ?? null;
    }

    public function isPaymentSuccessful(array $payload): bool
    {
        // Paysera status: 0 = not paid, 1 = paid, 2/3 = pending/info.
        return $this->getPaymentStatus($payload) === '1';
    }

    public function getPaymentStatus(array $payload): string
    {
        return (string) ($payload['status'] ?? '0');
    }

    public function getProviderPaymentId(array $payload): ?string
    {
        return isset($payload['id']) ? (string) $payload['id'] : ($payload['requestid'] ?? null);
    }

    public function getPaidAmountCents(array $payload): ?int
    {
        // Paysera `amount` is already in cents.
        return isset($payload['amount']) ? (int) $payload['amount'] : null;
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

    /** Standard base64 → URL-safe base64 ('+/=' → '-_') as Paysera requires. */
    private static function base64UrlEncode(string $value): string
    {
        return strtr(base64_encode($value), ['+' => '-', '/' => '_']);
    }

    private static function base64UrlDecode(string $value): string
    {
        return strtr($value, ['-' => '+', '_' => '/']);
    }
}

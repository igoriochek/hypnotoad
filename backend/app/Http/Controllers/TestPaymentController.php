<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Local-only fake payment gateway (PAYMENT_PROVIDER=test).
 * Lets a developer click through the entire purchase flow:
 * checkout → this page → "Pay" fires a REAL signed webhook through the
 * kernel → order becomes paid → redirect to the real success page.
 */
class TestPaymentController extends Controller
{
    public function show(Request $request): View
    {
        $order = Order::with('items')->where('order_number', $request->query('order'))->firstOrFail();

        return view('test-payment', [
            'order' => $order,
            'total' => $order->totalFormatted(),
        ]);
    }

    public function complete(Request $request, Kernel $kernel): RedirectResponse
    {
        $order = Order::where('order_number', $request->input('order'))->firstOrFail();

        // Build a webhook payload identical in shape to Montonio's.
        $payload = json_encode([
            'uuid' => 'evt-test-' . bin2hex(random_bytes(8)),
            'status' => 'PAID',
            'grandTotal' => number_format($order->total_cents / 100, 2, '.', ''),
            'currency' => $order->currency,
            'merchantData' => ['orderNumber' => $order->order_number],
        ]);

        $secret = config('payments.providers.test.webhook_secret');
        $signature = hash_hmac('sha256', $payload, $secret);

        // Build the redirect URL BEFORE the sub-request: kernel->handle()
        // swaps the container's request instance, which would corrupt url().
        $successUrl = url('/order/success?order=' . $order->order_number);

        // Dispatch a sub-request through the real HTTP kernel — the webhook
        // endpoint runs with its actual middleware and signature verification.
        $subRequest = Request::create(
            '/api/payments/webhook',
            'POST',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_MONTONIO_SIGNATURE' => $signature,
            ],
            content: $payload,
        );

        $kernel->handle($subRequest);

        return redirect()->to($successUrl);
    }

    public function cancel(Request $request): RedirectResponse
    {
        return redirect('/order/cancelled?order=' . $request->query('order'));
    }
}

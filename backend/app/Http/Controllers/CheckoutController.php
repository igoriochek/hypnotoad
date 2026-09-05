<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderStatus;
use App\Services\PaymentService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private PaymentService $paymentService,
    ) {}

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $locale = $validated['locale'];

        // Server-side price validation — never trust client prices
        try {
            $calculation = $this->productService->validateAndCalculate(
                $validated['items'],
                $locale,
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => 'INVALID_CART',
                'message' => $e->getMessage(),
            ], 422);
        }

        // Generate unique order number
        $orderNumber = $this->generateOrderNumber();

        try {
            $order = DB::transaction(function () use ($validated, $locale, $calculation, $orderNumber) {
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'locale' => $locale,
                    'customer_email' => $validated['customer_email'],
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'shipping_address' => $validated['shipping_address'] ?? null,
                    'shipping_city' => $validated['shipping_city'] ?? null,
                    'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                    'shipping_country' => $validated['shipping_country'] ?? null,
                    'status' => OrderStatus::PENDING->value,
                    'currency' => config('payments.currency', 'EUR'),
                    'total_cents' => $calculation['total_cents'],
                    'provider' => config('payments.default'),
                ]);

                foreach ($calculation['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_code' => $item['product_code'],
                        'title_snapshot' => $item['title_snapshot'],
                        'unit_price_cents' => $item['unit_price_cents'],
                        'quantity' => $item['quantity'],
                        'line_total_cents' => $item['line_total_cents'],
                        'requires_shipping' => $item['requires_shipping'],
                    ]);
                }

                return $order;
            });

            // Create payment at provider and get redirect URL
            $provider = $this->paymentService->getProvider();
            $checkoutUrl = $provider->createPayment($order);

            return response()->json([
                'order_number' => $order->order_number,
                'checkout_url' => $checkoutUrl,
            ], 201);
        } catch (\App\Services\PaymentException $e) {
            Log::error('Checkout payment creation failed', [
                'order' => $orderNumber,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'PAYMENT_CREATION_FAILED',
                'message' => 'Could not create payment. Please try again.',
            ], 502);
        } catch (\Exception $e) {
            Log::error('Checkout failed', [
                'order' => $orderNumber,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'CHECKOUT_FAILED',
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        return "SHK-{$date}-{$random}";
    }
}

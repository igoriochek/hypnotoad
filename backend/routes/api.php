<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use App\Services\ProductService;
use Illuminate\Support\Facades\Route;

// API info at root
Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'running',
        'endpoints' => [
            'products' => 'GET /api/products',
            'checkout' => 'POST /api/checkout',
            'webhook' => 'POST /api/payments/webhook',
            'order_success' => 'GET /order/success',
            'order_cancelled' => 'GET /order/cancelled',
            'health' => 'GET /up',
        ],
    ]);
});

// Product catalog — list all active products
Route::get('/products', function (ProductService $productService) {
    $locale = request()->query('locale', 'lt');
    $products = $productService->getAllProducts();

    return response()->json($products->map(fn ($p) => [
        'code' => $p->code,
        'badge' => $p->localizedBadge($locale),
        'title' => $p->localizedTitle($locale),
        'description' => $p->localizedDescription($locale),
        'meta' => $p->localizedMeta($locale),
        'price_display' => $p->localizedPrice($locale),
        'price_cents' => $p->price_cents,
        'payable' => $p->payable,
        'requires_shipping' => $p->requires_shipping,
        'sort_order' => $p->sort_order,
    ]));
});

// Checkout — validates cart and creates payment
Route::post('/checkout', [CheckoutController::class, 'checkout']);

// Webhook — provider sends signed payment status updates here
// Must be excluded from CSRF protection (configured in bootstrap/app.php or VerifyCsrfToken)
Route::post('/payments/webhook', [WebhookController::class, 'webhook'])
    ->name('payments.webhook');

<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// API info at root
Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'running',
        'endpoints' => [
            'checkout' => 'POST /api/checkout',
            'webhook' => 'POST /api/payments/webhook',
            'order_success' => 'GET /order/success',
            'order_cancelled' => 'GET /order/cancelled',
            'health' => 'GET /up',
        ],
    ]);
});

// Checkout — validates cart and creates payment
Route::post('/checkout', [CheckoutController::class, 'checkout']);

// Webhook — provider sends signed payment status updates here
// Must be excluded from CSRF protection (configured in bootstrap/app.php or VerifyCsrfToken)
Route::post('/payments/webhook', [WebhookController::class, 'webhook'])
    ->name('payments.webhook');

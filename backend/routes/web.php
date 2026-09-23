<?php

use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\TestPaymentController;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderStatus;
use Illuminate\Support\Facades\Route;

// Root — landing page with dashboard stats
Route::get('/', function () {
    return view('welcome', [
        'products' => Product::active()->get(),
        'productCount' => Product::active()->count(),
        'orderCount' => Order::count(),
        'paidCount' => Order::where('status', OrderStatus::PAID->value)->count(),
    ]);
});

// Order status pages — these show ONLY server-verified status from the DB.
// Visiting the success URL is NOT proof of payment — only webhook confirmation is.
Route::get('/order/success', [OrderStatusController::class, 'success'])->name('order.success');
Route::get('/order/cancelled', [OrderStatusController::class, 'cancelled'])->name('order.cancelled');

// Orders admin — list all orders + per-order detail (customer, items, shipping, webhook events).
Route::get('/orders', [OrderAdminController::class, 'index']);
Route::get('/orders/{orderNumber}', [OrderAdminController::class, 'show']);

// Local-only fake payment gateway (PAYMENT_PROVIDER=test) — never in production.
if (app()->environment('local')) {
    Route::get('/test-payment', [TestPaymentController::class, 'show']);
    Route::post('/test-payment/complete', [TestPaymentController::class, 'complete']);
    Route::get('/test-payment/cancel', [TestPaymentController::class, 'cancel']);
}

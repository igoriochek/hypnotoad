<?php

use App\Http\Controllers\OrderStatusController;
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

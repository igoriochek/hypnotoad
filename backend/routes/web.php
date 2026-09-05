<?php

use App\Http\Controllers\OrderStatusController;
use Illuminate\Support\Facades\Route;

// Root — simple text response
Route::get('/', fn () => 'shkelio-backend running. See /up for health, /api/ for API info.');

// Order status pages — these show ONLY server-verified status from the DB.
// Visiting the success URL is NOT proof of payment — only webhook confirmation is.
Route::get('/order/success', [OrderStatusController::class, 'success'])->name('order.success');
Route::get('/order/cancelled', [OrderStatusController::class, 'cancelled'])->name('order.cancelled');

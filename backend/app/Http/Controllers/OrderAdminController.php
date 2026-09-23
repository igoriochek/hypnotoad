<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderAdminController extends Controller
{
    public function index(): View
    {
        return view('orders', [
            'orders' => Order::with('items')->latest()->get(),
        ]);
    }

    public function show(string $orderNumber): View
    {
        $order = Order::with(['items', 'paymentEvents'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('order-detail', ['order' => $order]);
    }
}

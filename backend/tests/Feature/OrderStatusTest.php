<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_page_shows_server_verified_status(): void
    {
        $order = $this->createPaidOrder();

        $response = $this->getJson('/order/success?order=' . $order->order_number);

        $response->assertStatus(200);
        $response->assertJson([
            'order_number' => $order->order_number,
            'status' => 'paid',
            'is_paid' => true,
        ]);
    }

    public function test_success_page_shows_pending_status_if_not_paid(): void
    {
        $order = Order::create([
            'order_number' => 'SHK-PENDING-001',
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'status' => OrderStatus::PAYMENT_STARTED->value,
            'currency' => 'EUR',
            'total_cents' => 13200,
            'provider' => 'montonio',
        ]);

        $response = $this->getJson('/order/success?order=' . $order->order_number);

        $response->assertStatus(200);
        $response->assertJson([
            'is_paid' => false,
            'status' => 'payment_started',
        ]);
    }

    public function test_success_page_returns_404_for_unknown_order(): void
    {
        $response = $this->getJson('/order/success?order=SHK-NONEXISTENT');

        $response->assertStatus(404);
    }

    public function test_success_page_returns_400_without_order_param(): void
    {
        $response = $this->getJson('/order/success');

        $response->assertStatus(400);
    }

    public function test_cancelled_page_marks_pending_order_cancelled(): void
    {
        $order = Order::create([
            'order_number' => 'SHK-CANCEL-001',
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'status' => OrderStatus::PAYMENT_STARTED->value,
            'currency' => 'EUR',
            'total_cents' => 13200,
            'provider' => 'montonio',
        ]);

        $response = $this->getJson('/order/cancelled?order=' . $order->order_number);

        $response->assertStatus(200);
        $response->assertJson([
            'is_paid' => false,
            'status' => 'cancelled',
        ]);
    }

    public function test_cancelled_page_shows_paid_if_order_is_actually_paid(): void
    {
        $order = $this->createPaidOrder();

        $response = $this->getJson('/order/cancelled?order=' . $order->order_number);

        $response->assertStatus(200);
        $response->assertJson([
            'is_paid' => true,
            'status' => 'paid',
        ]);
    }

    private function createPaidOrder(): Order
    {
        $order = Order::create([
            'order_number' => 'SHK-PAID-001',
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'status' => OrderStatus::PAID->value,
            'currency' => 'EUR',
            'total_cents' => 13200,
            'provider' => 'montonio',
            'provider_payment_id' => 'pay-123',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_code' => 'consultation_single',
            'title_snapshot' => 'Individuali konsultacija',
            'unit_price_cents' => 13200,
            'quantity' => 1,
            'line_total_cents' => 13200,
            'requires_shipping' => false,
        ]);

        return $order;
    }
}

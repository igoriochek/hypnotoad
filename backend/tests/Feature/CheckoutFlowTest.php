<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentEvent;
use App\Services\OrderStatus;
use App\Services\PaymentProviders\MontonioProvider;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_and_returns_redirect(): void
    {
        // Mock the payment provider
        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('createPayment')
            ->once()
            ->andReturnUsing(function (Order $order) {
                $order->update(['status' => OrderStatus::PAYMENT_STARTED->value]);
                return 'https://checkout.montonio.com/pay/abc123';
            });
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        $response = $this->postJson('/api/checkout', [
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas Jonaitis',
            'customer_phone' => '+37061234567',
            'items' => [
                ['product_code' => 'consultation_single', 'quantity' => 1],
                ['product_code' => 'book_science_change', 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['order_number', 'checkout_url']);

        $order = Order::where('order_number', $response->json('order_number'))->first();
        $this->assertNotNull($order);
        $this->assertEquals(OrderStatus::PAYMENT_STARTED->value, $order->status);
        $this->assertEquals(13200 + 6000, $order->total_cents); // 132€ + 2×30€
        $this->assertCount(2, $order->items);
    }

    public function test_checkout_rejects_non_payable_product(): void
    {
        $response = $this->postJson('/api/checkout', [
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'items' => [
                ['product_code' => 'group_training_matthew', 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);
        $this->assertEquals('INVALID_CART', $response->json('error'));
    }

    public function test_checkout_rejects_empty_cart(): void
    {
        $response = $this->postJson('/api/checkout', [
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'items' => [],
        ]);

        $response->assertStatus(422);
    }

    public function test_checkout_rejects_invalid_email(): void
    {
        $response = $this->postJson('/api/checkout', [
            'locale' => 'lt',
            'customer_email' => 'not-an-email',
            'customer_name' => 'Jonas',
            'items' => [
                ['product_code' => 'consultation_single', 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);
    }

    public function test_checkout_ignores_client_supplied_price(): void
    {
        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('createPayment')
            ->once()
            ->andReturn('https://checkout.montonio.com/pay/abc');
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        // Client tries to send a fake price — it should be ignored
        $response = $this->postJson('/api/checkout', [
            'locale' => 'en',
            'customer_email' => 'customer@example.com',
            'customer_name' => 'John Doe',
            'items' => [
                [
                    'product_code' => 'consultation_single',
                    'quantity' => 1,
                    'price' => 100, // This should be ignored
                ],
            ],
        ]);

        $response->assertStatus(201);
        $order = Order::where('order_number', $response->json('order_number'))->first();
        $this->assertEquals(13200, $order->total_cents); // Server price, not client price
    }
}

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

class WebhookIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder(): Order
    {
        $order = Order::create([
            'order_number' => 'SHK-TEST-001',
            'locale' => 'lt',
            'customer_email' => 'klientas@example.com',
            'customer_name' => 'Jonas',
            'status' => OrderStatus::PAYMENT_STARTED->value,
            'currency' => 'EUR',
            'total_cents' => 13200,
            'provider' => 'montonio',
            'provider_payment_id' => 'pay-123',
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

    public function test_successful_webhook_marks_order_paid(): void
    {
        $order = $this->createOrder();

        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('verifyWebhook')->andReturn(true);
        $mockProvider->shouldReceive('extractPayload')->andReturn([
            'uuid' => 'evt-001',
            'status' => 'PAID',
            'grandTotal' => '132.00',
            'currency' => 'EUR',
            'merchantData' => ['orderNumber' => 'SHK-TEST-001'],
        ]);
        $mockProvider->shouldReceive('getEventId')->andReturn('evt-001');
        $mockProvider->shouldReceive('getOrderNumber')->andReturn('SHK-TEST-001');
        $mockProvider->shouldReceive('getEventType')->andReturn('payment_status_update');
        $mockProvider->shouldReceive('isPaymentSuccessful')->andReturn(true);
        $mockProvider->shouldReceive('getPaymentStatus')->andReturn('PAID');
        $mockProvider->shouldReceive('getProviderPaymentId')->andReturn('pay-123');
        $mockProvider->shouldReceive('getPaidAmountCents')->andReturn(13200);
        $mockProvider->shouldReceive('getCurrency')->andReturn('EUR');
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        // Mock mail to prevent actual sending
        \Mail::fake();

        $response = $this->postJson('/api/payments/webhook', [
            'uuid' => 'evt-001',
            'status' => 'PAID',
            'grandTotal' => '132.00',
            'currency' => 'EUR',
            'merchantData' => ['orderNumber' => 'SHK-TEST-001'],
        ]);

        $response->assertStatus(200);
        $order->refresh();
        $this->assertEquals(OrderStatus::PAID->value, $order->status);
        $this->assertNotNull($order->paid_at);
    }

    public function test_duplicate_webhook_is_idempotent(): void
    {
        $order = $this->createOrder();
        $order->update(['status' => OrderStatus::PAID->value, 'paid_at' => now()]);

        // Pre-create the payment event to simulate already-processed
        PaymentEvent::create([
            'provider_event_id' => 'evt-001',
            'order_id' => $order->id,
            'event_type' => 'payment_status_update',
            'verified' => true,
            'raw_payload' => '{}',
            'received_at' => now(),
        ]);

        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('verifyWebhook')->andReturn(true);
        $mockProvider->shouldReceive('extractPayload')->andReturn(['uuid' => 'evt-001']);
        $mockProvider->shouldReceive('getEventId')->andReturn('evt-001');
        // These should NOT be called because we short-circuit on duplicate
        $mockProvider->shouldNotReceive('getOrderNumber');
        $mockProvider->shouldNotReceive('isPaymentSuccessful');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        $response = $this->postJson('/api/payments/webhook', [
            'uuid' => 'evt-001',
        ]);

        $response->assertStatus(200);
        $response->assertContent('OK');
    }

    public function test_webhook_with_invalid_signature_rejected(): void
    {
        $order = $this->createOrder();

        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('verifyWebhook')->andReturn(false);
        $mockProvider->shouldReceive('extractPayload')->andReturn(['uuid' => 'evt-bad-sig']);
        $mockProvider->shouldReceive('getEventId')->andReturn('evt-bad-sig');
        $mockProvider->shouldReceive('getOrderNumber')->andReturn('SHK-TEST-001');
        $mockProvider->shouldReceive('getEventType')->andReturn('payment_status_update');
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');
        $mockProvider->shouldNotReceive('isPaymentSuccessful');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        $response = $this->postJson('/api/payments/webhook', [
            'uuid' => 'evt-bad-sig',
        ]);

        $response->assertStatus(403);
        $order->refresh();
        $this->assertNotEquals(OrderStatus::PAID->value, $order->status);
    }

    public function test_webhook_with_amount_mismatch_rejected(): void
    {
        $order = $this->createOrder();

        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('verifyWebhook')->andReturn(true);
        $mockProvider->shouldReceive('extractPayload')->andReturn(['uuid' => 'evt-mismatch', 'grandTotal' => '99.99']);
        $mockProvider->shouldReceive('getEventId')->andReturn('evt-mismatch');
        $mockProvider->shouldReceive('getOrderNumber')->andReturn('SHK-TEST-001');
        $mockProvider->shouldReceive('getEventType')->andReturn('payment_status_update');
        $mockProvider->shouldReceive('isPaymentSuccessful')->andReturn(true);
        $mockProvider->shouldReceive('getPaymentStatus')->andReturn('PAID');
        $mockProvider->shouldReceive('getProviderPaymentId')->andReturn('pay-123');
        $mockProvider->shouldReceive('getPaidAmountCents')->andReturn(9999); // Wrong amount!
        $mockProvider->shouldReceive('getCurrency')->andReturn('EUR');
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        $response = $this->postJson('/api/payments/webhook', [
            'uuid' => 'evt-mismatch',
            'grandTotal' => '99.99',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'AMOUNT_MISMATCH']);
        $order->refresh();
        $this->assertEquals(OrderStatus::FAILED->value, $order->status);
    }

    public function test_webhook_for_unknown_order_returns_404(): void
    {
        $mockProvider = \Mockery::mock(MontonioProvider::class);
        $mockProvider->shouldReceive('verifyWebhook')->andReturn(true);
        $mockProvider->shouldReceive('extractPayload')->andReturn(['uuid' => 'evt-unknown']);
        $mockProvider->shouldReceive('getEventId')->andReturn('evt-unknown');
        $mockProvider->shouldReceive('getOrderNumber')->andReturn('SHK-NONEXISTENT');
        $mockProvider->shouldReceive('getEventType')->andReturn('payment_status_update');
        $mockProvider->shouldReceive('getProviderName')->andReturn('montonio');

        $this->app->make(PaymentService::class)->setProvider($mockProvider);

        $response = $this->postJson('/api/payments/webhook', [
            'uuid' => 'evt-unknown',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'ORDER_NOT_FOUND']);
    }
}

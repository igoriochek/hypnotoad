<?php

namespace Database\Factories;

use App\Models\Order;
use App\Services\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => 'SHK-' . now()->format('Ymd') . '-' . strtoupper(fake()->bothify('??????')),
            'locale' => fake()->randomElement(['lt', 'en', 'ru']),
            'customer_email' => fake()->safeEmail(),
            'customer_name' => fake()->name(),
            'customer_phone' => '+3706' . fake()->numberBetween(100000, 999999),
            'status' => OrderStatus::PENDING->value,
            'currency' => 'EUR',
            'total_cents' => fake()->numberBetween(3000, 60000),
            'provider' => 'montonio',
        ];
    }
}

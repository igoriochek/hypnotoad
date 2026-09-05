<?php

namespace App\Models;

use App\Services\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'locale',
        'customer_email',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'status',
        'currency',
        'total_cents',
        'provider',
        'provider_payment_id',
        'provider_response',
        'paid_at',
    ];

    protected $casts = [
        'total_cents' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentEvents(): HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID->value;
    }

    public function requiresShipping(): bool
    {
        return $this->items()->where('requires_shipping', true)->exists();
    }

    public function totalFormatted(): string
    {
        return number_format($this->total_cents / 100, 2, ',', '.') . ' €';
    }
}

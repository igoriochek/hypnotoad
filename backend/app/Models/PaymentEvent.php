<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'provider_event_id',
        'order_id',
        'event_type',
        'verified',
        'raw_payload',
        'received_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'received_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

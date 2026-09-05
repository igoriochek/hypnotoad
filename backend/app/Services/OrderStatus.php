<?php

namespace App\Services;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAYMENT_STARTED = 'payment_started';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case FULFILLED = 'fulfilled';
}

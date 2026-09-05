<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->order->locale) {
            'en' => 'Order confirmation — ' . $this->order->order_number,
            'ru' => 'Подтверждение заказа — ' . $this->order->order_number,
            default => 'Užsakymo patvirtinimas — ' . $this->order->order_number,
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
                'totalFormatted' => $this->order->totalFormatted(),
                'requiresShipping' => $this->order->requiresShipping(),
            ],
        );
    }
}

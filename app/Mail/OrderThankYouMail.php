<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $context = 'placed',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->context === 'confirmed'
                ? 'Đơn hàng #' . $this->order->id . ' đã được xác nhận'
                : 'Cảm ơn bạn đã đặt hàng #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'clients.email.order_thank_you',
            with: [
                'order' => $this->order,
                'context' => $this->context,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
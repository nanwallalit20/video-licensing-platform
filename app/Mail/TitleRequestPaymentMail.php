<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TitleRequestPaymentMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    protected Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order) {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'MovieRites - Title Request Payment Link for Title Request #' . $this->order->uuid,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.title_request_payment',
            with: [
                'order' => $this->order,
            ],
        );
    }

}

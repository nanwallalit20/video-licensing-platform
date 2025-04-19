<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    protected string $user_name;
    protected string $subscribeLink;

    /**
     * Create a new message instance.
     */
    public function __construct(string $user_name, string $subscribeLink) {
        $this->user_name = $user_name;
        $this->subscribeLink = $subscribeLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'MovieRites - Content Subscription',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.subscriptionMail',
            with: [
                'user_name' => $this->user_name,
                'subscribeLink' => $this->subscribeLink,
            ]
        );
    }

}

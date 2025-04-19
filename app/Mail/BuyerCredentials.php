<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class BuyerCredentials extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    protected string $user_name;
    protected string $user_email;
    protected string $resetPasswordLink;

    /**
     * Create a new message instance.
     */
    public function __construct(string $user_name, string $user_email, string $resetPasswordLink) {
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->resetPasswordLink = $resetPasswordLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'MovieRites - Buyer Account Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.buyer_credential_mail',
            with: [
                'user_name' => $this->user_name,
                'user_email' => $this->user_email,
                'resetPasswordLink' => $this->resetPasswordLink,
            ]
        );
    }

}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationToSeller extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $title;
    public $head_msg;
    public $subject;
    public static $defaultSubject = "MovieRites - Admin Support Mail";

    public function __construct($title, $head_msg, $subject) {
        $this->title = $title;
        $this->head_msg = $head_msg;
        $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: $this->subject ?? self::$defaultSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.admin_to_seller_email',
            with: [
                'title' => $this->title,
                'head_msg' => $this->head_msg
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        return [];
    }


    public function build() {
        return $this->view('emails.admin_to_seller_email');
    }
}

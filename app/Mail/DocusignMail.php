<?php

namespace App\Mail;

use App\Helpers\Docusign\DocusignHelper;
use App\Models\Title;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocusignMail extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    protected mixed $title;

    /**
     * @param Title $title
     */
    public function __construct(Title $title) {
        $this->title = $title;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'MovieRites - Docusign Revenue Plan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'mail.docusign.update', with: ['title' => $this->title]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        $list = [];
        $revenuePlan = $this->title->getRevenuePlan;
        if ($revenuePlan) {
            $envelopeId = $revenuePlan->envelope_id ?? null;
            if ($envelopeId) {
                $docusign = new DocusignHelper();
                $documents = $docusign->getEnvelopeDocuments($envelopeId);
                foreach ($documents as $document) {
                    $filePath = trim($document->path, '/') . '/' . $document->name;
                    $list[] = Attachment::fromStorage($filePath);
                }
            }
        }

        return $list;
    }
}

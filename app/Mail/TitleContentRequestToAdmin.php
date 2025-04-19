<?php

namespace App\Mail;

use App\Enums\Roles;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TitleContentRequestToAdmin extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    protected Order $orderDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($orderDetails) {
        $this->orderDetails = $orderDetails;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'MovieRites - Title Content Request To Admin',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        $order = Order::where('order_uuid', $this->orderDetails->order_uuid)
            ->first();

        $admin = User::whereHas('getUserProfile', function ($query) {
            $query->where('role_id', Roles::Superadmin);
        })->first();

        $titleDetails = [];

        foreach ($order->getOrderMetas as $meta) {
            $title = $meta->getTitle;
            $detail = [
                'title_id' => $title->uuid,
                'name' => $title->name,
                'type' => $title->type->name,
                'genres' => $title->getGenresThroughMapGenreTitles->pluck('name')->implode(', ')
            ];

            if ($meta->getSeason) {
                $detail['season_name'] = $meta->getSeason->name;
            }

            $titleDetails[] = $detail;
        }

        return new Content(
            view: 'emails.title_request_to_admin',
            with: [
                'adminName' => $admin->name,
                'orderUuid' => $this->orderDetails->order_uuid,
                'buyerEmail' => $order->getBuyer->email,
                'titles' => $titleDetails
            ],
        );
    }

}

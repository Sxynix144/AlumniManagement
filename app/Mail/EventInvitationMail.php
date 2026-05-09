<?php

namespace App\Mail;

use App\Models\Alumni;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Event  $event,
        public readonly Alumni $alumni,
        public readonly string $token,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You're Invited: {$this->event->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-invitation',
            with: [
                'event'    => $this->event,
                'alumni'   => $this->alumni,
                'rsvpUrl'  => route('login'),
            ],
        );
    }
}
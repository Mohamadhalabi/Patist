<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CustomReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $reminder;

    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Reminder - '.$this->reminder->ref_code,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.custom-reminder',
            with: [
                'reminder' => $this->reminder,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

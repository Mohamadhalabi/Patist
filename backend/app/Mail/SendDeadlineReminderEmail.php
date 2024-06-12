<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendDeadlineReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $renewal;

    public function __construct($renewal)
    {
        $this->renewal = $renewal;
    }

    public function envelope()
    {
        return new Envelope(
            subject: $this->renewal->reference_no.' - Deadline Reminder Email',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'renewal' => $this->renewal,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class Invoice extends Mailable
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
            subject: 'Invoice - '.$this->renewal->reference_no,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.invoice',
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

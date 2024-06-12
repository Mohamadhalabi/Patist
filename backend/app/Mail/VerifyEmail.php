<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $workflowId;

    public function __construct($workflowId)
    {
        $this->workflowId = $workflowId;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Verify Email',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'url' => URL::temporarySignedRoute(
                    'verify-email',
                    now()->addMinutes(30),
                    ['workflow_id' => $this->workflowId],
                ),
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}

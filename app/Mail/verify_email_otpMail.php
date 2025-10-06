<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class verify_email_otpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Email Otp',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

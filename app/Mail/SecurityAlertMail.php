<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alertType;
    public $details;
    public $user;

    public function __construct($user, $alertType, $details)
    {
        $this->user = $user;
        $this->alertType = $alertType;
        $this->details = $details;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Security Alert - ' . $this->alertType,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.security-alert',
        );
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user  = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kích hoạt tài khoản',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'clients.email.activation',
            with: [
                'token' => $this->token,
                'user'  => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
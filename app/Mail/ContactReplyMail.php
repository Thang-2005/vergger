<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $customerEmail;
    public $replyContent;
    public $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct($customerName, $replyContent, $contactData = [])
    {
        $this->customerName = $customerName;
        $this->replyContent = $replyContent;
        $this->customerEmail = $contactData->email ?? '';
        $this->contactData = $contactData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->customerEmail],
            subject: 'Phản hồi liên hệ từ Veggie Store',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.contact-reply',
            with: [
                'customerName' => $this->customerName,
                'replyContent' => $this->replyContent,
                'companyName' => 'Veggie Store',
                'companyPhone' => '1900 1234',
                'companyEmail' => config('mail.from.address'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


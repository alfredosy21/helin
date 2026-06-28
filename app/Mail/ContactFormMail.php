<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $phone,
        public readonly string $subject,
        public readonly string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Helin] Nuevo mensaje de contacto: ' . $this->subject,
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-form',
        );
    }
}

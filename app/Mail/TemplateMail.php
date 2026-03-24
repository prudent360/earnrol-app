<?php

namespace App\Mail;

use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $parsedSubject;
    public string $parsedBody;

    public function __construct(string $templateKey, array $data = [])
    {
        [$this->parsedSubject, $this->parsedBody] = TemplateService::parse($templateKey, $data);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->parsedSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.template',
            with: [
                'body' => $this->parsedBody,
            ],
        );
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $logoDataUri = null;

    public function __construct(
        public array $payload
    ) {
        $this->logoDataUri = $this->buildLogoDataUri();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jauns pieprasījums no formas',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-admin-notification',
            with: [
                'payload' => $this->payload,
                'logoDataUri' => $this->logoDataUri,
            ],
        );
    }

    private function buildLogoDataUri(): ?string
    {
        $relativePath = (string) config('invoice.logo_path', '');
        if ($relativePath === '') {
            return null;
        }

        $path = public_path(ltrim($relativePath, '/'));
        if (! is_file($path)) {
            return null;
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };

        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($content);
    }
}

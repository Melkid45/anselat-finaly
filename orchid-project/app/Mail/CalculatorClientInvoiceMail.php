<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CalculatorClientInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $payload,
        public string $invoiceNumber,
        public string $pdfContent,
        public array $invoice
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Anselat - Rekins par izmaksu aprakinu',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.calculator-client-invoice',
            with: [
                'payload' => $this->payload,
                'invoiceNumber' => $this->invoiceNumber,
                'invoice' => $this->invoice,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'rekins-' . $this->invoiceNumber . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

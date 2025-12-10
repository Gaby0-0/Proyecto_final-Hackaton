<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InformeGeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $datos,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informe General - ConcursITO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.informe-general',
        );
    }

    public function attachments(): array
    {
        // Generar PDF
        $pdf = Pdf::loadView('pdf.informe-general', ['datos' => $this->datos]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'informe-general.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

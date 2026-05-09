<?php

namespace App\Mail;

use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $isAdminView;
    public $recipientName;

    public function __construct($appointments, bool $isAdminView = false, string $recipientName = '')
    {
        $this->appointments = $appointments;
        $this->isAdminView = $isAdminView;
        $this->recipientName = $recipientName;
    }

    public function envelope(): Envelope
    {
        $subject = $this->isAdminView
            ? 'Reporte Diario de Citas - ' . config('app.name')
            : 'Tus Citas del Día - ' . config('app.name');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-report',
            with: [
                'appointments' => $this->appointments,
                'isAdminView' => $this->isAdminView,
                'recipientName' => $this->recipientName,
            ],
        );
    }

    public function attachments(): array
    {
        $pdfService = app(PdfService::class);
        $pdfContent = $pdfService->generateDailyReportPdf(
            $this->appointments,
            $this->isAdminView
        );

        return [
            Attachment::fromData(fn() => $pdfContent, 'reporte-diario-' . now()->format('Y-m-d') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

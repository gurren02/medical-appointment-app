<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentDoctorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Cita Asignada - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.doctor-appointment-notification',
            with: [
                'appointment' => $this->appointment,
                'patient' => $this->appointment->patient,
                'doctor' => $this->appointment->doctor,
            ],
        );
    }

    public function attachments(): array
    {
        $pdfService = app(PdfService::class);
        $pdfContent = $pdfService->generateAppointmentPdf($this->appointment);

        return [
            Attachment::fromData(fn() => $pdfContent, 'comprobante-cita-' . $this->appointment->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

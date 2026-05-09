<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfService
{
    public function generateAppointmentPdf($appointment)
    {
        $appointment->load(['patient.user', 'doctor.user']);

        $pdf = Pdf::loadView('pdf.appointment', [
            'appointment' => $appointment,
            'patient' => $appointment->patient,
            'doctor' => $appointment->doctor,
            'date' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->output();
    }

    public function generateDailyReportPdf($appointments, $adminView = false, $doctor = null)
    {
        $pdf = Pdf::loadView('pdf.daily-report', [
            'appointments' => $appointments,
            'date' => now()->format('d/m/Y'),
            'adminView' => $adminView,
            'doctor' => $doctor,
        ]);

        return $pdf->output();
    }
}

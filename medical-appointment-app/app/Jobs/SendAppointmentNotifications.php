<?php

namespace App\Jobs;

use App\Mail\AppointmentConfirmationMail;
use App\Mail\AppointmentDoctorNotification;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentNotifications implements ShouldQueue
{
    use Dispatchable, Queueable;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle(): void
    {
        $this->appointment->load(['patient.user', 'doctor.user']);

        $patientEmail = $this->appointment->patient->user->email;
        $doctorEmail = $this->appointment->doctor->user->email;
        $patientPhone = $this->appointment->patient->user->phone;
        $patientName = $this->appointment->patient->user->name;
        $doctorName = $this->appointment->doctor->user->name;

        Mail::to($patientEmail)->send(new AppointmentConfirmationMail($this->appointment));
        Mail::to($doctorEmail)->send(new AppointmentDoctorNotification($this->appointment));

        try {
            $whatsapp = app(WhatsAppService::class);
            $templateSid = env('TWILIO_WHATSAPP_CONFIRMATION_SID');

            if ($templateSid && config('whatsapp.default') === 'twilio') {
                $whatsapp->sendTemplate($patientPhone, $templateSid, [
                    '1' => \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y'),
                    '2' => \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i'),
                ]);
            } else {
                $message = "Hola $patientName, tu cita médica con $doctorName ha sido confirmada para el " .
                    \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y') .
                    " a las " . \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i') . ". " .
                    "Folio: #" . str_pad($this->appointment->id, 6, '0', STR_PAD_LEFT);

                $whatsapp->sendMessage($patientPhone, $message);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed: ' . $e->getMessage());
        }
    }
}

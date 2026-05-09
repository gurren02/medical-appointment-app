<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminderMail;

class SendWhatsAppRemindersCommand extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Envía recordatorios de WhatsApp para citas del día siguiente';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $tomorrow)
            ->where('status', Appointment::STATUS_PROGRAMADO)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas para mañana.');
            return;
        }

        $whatsapp = app(WhatsAppService::class);

        foreach ($appointments as $appointment) {
            $patientName = $appointment->patient->user->name;
            $doctorName = $appointment->doctor->user->name;
            $phone = $appointment->patient->user->phone;
            $date = Carbon::parse($appointment->date)->format('d/m/Y');
            $time = Carbon::parse($appointment->start_time)->format('H:i');

            $message = "Recordatorio {$appointment->duration}min: Hola $patientName, te recordamos que tienes una cita médica mañana $date a las $time con $doctorName. Folio: #" . str_pad($appointment->id, 6, '0', STR_PAD_LEFT);

            $whatsapp->sendMessage($phone, $message);
            
            // Envío de correo recordatorio
            Mail::to($appointment->patient->user->email)->send(new AppointmentReminderMail($appointment));

            $this->info("Recordatorio enviado a: $patientName ($phone)");
        }

        $this->info("Total recordatorios enviados: {$appointments->count()}");
    }
}

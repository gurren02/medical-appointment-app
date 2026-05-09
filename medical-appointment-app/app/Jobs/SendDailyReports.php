<?php

namespace App\Jobs;

use App\Mail\DailyReportMail;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendDailyReports implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct() {}

    public function handle(): void
    {
        $today = now()->format('Y-m-d');

        $allAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $today)
            ->orderBy('start_time')
            ->get();

        $adminEmail = env('MAIL_TO_ADMIN', 'admin@healthify.com');
        Mail::to($adminEmail)->send(new DailyReportMail($allAppointments, true, 'Administrador'));

        $doctors = Doctor::with('user')->get();
        foreach ($doctors as $doctor) {
            $doctorAppointments = $allAppointments->where('doctor_id', $doctor->id);
            if ($doctorAppointments->isNotEmpty()) {
                Mail::to($doctor->user->email)->send(
                    new DailyReportMail($doctorAppointments, false, $doctor->user->name)
                );
            }
        }
    }
}

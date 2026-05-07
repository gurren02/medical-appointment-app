<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }

        $reasons = [
            'Chequeo general',
            'Dolor de garganta',
            'Seguimiento de tratamiento',
            'Consulta de rutina',
            'Revisión de resultados',
            'Malestar estomacal',
        ];

        for ($i = 0; $i < 6; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            $date = Carbon::now()->addDays(rand(1, 15));
            $hour = rand(8, 11);
            $min = [0, 15, 30, 45][rand(0, 3)];
            
            $startTime = Carbon::create($date->year, $date->month, $date->day, $hour, $min, 0);
            $endTime = $startTime->copy()->addMinutes(15);

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'date' => $date->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'duration' => 15,
                'reason' => $reasons[$i],
                'status' => 1, // Programado
            ]);
        }
    }
}

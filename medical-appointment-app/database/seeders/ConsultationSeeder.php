<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ConsultationSeeder extends Seeder
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

        $diagnoses = [
            'Infección respiratoria leve',
            'Gastritis aguda',
            'Hipertensión arterial controlada',
            'Dermatitis alérgica',
            'Faringitis viral',
            'Lumbalgia mecánica',
        ];

        $treatments = [
            'Reposo por 3 días y abundante líquido.',
            'Dieta blanda y antiácidos según necesidad.',
            'Continuar con medicación habitual y control en un mes.',
            'Aplicar crema antihistamínica cada 12 horas.',
            'Gárgaras de agua con sal y analgésicos.',
            'Ejercicios de estiramiento y analgésicos si hay dolor.',
        ];

        $notes = [
            'Paciente muestra mejoría desde la última visita.',
            'Se recomienda realizar exámenes de laboratorio.',
            'Paciente refiere cumplir con el tratamiento anterior.',
            'Sin complicaciones aparentes.',
            'Se sugiere interconsulta con especialista.',
        ];

        $medications = [
            ['medication' => 'Paracetamol 500mg', 'dose' => '1 tableta', 'frequency' => 'cada 8 horas por 3 días'],
            ['medication' => 'Amoxicilina 875mg', 'dose' => '1 cápsula', 'frequency' => 'cada 12 horas por 7 días'],
            ['medication' => 'Omeprazol 20mg', 'dose' => '1 cápsula', 'frequency' => 'en ayunas por 15 días'],
            ['medication' => 'Ibuprofeno 400mg', 'dose' => '1 tableta', 'frequency' => 'cada 8 horas si hay dolor'],
            ['medication' => 'Cetirizina 10mg', 'dose' => '1 tableta', 'frequency' => 'cada noche por 5 días'],
        ];

        foreach ($patients as $patient) {
            // Crear 2 consultas pasadas para cada paciente
            for ($i = 0; $i < 2; $i++) {
                $doctor = $doctors->random();
                $date = Carbon::now()->subDays(rand(10, 60)); // Hace 10 a 60 días
                $hour = rand(8, 16);
                $min = [0, 15, 30, 45][rand(0, 3)];
                
                $startTime = Carbon::create($date->year, $date->month, $date->day, $hour, $min, 0);
                $endTime = $startTime->copy()->addMinutes(15);

                $appointment = Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patient->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'duration' => 15,
                    'reason' => 'Consulta de seguimiento',
                    'status' => Appointment::STATUS_COMPLETADO,
                ]);

                $consultation = Consultation::create([
                    'appointment_id' => $appointment->id,
                    'diagnosis' => $diagnoses[array_rand($diagnoses)],
                    'treatment' => $treatments[array_rand($treatments)],
                    'notes' => $notes[array_rand($notes)],
                ]);

                // Añadir 1-2 medicamentos aleatorios
                $numMeds = rand(1, 2);
                $selectedMeds = array_rand($medications, $numMeds);
                
                if (!is_array($selectedMeds)) {
                    $selectedMeds = [$selectedMeds];
                }

                foreach ($selectedMeds as $medIndex) {
                    Prescription::create(array_merge(
                        ['consultation_id' => $consultation->id],
                        $medications[$medIndex]
                    ));
                }
            }
        }
    }
}

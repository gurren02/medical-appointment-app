<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\BloodType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'jesusmutul15@gmail.com';
        
        // 1. Crear el Médico de Prueba
        $doctorUser = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Dr. Jesús Mutul (Test)',
                'password' => Hash::make('password'),
                'id_number' => '99999999',
                'phone' => '9991234567',
                'address' => 'Clínica de Pruebas',
            ]
        );

        $doctorUser->assignRole('Doctor');

        $doctor = Doctor::updateOrCreate(
            ['user_id' => $doctorUser->id],
            ['specialty' => 'Medicina General']
        );

        // Horarios para el médico de prueba (Lunes de 8am a 12pm)
        for ($h = 8; $h < 12; $h++) {
            for ($m = 0; $m < 60; $m += 30) {
                $startTime = sprintf("%02d:%02d:00", $h, $m);
                $endM = ($m + 30) % 60;
                $endH = ($m + 30 >= 60) ? $h + 1 : $h;
                $endTime = sprintf("%02d:%02d:00", $endH, $endM);
                
                \App\Models\DoctorSchedule::updateOrCreate([
                    'doctor_id' => $doctor->id,
                    'day' => 'LUNES',
                    'start_time' => $startTime,
                ], ['end_time' => $endTime]);
            }
        }

        // 2. Crear el Paciente de Prueba
        // Usaremos un correo ligeramente diferente o el mismo si el sistema lo permite (pero User suele ser único por email)
        // Crearemos un segundo usuario con un alias para pruebas de paciente
        $patientEmail = 'agustinopecho@gmail.com';
        
        $patientUser = User::updateOrCreate(
            ['email' => $patientEmail],
            [
                'name' => 'Jesús Mutul (Paciente Test)',
                'password' => Hash::make('password'),
                'id_number' => '88888888',
                'phone' => '9997654321',
                'address' => 'Casa de Pruebas',
            ]
        );

        $patientUser->assignRole('Paciente');

        $bloodType = BloodType::first();

        Patient::updateOrCreate(
            ['user_id' => $patientUser->id],
            [
                'blood_type_id' => $bloodType ? $bloodType->id : 1,
                'emergency_contact_name' => 'Contacto Emergencia',
                'emergency_contact_phone' => '9991112233',
            ]
        );
    }
}

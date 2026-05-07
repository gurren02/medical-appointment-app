<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorsData = [
            [
                'name' => 'Dr. Carlos Perez',
                'email' => 'carlos@test.com',
                'id_number' => '30000001',
                'phone' => '33333333',
                'specialty' => 'Cardiología',
            ],
            [
                'name' => 'Dra. Ana Gomez',
                'email' => 'ana@test.com',
                'id_number' => '30000002',
                'phone' => '33333333',
                'specialty' => 'Dermatología',
            ],
            [
                'name' => 'Dr. Luis Torres',
                'email' => 'luis@test.com',
                'id_number' => '30000003',
                'phone' => '33333333',
                'specialty' => 'Endocrinología',
            ],
            [
                'name' => 'Dra. Maria Lopez',
                'email' => 'maria@test.com',
                'id_number' => '30000004',
                'phone' => '33333334',
                'specialty' => 'Pediatría',
            ],
            [
                'name' => 'Dr. Jorge Rodriguez',
                'email' => 'jorge@test.com',
                'id_number' => '30000005',
                'phone' => '33333335',
                'specialty' => 'Ginecología',
            ],
            [
                'name' => 'Doctor Demo 1',
                'email' => 'doctor1@demo.com',
                'id_number' => '50000001',
                'phone' => '600000001',
                'specialty' => 'Ginecología',
            ],
            [
                'name' => 'Doctor Demo 2',
                'email' => 'doctor2@demo.com',
                'id_number' => '50000002',
                'phone' => '600000002',
                'specialty' => 'Geriatría',
            ],
            [
                'name' => 'Doctor Demo 3',
                'email' => 'doctor3@demo.com',
                'id_number' => '50000003',
                'phone' => '600000003',
                'specialty' => 'Hematología',
            ],
            [
                'name' => 'Doctor Demo 4',
                'email' => 'doctor4@demo.com',
                'id_number' => '50000004',
                'phone' => '600000004',
                'specialty' => 'Endocrinología',
            ],
            [
                'name' => 'Doctor Demo 5',
                'email' => 'doctor5@demo.com',
                'id_number' => '50000005',
                'phone' => '600000005',
                'specialty' => 'Cardiología',
            ],
        ];

        foreach ($doctorsData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'id_number' => $data['id_number'],
                    'phone' => $data['phone'],
                    'address' => 'Av. Principal 123',
                ]
            );

            $user->assignRole('Doctor');

            $doctor = Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty' => $data['specialty'],
                ]
            );

            // Inyectar horarios de prueba
            $days = ['LUNES', 'MARTES', 'MIÉRCOLES'];
            foreach ($days as $day) {
                for ($h = 8; $h < 12; $h++) {
                    for ($m = 0; $m < 60; $m += 15) {
                        $startTime = sprintf("%02d:%02d:00", $h, $m);
                        
                        $endM = ($m + 15) % 60;
                        $endH = ($m + 15 >= 60) ? $h + 1 : $h;
                        $endTime = sprintf("%02d:%02d:00", $endH, $endM);
                        
                        \App\Models\DoctorSchedule::updateOrCreate([
                            'doctor_id' => $doctor->id,
                            'day' => $day,
                            'start_time' => $startTime,
                        ], [
                            'end_time' => $endTime,
                        ]);
                    }
                }
            }
        }
    }
}

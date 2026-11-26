<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'drg. Andika Pratama',
                'photo' => 'images/doctor/1.png',
                'consultation_fee' => 150000
            ],
            [
                'name' => 'drg. Amanda Putri',
                'photo' => 'images/doctor/2.png',
                'consultation_fee' => 175000
            ],
            [
                'name' => 'drg. Kevin Wijaya',
                'photo' => 'images/doctor/3.png',
                'consultation_fee' => 200000
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::updateOrCreate(
                ['name' => $doctor['name']],
                $doctor
            );
        }
    }
}
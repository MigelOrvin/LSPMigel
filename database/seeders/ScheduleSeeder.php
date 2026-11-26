<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            'drg. Andika Pratama' => [
                [1, '09:00:00', '13:00:00'],
                [3, '10:00:00', '15:00:00'],
                [5, '09:00:00', '12:00:00'],
            ],

            'drg. Amanda Putri' => [
                [2, '10:00:00', '15:00:00'],
                [4, '09:00:00', '14:00:00'],
                [6, '08:00:00', '12:00:00'],
            ],

            'drg. Kevin Wijaya' => [
                [1, '13:00:00', '17:00:00'],
                [4, '13:00:00', '18:00:00'],
                [5, '14:00:00', '18:00:00'],
            ],
        ];

        foreach ($schedules as $name => $days) {

            $doctor = Doctor::where('name', $name)
                ->firstOrFail();

            foreach ($days as [$day, $start, $end]) {

                $doctor->schedules()->updateOrCreate(
                    [
                        'day_of_week' => $day
                    ],
                    [
                        'start_time' => $start,
                        'end_time' => $end
                    ]
                );
            }
        }
    }
}
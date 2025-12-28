<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $schedules = [

            // Muaythai Intermediate (ID = 1)
            [1, 'Monday', '19:00'],
            [1, 'Thursday', '19:00'],

            // Mat Pilates (ID = 2)
            [2, 'Wednesday', '09:00'],
            [2, 'Friday', '09:00'],

            // Mix Class 1 (ID = 3)
            [3, 'Wednesday', '19:00'],  // Mat Pilates
            [3, 'Sunday', '09:00'],     // Muaythai

            // Mix Class 2 (ID = 4)
            [4, 'Tuesday', '19:00'],    // Mat Pilates
            [4, 'Saturday', '09:30'],   // Muaythai

            // Mix Class 3 (ID = 5)
            [5, 'Thursday', '19:00'],   // Mat Pilates
            [5, 'Sunday', '11:00'],     // Body shaping

            // Mix Class 4 (ID = 6)
            [6, 'Friday', '19:00'],     // Body shaping
            [6, 'Sunday', '10:00'],     // Muaythai

            // Muaythai Beginner (ID = 7)
            [7, 'Tuesday', '19:00'],
            [7, 'Saturday', '08:00'],
        ];

        foreach ($schedules as $s) {
            Schedule::create([
                'class_id' => $s[0],
                'day' => $s[1],
                'class_time' => $s[2],
                'instructor' => null,
                'show_on_landing' => true,
            ]);
        }
    }
}

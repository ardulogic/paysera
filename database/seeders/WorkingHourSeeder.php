<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkingHour;

class WorkingHourSeeder extends Seeder
{
    public function run()
    {
        // Example: Open 09:00–17:00 from Monday (1) to Friday (5)
        $defaultHours = [
            0 => null, // Sunday - closed
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => null, // Saturday - closed
        ];

        foreach ($defaultHours as $day => $hours) {
            if ($hours) {
                WorkingHour::updateOrCreate(
                    ['day_of_week' => $day],
                    ['start_time' => $hours[0], 'end_time' => $hours[1]]
                );
            }
        }
    }
}

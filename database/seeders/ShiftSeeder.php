<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $shifts = [
            ['shift_start' => '08:00', 'shift_end' => '16:00'],
            ['shift_start' => '16:00', 'shift_end' => '24:00'],
            ['shift_start' => '00:00', 'shift_end' => '08:00'],
        ];

        foreach ($days as $day) {
            foreach ($shifts as $shift) {
                Shift::create(array_merge(['day' => $day], $shift));
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shift::create(['day' => 'Monday', 'shift_start' => '08:00', 'shift_end' => '16:00']);
        Shift::create(['day' => 'Monday', 'shift_start' => '16:00', 'shift_end' => '24:00']);
        Shift::create(['day' => 'Monday', 'shift_start' => '00:00', 'shift_end' => '08:00']);
    }
}

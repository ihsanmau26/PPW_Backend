<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorShift;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DoctorShift::create(['doctor_id' => 1, 'shift_id' => 1]);
        DoctorShift::create(['doctor_id' => 1, 'shift_id' => 2]);
        DoctorShift::create(['doctor_id' => 1, 'shift_id' => 3]);
    }
}

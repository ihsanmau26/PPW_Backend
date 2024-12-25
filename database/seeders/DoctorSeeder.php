<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::create([
            'user_id' => 2,
            'specialization' => 'General',
        ]);

        Doctor::create([
            'user_id' => 4,
            'specialization' => 'General',
        ]);

        Doctor::create([
            'user_id' => 6,
            'specialization' => 'Dental',
        ]);
    }
}

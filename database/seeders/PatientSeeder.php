<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::create([
            'user_id' => 3,
            'gender' => 'Male',
            'age' => 20,
            'date_of_birth' => '2004-01-01',
            'phone_number' => '081234567890',
        ]);

        Patient::create([
            'user_id' => 5,
            'gender' => 'Female',
            'age' => 18,
            'date_of_birth' => '2006-02-02',
            'phone_number' => '081298765432',
        ]);

        Patient::create([
            'user_id' => 7,
            'gender' => 'Female',
            'age' => 24,
            'date_of_birth' => '2000-03-31',
            'phone_number' => '081265473827',
        ]);
    }
}

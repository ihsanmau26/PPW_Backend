<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PatientSeeder::class,
            DoctorSeeder::class,
            ShiftSeeder::class,
            DoctorShiftSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            MedicineSeeder::class,     
            CheckupSeeder::class,
            PrescriptionDetailSeeder::class,      
            CheckupHistorySeeder::class,
        ]);
    }
}

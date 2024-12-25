<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrescriptionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrescriptionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrescriptionDetail::factory(15)->create();
    }
}

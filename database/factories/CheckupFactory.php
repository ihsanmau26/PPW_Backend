<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Checkup;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checkup>
 */
class CheckupFactory extends Factory
{
    protected $model = Checkup::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $patient = Patient::all()->random();
        $doctor = Doctor::all()->random();

        $prescription = Prescription::firstOrCreate([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ], [
            'prescription_date' => $this->faker->date(),
        ]);

        return [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'checkup_type' => $this->faker->randomElement(['Dental', 'General']),
            'checkup_date' => $this->faker->date(),
            'checkup_time' => $this->faker->time('H:i'),
            'status' => $this->faker->randomElement(['Selesai', 'Dalam Proses', 'Belum Selesai']),
        ];        
    }
}

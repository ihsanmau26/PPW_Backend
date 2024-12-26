<?php

namespace Database\Factories;

use App\Models\Checkup;
use App\Models\Prescription;
use App\Models\CheckupHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckupHistory>
 */
class CheckupHistoryFactory extends Factory
{
    protected $model = CheckupHistory::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $checkup = Checkup::where('status', 'Selesai')
                          ->doesntHave('checkup_histories')
                          ->inRandomOrder()
                          ->first();

        if (!$checkup) {
            $checkup = Checkup::factory()->create([
                'status' => 'Selesai',
            ]);
        }

        $prescription = Prescription::firstOrCreate([
            'patient_id' => $checkup->patient_id,
            'doctor_id' => $checkup->doctor_id,
        ], [
            'prescription_date' => $this->faker->date(),
        ]);

        return [
            'checkup_id' => $checkup->id,
            'prescription_id' => $prescription->id,
            'diagnosis' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}

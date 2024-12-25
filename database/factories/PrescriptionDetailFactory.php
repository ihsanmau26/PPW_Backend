<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrescriptionDetail>
 */
class PrescriptionDetailFactory extends Factory
{
    protected $model = PrescriptionDetail::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prescription_id' => Prescription::inRandomOrder()->first()->id,
            'medicine_id' => Medicine::inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'instructions' => $this->faker->sentence(),
        ];
    }
}

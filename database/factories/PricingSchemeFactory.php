<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PricingScheme>
 */
class PricingSchemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $duration = $this->faker->randomElement([60, 90, 120]);

        return [
            'field_id' => Field::factory(),
            'duration_minutes' => $duration,
            'price' => $this->faker->numberBetween(50, 200) * 1000,
            'description' => 'Sesi '.($duration / 60).' Jam',
        ];
    }
}

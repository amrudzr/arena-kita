<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),
            'field_name' => 'Lapangan '.$this->faker->randomElement(['A', 'B', 'C', '1', '2']),
            'sport_type' => $this->faker->randomElement(['Futsal', 'Badminton', 'Basket', 'Voli']),
            // 'field_photo_url' => $this->faker->imageUrl(640, 480, 'sports court', true),
            'field_photo_url' => null,
        ];
    }
}

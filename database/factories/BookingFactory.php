<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PricingScheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pricing_scheme_id' => PricingScheme::factory(),
            'booking_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'start_time' => $this->faker->randomElement(['09:00:00', '10:00:00', '15:00:00', '19:00:00']),
            'end_time' => $this->faker->randomElement(['10:00:00', '11:00:00', '16:00:00', '20:00:00']),
            'total_price' => $this->faker->numberBetween(50, 200) * 1000,
            'booking_status' => $this->faker->randomElement(['PENDING', 'CONFIRMED', 'COMPLETED']),
        ];
    }
}

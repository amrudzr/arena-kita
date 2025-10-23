<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'payment_method' => $this->faker->randomElement(['BCA VA', 'GoPay', 'OVO']),
            'payment_status' => 'SUCCESS',
            'payment_time' => $this->faker->dateTimeThisMonth(),
            'gateway_transaction_code' => $this->faker->unique()->sha1(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Field;
use App\Models\Owner;
use App\Models\PricingScheme;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenuePhoto;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 1 User (Penyewa) yang datanya kita tahu untuk login
        $testUser = User::factory()->create([
            'full_name' => 'Test User',
            'email' => 'user@arenakita.com',
            'password' => Hash::make('password'), // password = 'password'
        ]);

        // 2. Buat 10 User (Penyewa) acak lainnya
        User::factory(10)->create();

        // 3. Buat 1 Owner yang datanya kita tahu untuk login dashboard serta gunakan 'has' untuk langsung membuatkan venue miliknya
        Owner::factory()
            ->has(
                Venue::factory()->count(2) // Buatkan 2 venue untuk owner ini
                    ->has(VenuePhoto::factory()->count(4)) // Setiap venue punya 4 foto
                    ->has(
                        Field::factory()->count(3) // Setiap venue punya 3 lapangan
                            ->has(PricingScheme::factory()->count(2)) // Setiap lapangan punya 2 skema harga
                    )
            )
            ->create([
                'full_name' => 'Test Owner',
                'email' => 'owner@arenakita.com',
                'password' => Hash::make('password'), // password = 'password'
            ]);

        // 4. Buat 4 Owner acak lainnya, masing-masing dengan asetnya
        Owner::factory(4)
            ->has(
                Venue::factory()->count(rand(1, 3)) // Punya 1-3 venue
                    ->has(VenuePhoto::factory()->count(rand(3, 5)))
                    ->has(
                        Field::factory()->count(rand(2, 4))
                            ->has(PricingScheme::factory()->count(rand(1, 3)))
                    )
            )
            ->create();

        // Ambil semua user dan skema harga yang sudah ada di database
        $allUsers = User::all();
        $allPricingSchemes = PricingScheme::all();

        // Failsafe jika data gagal dibuat
        if ($allUsers->isEmpty() || $allPricingSchemes->isEmpty()) {
            $this->command->warn('Tidak ada User atau Pricing Scheme. Booking tidak dibuat.');

            return;
        }

        // Buat 50 booking acak
        foreach (range(1, 50) as $i) {
            $user = $allUsers->random();
            $scheme = $allPricingSchemes->random();

            // Tentukan jam booking
            $startTime = Carbon::createFromTime(rand(9, 20), 0, 0); // Jam 9 pagi - 8 malam
            $endTime = $startTime->clone()->addMinutes($scheme->duration_minutes);
            $bookingDate = now()->addDays(rand(-10, 20)); // Booking di masa lalu & masa depan

            // Tentukan status
            $bookingStatus = 'CONFIRMED';
            if ($bookingDate->isPast()) {
                $bookingStatus = 'COMPLETED';
            }

            $booking = Booking::factory()->create([
                'user_id' => $user->id,
                'pricing_scheme_id' => $scheme->id,
                'booking_date' => $bookingDate->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'total_price' => $scheme->price, // Ambil harga asli dari skema
                'booking_status' => $bookingStatus,
            ]);

            // Jika booking sudah selesai (COMPLETED), buatkan transaksinya
            if ($bookingStatus === 'COMPLETED') {
                Transaction::factory()->create([
                    'booking_id' => $booking->id,
                    'payment_status' => 'SUCCESS',
                    'payment_time' => $bookingDate->addMinutes(10), // Bayar 10 mnt setelah booking
                ]);
            }
        }
    }
}

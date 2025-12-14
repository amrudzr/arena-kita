<?php

namespace App\Services;

use App\Models\Field;
use App\Models\PricingScheme;
use App\Models\Transaction;
use App\Models\Venue;

class BookingService
{
    public function __construct() {}

    public function createBooking(array $request)
    {
        $user = auth()->guard('api_user')->user();

        return $user->bookings()->create($request);
    }

    public function detailBooking($booking): array
    {
        $pricingScheme = PricingScheme::findOrFail($booking->pricing_scheme_id);
        $field = Field::findOrFail($pricingScheme->field_id);
        $venue = Venue::findOrFail($field->venue_id);
        $transaction = Transaction::findOrFail($booking->id);

        return [
            'venue_name' => $venue->venue_name,
            'address' => $venue->address,
            'field_name' => $field->field_name,
            'sport_type' => $field->sport_type,
            'duration' => $pricingScheme->duration_minutes,
            'price' => $pricingScheme->price,
            'booking_date' => $booking->booking_date,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
            'total_price' => $booking->total_price,
            'payment_status' => $transaction->payment_status,
            'booking_status' => $booking->status,
        ];
    }
}

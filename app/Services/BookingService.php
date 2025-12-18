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
        $pricingScheme = PricingScheme::find($booking->pricing_scheme_id);
        $field = Field::find($pricingScheme->field_id);
        $venue = Venue::find($field->venue_id);

        $transaction = Transaction::where('booking_id', $booking->id)->first();

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
            'payment_status' => $transaction ? $transaction->payment_status : 'BELUM DIBAYAR',
            'booking_status' => $booking->booking_status,
            'qr_url' => $transaction?->gateway_transaction_code,
        ];
    }
}

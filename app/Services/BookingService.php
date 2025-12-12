<?php

namespace App\Services;

class BookingService
{
    public function __construct() {}

    public function createBooking(array $request)
    {
        $user = auth()->guard('api_user')->user();

        return $user->bookings()->create($request);
    }
}

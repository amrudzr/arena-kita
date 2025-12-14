<?php

namespace App\Services\Owner;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingService
{
    private function ensureOwnership(Booking $booking)
    {
        $booking->loadMissing(['pricingScheme.field.venue']);

        if (! $booking->pricingScheme) {
            throw ValidationException::withMessages([
                'booking' => 'Booking mengarah ke Skema Harga yang tidak ditemukan.',
            ]);
        }

        if (! $booking->pricingScheme->field) {
            throw ValidationException::withMessages([
                'booking' => 'Lapangan untuk booking ini tidak ditemukan.',
            ]);
        }

        if (! $booking->pricingScheme->field->venue) {
            throw ValidationException::withMessages([
                'booking' => 'Venue untuk booking ini tidak ditemukan.',
            ]);
        }

        $bookingOwnerId = $booking->pricingScheme->field->venue->owner_id;
        $currentOwnerId = Auth::guard('api_owner')->id();

        if ($bookingOwnerId !== $currentOwnerId) {
            throw ValidationException::withMessages([
                'booking' => 'Anda tidak memiliki akses ke booking ini.',
            ]);
        }
    }

    public function approveBooking(Booking $booking)
    {
        $this->ensureOwnership($booking);

        if ($booking->booking_status !== 'PENDING') {
            throw ValidationException::withMessages([
                'status' => 'Hanya booking dengan status PENDING yang bisa disetujui.',
            ]);
        }

        $booking->update([
            'booking_status' => 'CONFIRMED',
        ]);

        return $booking;
    }

    public function rejectBooking(Booking $booking)
    {
        $this->ensureOwnership($booking);

        if ($booking->booking_status === 'COMPLETED' || $booking->booking_status === 'CANCELLED') {
            throw ValidationException::withMessages([
                'status' => 'Booking ini tidak dapat ditolak karena sudah selesai atau dibatalkan.',
            ]);
        }

        $booking->update([
            'booking_status' => 'REJECTED',
        ]);

        return $booking;
    }
}

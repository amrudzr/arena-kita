<?php

namespace App\Services\Owner;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Mendapatkan semua booking milik Owner yang sedang login
     */
    public function getOwnerBookings($status = null)
    {
        $ownerId = Auth::guard('api_owner')->id();

        // Query Booking berdasarkan Owner via PricingScheme -> Field -> Venue
        $query = Booking::query()
            ->with(['user', 'pricingScheme.field.venue']) // Eager load
            ->whereHas('pricingScheme.field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            });

        if ($status) {
            $query->where('booking_status', $status);
        }

        return $query->latest('booking_date')->get();
    }

    /**
     * Mendapatkan statistik sederhana [F-4.5]
     */
    public function getStats()
    {
        $ownerId = Auth::guard('api_owner')->id();

        // Helper query
        $bookingsQuery = Booking::whereHas('pricingScheme.field.venue', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        });

        return [
            'total_bookings' => $bookingsQuery->count(),
            'pending_bookings' => (clone $bookingsQuery)->where('booking_status', 'PENDING')->count(),
            'total_income' => (clone $bookingsQuery)
                ->where('booking_status', 'COMPLETED') // Asumsi uang masuk jika completed/confirmed
                ->sum('total_price'),
            // Tambahkan income harian/bulanan di sini menggunakan Carbon
        ];
    }
}

<?php

namespace App\Services\Owner;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getOwnerBookingsQuery($status = null)
    {
        $ownerId = Auth::guard('api_owner')->id();

        $query = Booking::query()
            ->whereHas('pricingScheme.field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->whereHas('transaction')
            ->with(['user', 'pricingScheme.field.venue', 'transaction']);

        if ($status) {
            $query->where('booking_status', $status);
        }

        return $query->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc');
    }

    public function getStats()
    {
        $ownerId = Auth::guard('api_owner')->id();

        $baseQuery = Booking::query()
            ->whereHas('pricingScheme.field.venue', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->whereHas('transaction');

        return [
            'total_bookings' => (clone $baseQuery)->count(),
            'pending_bookings' => (clone $baseQuery)
                ->where('booking_status', 'PENDING')
                ->count(),
            'total_income' => (clone $baseQuery)
                ->where('booking_status', 'COMPLETED')
                ->sum('total_price'),
            'potential_income' => (clone $baseQuery)
                ->where('booking_status', 'CONFIRMED')
                ->sum('total_price'),
        ];
    }
}

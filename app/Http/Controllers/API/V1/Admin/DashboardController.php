<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Venue;
use App\Traits\ApiResponseTrait;
use Exception;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function stats()
    {
        try {
            // Hitung statistik global
            $totalUsers = User::where('role', 'user')->count();
            $totalOwners = Owner::count();
            $totalVenues = Venue::count();

            // Total Booking/Transaksi yang sukses
            $totalTransactions = Transaction::where('payment_status', 'SUCCESS')->count();

            // Estimasi GMV (Gross Merchandise Value) / Total Uang Masuk
            $totalRevenue = Transaction::where('payment_status', 'SUCCESS')
                ->join('bookings', 'transactions.booking_id', '=', 'bookings.id')
                ->sum('bookings.total_price');

            $data = [
                'total_users' => $totalUsers,
                'total_owners' => $totalOwners,
                'total_venues' => $totalVenues,
                'total_transactions' => $totalTransactions,
                'total_revenue' => 'Rp '.number_format($totalRevenue, 0, ',', '.'),
                'raw_total_revenue' => (float) $totalRevenue,
            ];

            return $this->sendSuccessWithData($data, 'Statistik admin berhasil diambil.');

        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

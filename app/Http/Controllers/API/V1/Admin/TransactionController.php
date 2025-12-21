<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\TransactionResource;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 20);
            if ($limit > 100) {
                $limit = 100;
            }

            $query = Transaction::with([
                'booking.user',
                'booking.pricingScheme.field.venue.owner',
            ])->latest();

            if ($request->has('status')) {
                $query->where('payment_status', $request->status);
            }

            $transactions = $query->paginate($limit);

            return $this->sendSuccessWithData(
                TransactionResource::collection($transactions),
                'Laporan transaksi global berhasil diambil.'
            );

        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil laporan transaksi global.', 500);
        }
    }
}

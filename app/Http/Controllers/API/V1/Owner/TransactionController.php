<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\TransactionResource;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $ownerId = Auth::guard('api_owner')->id();

            $query = Transaction::query()
                ->with(['booking.user', 'booking.pricingScheme.field.venue'])
                ->whereHas('booking.pricingScheme.field.venue', function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId);
                });

            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            $perPage = $request->input('limit', 10);
            $transactions = $query->latest('payment_time')->paginate($perPage);

            return $this->sendSuccessWithData(TransactionResource::collection($transactions), 'Riwayat transaksi berhasil diambil.');
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

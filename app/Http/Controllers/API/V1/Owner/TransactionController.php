<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\Owner\TransactionResource;

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

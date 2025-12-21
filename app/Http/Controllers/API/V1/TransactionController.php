<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TransactionRequest;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Booking;
use App\Services\TransactionService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function store(TransactionRequest $request)
    {
        try {
            $user = Auth::guard('api_user')->id();
            $booking = Booking::find($request['booking_id']);

            if ($booking->user_id !== $user) {
                return $this->sendError('Anda tidak memiliki akses untuk membuat transaksi dari booking ini.', [], 403);
            }

            $result = $this->transactionService->createTransaction($request->validated());

            return $this->sendSuccessWithData(new TransactionResource($result), 'Transaksi berhasil dibuat.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal membuat transaksi.', 500);
        }
    }
}

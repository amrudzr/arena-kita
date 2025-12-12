<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Midtrans\CoreApi;

class TransactionService
{
    use ApiResponseTrait;

    public function __construct() {}

    public function createTransaction(array $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->guard('api_user')->user();

            $booking = Booking::findOrFail($request['booking_id']);

            if ($booking->user_id !== $user->id) {
                throw new \Exception('Anda tidak memiliki akses untuk membuat transaksi dari booking ini.');
            }

            if (Transaction::where('booking_id', $request['booking_id'])->exists()) {
                $transaction = Transaction::where('booking_id', $request['booking_id'])->first();

                if ($transaction->payment_status === 'EXPIRE') {
                    $transaction->delete();
                } else {
                    throw new \Exception('Sudah ada transaksi yang dibuat untuk booking ini.');
                }
            }

            $orderId = 'AKT-'.$booking->id.'_'.time();

            $parts = explode(' ', trim($user->name));
            $firstName = $parts[0];
            $lastName = count($parts) > 1 ? $parts[count($parts) - 1] : '';

            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ],
                'qris' => [
                    'acquirer' => strtolower($request['payment_method']),
                ],
            ];

            $midtransResponse = CoreApi::charge($params);

            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_method' => $request['payment_method'],
                'payment_status' => strtoupper($midtransResponse->transaction_status),
            ]);

            DB::commit();

            return [
                'amount' => $midtransResponse->gross_amount,
                'transaction' => $transaction,
                'qr_image_url' => $midtransResponse->actions[0]->url ?? null,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function index(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'];
        preg_match('/-(\d+)_/', $orderId, $match);
        $numberId = $match[1];

        $transactionStatus = $payload['transaction_status'];

        $transaction = Transaction::where('booking_id', $numberId)->first();
        $booking = Booking::where('id', $numberId)->first();

        if (! $transaction) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $transaction->payment_status = 'SUCCESS';
            $booking->booking_status = 'SUCCESS';
            $transaction->payment_time = now();
        } elseif ($transactionStatus == 'expire') {
            $transaction->payment_status = 'EXPIRE';
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
            $transaction->payment_status = 'FAILED';
            $booking->booking_status = 'FAILED';
        }

        $transaction->save();
        $booking->save();

        return response()->json(['status' => 'success']);
    }
}

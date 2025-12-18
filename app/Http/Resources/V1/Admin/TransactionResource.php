<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Akses data nested
        $booking = $this->booking;
        $user = $booking->user ?? null;
        $pricing = $booking->pricingScheme ?? null;
        $field = $pricing->field ?? null;
        $venue = $field->venue ?? null;
        $owner = $venue->owner ?? null;

        // GENERATE CODE
        // 1. Ambil Timestamp: 20251212100708
        $timestamp = $this->created_at->format('YmdHis');

        // 2. Padding ID: 1 -> 0001, 25 -> 0025
        // str_pad($input, panjang_total, 'string_pengisi', ARAH_PENGISIAN)
        $paddedId = str_pad($this->id, 4, '0', STR_PAD_LEFT);

        // 3. Gabungkan
        $transactionCode = $timestamp.'-'.$paddedId;

        return [
            'id' => $this->id,
            'code' => $transactionCode,
            'payment_status' => $this->payment_status,
            'total_price' => 'Rp '.number_format($this->total_price, 0, ',', '.'),
            'raw_total_price' => (int) $this->total_price,
            'payment_method' => $this->payment_method ?? 'Transfer',
            'created_at' => $this->created_at->format('d M Y H:i'),

            // Info Pembayar (User)
            'user' => [
                'name' => $user->full_name ?? 'User Terhapus',
                'email' => $user->email ?? '-',
            ],

            // Info Tujuan (Venue & Owner)
            'destination' => [
                'venue_name' => $venue->venue_name ?? 'Venue Terhapus',
                'owner_name' => $owner->full_name ?? '-',
                'field_name' => $field->field_name ?? '-',
            ],

            // Info Jadwal Main
            'booking_detail' => [
                'date' => $booking->booking_date->format('d M Y'),
                'time' => $booking->start_time->format('H:i').' - '.$booking->end_time->format('H:i'),
            ],
        ];
    }
}

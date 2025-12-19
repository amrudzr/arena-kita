<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $booking = $this->booking;
        $user = $booking->user ?? null;
        $pricing = $booking->pricingScheme ?? null;
        $field = $pricing->field ?? null;
        $venue = $field->venue ?? null;
        $owner = $venue->owner ?? null;
        $timestamp = $this->created_at->format('YmdHis');
        $paddedId = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        $transactionCode = $timestamp.'-'.$paddedId;

        return [
            'id' => $this->id,
            'code' => $transactionCode,
            'payment_status' => $this->payment_status,
            'total_price' => 'Rp '.number_format($this->total_price, 0, ',', '.'),
            'raw_total_price' => (int) $this->total_price,
            'payment_method' => $this->payment_method ?? 'Transfer',
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'user' => [
                'name' => $user->full_name ?? 'User Terhapus',
                'email' => $user->email ?? '-',
            ],
            'destination' => [
                'venue_name' => $venue->venue_name ?? 'Venue Terhapus',
                'owner_name' => $owner->full_name ?? '-',
                'field_name' => $field->field_name ?? '-',
            ],
            'booking_detail' => [
                'date' => $booking->booking_date->format('Y-m-d'),
                'time' => $booking->start_time->format('H:i').' - '.$booking->end_time->format('H:i'),
            ],
        ];
    }
}

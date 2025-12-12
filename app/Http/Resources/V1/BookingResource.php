<?php

namespace App\Http\Resources\V1;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Booking */
class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pricing_scheme_id' => $this->pricing_scheme_id,
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'start_time' => $this->start_time->format('H:i'),
            'end_time' => $this->end_time->format('H:i'),
            'total_price' => 'Rp '.number_format($this->total_price, 0, ',', '.'),
            'booking_status' => $this->booking_status,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}

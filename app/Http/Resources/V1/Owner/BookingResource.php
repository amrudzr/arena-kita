<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'start_time' => $this->start_time->format('H:i'),
            'end_time' => $this->end_time->format('H:i'),
            'total_price' => 'Rp '.number_format($this->total_price, 0, ',', '.'),
            'raw_total_price' => (float) $this->total_price,
            'status' => $this->booking_status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'created_at_human' => $this->created_at->diffForHumans(),
            
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->full_name,
                    'email' => $this->user->email,
                    'phone' => $this->user->phone_number,
                ];
            }),

            'field_info' => $this->whenLoaded('pricingScheme', function () {
                return [
                    'field_name' => optional($this->pricingScheme->field)->field_name ?? '-',
                    'sport_type' => optional($this->pricingScheme->field)->sport_type ?? '-',
                    'venue_name' => optional(optional($this->pricingScheme->field)->venue)->venue_name ?? '-',
                ];
            }),
        ];
    }
}

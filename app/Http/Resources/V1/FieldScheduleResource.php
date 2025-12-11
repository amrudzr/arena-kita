<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Owner\PricingSchemeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource; // Reuse Resource Harga

class FieldScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'field_id' => $this['field']->id,
            'field_name' => $this['field']->field_name,
            'sport_type' => $this['field']->sport_type,
            'date' => $this['date'],
            'venue_operational' => [
                'open' => $this['field']->venue->opening_time?->format('H:i') ?? '00:00',
                'close' => $this['field']->venue->closing_time?->format('H:i') ?? '23:59',
            ],
            'pricing_schemes' => PricingSchemeResource::collection($this['field']->pricingSchemes),
            'booked_slots' => $this['bookings']->map(function ($booking) {
                return [
                    'start' => $booking->start_time->format('H:i'),
                    'end' => $booking->end_time->format('H:i'),
                    'status' => $booking->booking_status,
                ];
            }),
        ];
    }
}

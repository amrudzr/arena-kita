<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'booking' => [
                'booking_date' => $this['booking_date']->format('Y-m-d'),
                'start_time' => $this['start_time']->format('H:i'),
                'end_time' => $this['end_time']->format('H:i'),
                'total_price' => 'Rp '.number_format($this['total_price'], 0, ',', '.'),
                'booking_status' => $this['booking_status'],
            ],
            'venue' => [
                'venue_name' => $this['venue_name'],
                'address' => $this['address'],
            ],
            'field' => [
                'field_name' => $this['field_name'],
                'sport_type' => $this['sport_type'],
            ],
            'scheme' => [
                'duration' => $this['duration'],
                'price' => $this['price'],
            ],
            'payment' => [
                'payment_status' => $this['payment_status'],
            ],
        ];
    }
}

<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'payment_time' => $this->payment_time->format('Y-m-d H:i'),
            'booking' => new BookingResource($this->whenLoaded('booking')),
        ];
    }
}

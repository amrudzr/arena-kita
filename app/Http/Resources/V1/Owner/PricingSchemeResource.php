<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingSchemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'duration_minutes' => $this->duration_minutes,
            'price' => 'Rp '.number_format($this->price, 0, ',', '.'),
            'raw_price' => (float) $this->price,
            'description' => $this->description,
        ];
    }
}

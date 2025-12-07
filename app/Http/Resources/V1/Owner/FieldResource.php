<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use App\Http\Resources\V1\Owner\VenueResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\Owner\PricingSchemeResource;

class FieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->field_name,
            'type' => $this->sport_type,
            'status' => $this->status,
            'photo_url' => $this->field_photo_url,
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'pricing_schemes' => PricingSchemeResource::collection($this->whenLoaded('pricingSchemes')),
        ];
    }
}

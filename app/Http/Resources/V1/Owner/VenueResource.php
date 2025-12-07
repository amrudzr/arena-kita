<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use App\Http\Resources\V1\Owner\FieldResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\Owner\VenuePhotoResource;

class VenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->venue_name,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'gps_coordinate' => $this->gps_coordinate,
            'opening_time' => $this->opening_time?->format('H:i'),
            'closing_time' => $this->closing_time?->format('H:i'),
            'photos' => VenuePhotoResource::collection($this->whenLoaded('photos')),
            'fields' => FieldResource::collection($this->whenLoaded('fields')),
        ];
    }
}

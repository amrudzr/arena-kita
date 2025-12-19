<?php

namespace App\Http\Resources\V1\Admin;

use App\Http\Resources\V1\Owner\FieldResource;
use App\Http\Resources\V1\Owner\VenuePhotoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'venue_name' => $this->venue_name,
            'city' => $this->city,
            'owner' => $this->whenLoaded('owner', function () {
                return [
                    'id' => $this->owner->id,
                    'name' => $this->owner->full_name,
                    'email' => $this->owner->email,
                ];
            }),
            'address' => $this->when(! $request->routeIs('*.index'), $this->address),
            'description' => $this->when(! $request->routeIs('*.index'), $this->description),
            'gps_coordinate' => $this->when(! $request->routeIs('*.index'), $this->gps_coordinate),
            'opening_time' => $this->opening_time ? $this->opening_time->format('H:i') : null,
            'closing_time' => $this->closing_time ? $this->closing_time->format('H:i') : null,
            'photos' => VenuePhotoResource::collection($this->whenLoaded('venuePhoto')),
            'fields' => FieldResource::collection($this->whenLoaded('fields')),
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}

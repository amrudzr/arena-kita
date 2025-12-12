<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $firstPhoto = $this->whenLoaded('venuePhoto', function () {
            return $this->venuePhoto->first();
        });

        $thumbnailUrl = null;

        if ($firstPhoto) {
            $thumbnailUrl = $firstPhoto->venue_photo_url;
        }

        return [
            'id' => $this->id,
            'venue_name' => $this->venue_name,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'gps_coordinate' => $this->gps_coordinate,
            'opening_time' => $this->opening_time?->format('H:i'),
            'closing_time' => $this->closing_time?->format('H:i'),
            'thumbnail' => $thumbnailUrl,
            'photos' => VenuePhotoResource::collection($this->whenLoaded('venuePhoto')),
            'fields' => FieldResource::collection($this->whenLoaded('fields')),
        ];
    }
}

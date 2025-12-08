<?php

namespace App\Services\Owner;

use App\Models\VenuePhoto;
use Illuminate\Support\Facades\Storage;

class VenuePhotoService
{
    public function __construct() {}

    public function createPhoto(array $photoData, $venue)
    {
        $path = $photoData['photo_url']->store('venue', 'public');
        $photoData['photo_url'] = $path;

        return $venue->photos()->create($photoData, $venue);
    }

    public function deletePhoto(VenuePhoto $photo)
    {
        Storage::disk('public')->delete($photo->getRawOriginal('photo_url'));

        return $photo->delete();
    }
}

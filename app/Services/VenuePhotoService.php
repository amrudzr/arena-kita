<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VenuePhotoService
{
    public function __construct()
    {
    }

    public function createPhoto(array $photoData, $venue)
    {
        $path = $photoData['photo_url']->store('venue', 'public');
        $photoData['photo_url'] = $path;
        return $venue->venuePhoto()->create($photoData, $venue);
    }

    public function updatePhoto(array $photoData, $photo)
    {
        $currentPath = $photo->photo_url;
        if (isset($photoData['photo_url']) && $photoData['photo_url'] instanceof UploadedFile) {
            if (!empty($currentPath) && Storage::disk('public')->exists($currentPath)) {
                Storage::disk('public')->delete($currentPath);
            }
            $currentPath = $photoData['photo_url']->store('venue', 'public');
        }
        $photoData['photo_url'] = $currentPath;
        return $photo->update($photoData);
    }

    public function deletePhoto($photo)
    {
        return $photo->delete();
    }
}

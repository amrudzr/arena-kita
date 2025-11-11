<?php

namespace App\Services\Owner;

use App\Models\VenuePhoto;
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

    public function updatePhoto(array $photoData, VenuePhoto $photo)
    {
        if (isset($photoData['photo_url']) && $photoData['photo_url'] instanceof UploadedFile) {
            if ($oldPath = $photo->getRawOriginal('photo_url')) {
                Storage::disk('public')->delete($oldPath);
            }
            $newPath = $photoData['photo_url']->store('venue', 'public');
            $photoData['photo_url'] = $newPath;

            $photo->update(['photo_url' => $newPath]);
        }
        $otherData = $photoData;
        unset($otherData['photo_url']);
        if (!empty($otherData)) $photo->update($otherData);
        return $photo->refresh();
    }

    public function deletePhoto($photo)
    {
        return $photo->delete();
    }
}

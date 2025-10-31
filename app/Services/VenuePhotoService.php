<?php

namespace App\Services;

class VenuePhotoService
{
    public function __construct()
    {
    }

    public function createPhoto(array $photoData, $venue)
    {
        return $venue->venuePhoto()->create($photoData, $venue);
    }

    public function updatePhoto(array $photoData, $photo)
    {
        return $photo->update($photoData);
    }

    public function deletePhoto($photo)
    {
        return $photo->delete();
    }
}

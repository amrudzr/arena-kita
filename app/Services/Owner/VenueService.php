<?php

namespace App\Services\Owner;

class VenueService
{
    public function __construct() {}

    public function createVenue(array $request)
    {
        $owner = auth()->guard('api_owner')->user();

        return $owner->venues()->create($request);
    }

    public function updateVenue(array $request, $venue)
    {
        $venue->update($request);

        return $venue->refresh();
    }

    public function deleteVenue($venue)
    {
        return $venue->delete();
    }
}

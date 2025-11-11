<?php

namespace App\Services\Admin;

use App\Models\Venue;

class VenueService
{
    public function __construct()
    {
    }

    public function createVenue(array $request)
    {
        return Venue::class->create($request);
    }

    public function updateVenue(array $request, $venue)
    {
        return $venue->update($request);
    }

    public function deleteVenue($venue)
    {
        return $venue->delete();
    }
}

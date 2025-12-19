<?php

namespace App\Services\Admin;

use App\Models\Venue;

class VenueService
{
    public function __construct() {}

    public function createVenue(array $request)
    {
        return Venue::create($request);
    }

    public function updateVenue(array $request, Venue $venue)
    {
        $venue->update($request);

        return $venue;
    }

    public function deleteVenue(Venue $venue)
    {
        return $venue->delete();
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;

class VenueService
{
    public function __construct()
    {

    }

    public function createVenue(array $request)
    {
        $owner = auth()->guard('owner')->user();
        return $owner->venues()->create($request);
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

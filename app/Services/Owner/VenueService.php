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
<<<<<<< HEAD
        $venue->update($request);

        return $venue->refresh();
=======
        return $venue->update($request);
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4
    }

    public function deleteVenue($venue)
    {
        return $venue->delete();
    }
}

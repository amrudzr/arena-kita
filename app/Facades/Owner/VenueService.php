<?php

namespace App\Facades\Owner;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Owner\VenueService
 */
class VenueService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'venue';
    }
}

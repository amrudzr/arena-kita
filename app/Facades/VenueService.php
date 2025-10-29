<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\VenueService
 */
class VenueService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'venue';
    }
}

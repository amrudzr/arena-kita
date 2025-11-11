<?php

namespace App\Facades\Admin;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Admin\VenueService
 */
class VenueService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\Admin\VenueService::class;
    }
}

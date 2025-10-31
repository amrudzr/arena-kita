<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\VenuePhotoService
 */
class VenuePhotoService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\VenuePhotoService::class;
    }
}

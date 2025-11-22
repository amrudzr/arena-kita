<?php

namespace App\Facades\Owner;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Owner\VenuePhotoService
 */
class VenuePhotoService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\Owner\VenuePhotoService::class;
    }
}

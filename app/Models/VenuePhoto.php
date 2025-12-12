<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $venue_id
 */
class VenuePhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    protected function venuePhotoUrl()
    {
        return Attribute::make(
            get: function ($value) {
                if (! $value) {
                    return null;
                }

                if (str_contains($value, 'http')) {
                    return $value;
                }

                return Storage::disk('public')->url($value);
            }
        );
    }
}

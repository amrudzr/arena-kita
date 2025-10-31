<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

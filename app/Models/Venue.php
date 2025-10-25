<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed $owner_id
 */
class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function venuePhoto()
    {
        return $this->hasMany(VenuePhoto::class);
    }
}

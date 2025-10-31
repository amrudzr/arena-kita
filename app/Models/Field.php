<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'field_name',
        'sport_type',
        'field_photo_url',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function pricingSchemes()
    {
        return $this->hasMany(PricingScheme::class);
    }
}

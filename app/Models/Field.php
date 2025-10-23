<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function pricingSchemes()
    {
        return $this->hasMany(PricingScheme::class);
    }
}

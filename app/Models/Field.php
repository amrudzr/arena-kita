<?php

namespace App\Models;

use App\Enums\FieldStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => FieldStatus::class,
    ];

    protected function fieldPhotoUrl()
    {
        return Attribute::make(
            function ($value) {
                if ($value) {
                    return Storage::disk('public')->url($value);
                }

                return null;
            }
        );
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function pricingSchemes()
    {
        return $this->hasMany(PricingScheme::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'booking_date' => 'date',
            'start_time' => 'datetime:H:i', // Format sebagai jam:menit
            'end_time' => 'datetime:H:i',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricingScheme()
    {
        return $this->belongsTo(PricingScheme::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}

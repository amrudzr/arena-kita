<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOtp extends Model
{
    protected $table = 'user_otps';

    protected $fillable = [
        'otp_code',
        'user_id',
        'expired_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

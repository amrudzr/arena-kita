<?php

namespace App\Helpers;

use Illuminate\Support\Facades\RateLimiter;

class LoginThrottle
{
    public static function isSuspicious(): bool
    {
        $ip = request()->ip();
        $email = request('email');

        $key = 'login-attempts:'.$ip.$email;

        return RateLimiter::attempts($key) >= 3;
    }
}

<?php

namespace App\Listeners;

use App\Mail\OtpMail;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendOtpListener
{
    public function __construct()
    {
    }

    public function handle(Registered $event): void
    {
        $user = User::find($event->user->getAuthIdentifier());

        $otp = UserOtp::where('user_id', $user->id)
            ->where('expired_at', '>', now())
            ->first();

        if ($otp) {
            Mail::to($user->email)->send(new OtpMail($user, $otp->otp_code));
        }
    }
}

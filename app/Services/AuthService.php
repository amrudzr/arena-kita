<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function registerUser(array $validatedData)
    {
        try {
            return DB::transaction(function () use ($validatedData) {
                $existingUser = User::where('email', $validatedData['email'])->first();

                if ($existingUser && $existingUser->email_verified_at !== null) {
                    throw ValidationException::withMessages([
                        'email' => ['Email sudah terdaftar dan terverifikasi.'],
                    ]);
                }

                if ($existingUser && $existingUser->email_verified_at === null) {
                    UserOtp::where('user_id', $existingUser->id)->delete();
                    $existingUser->forceDelete();
                }

                $user = User::create([
                    'full_name' => $validatedData['full_name'],
                    'email' => $validatedData['email'],
                    'phone_number' => $validatedData['phone_number'],
                    'password' => $validatedData['password'],
                    'role' => 'user',
                    'email_verified_at' => null,
                ]);

                $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

                UserOtp::create([
                    'user_id' => $user->id,
                    'otp_code' => $otpCode,
                    'expired_at' => now()->addMinutes(5),
                ]);

                event(new Registered($user));

                return ['user' => $user];
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registrasi Error: '.$e->getMessage(), [
                'email' => $validatedData['email'] ?? null,
                'trace' => $e->getLine(),
            ]);

            throw ValidationException::withMessages([
                'error' => ['Terjadi kesalahan saat pendaftaran. Silakan coba lagi.'],
            ]);
        }
    }

    public function verifyOtp(array $validatedData)
    {
        $user = User::where('email', $validatedData['email'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['User tidak ditemukan.'],
            ]);
        }

        $otp = UserOtp::where('user_id', $user->id)
            ->where('otp_code', $validatedData['otp_code'])
            ->where('expired_at', '>', now())
            ->first();

        if (! $otp) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP tidak valid atau sudah kadaluarsa.'],
            ]);
        }

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);
        $otp->delete();

        $plainTextToken = $user->createToken('auth-token-'.$user->id)->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }

    public function attemptLogin(string $guard, array $credentials)
    {
        $key = 'login-attempts:'.request()->ip().$credentials['email'];

        $providerName = config("auth.guards.$guard.provider");
        $modelClass = config("auth.providers.$providerName.model");

        $user = $modelClass::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($key, 300);

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        RateLimiter::clear($key);

        $plainTextToken = $user->createToken('auth-token-'.$user->id)->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }
}

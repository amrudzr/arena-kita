<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function registerUser(array $validatedData)
    {
        $user = User::create([
            'full_name' => $validatedData['full_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'password' => $validatedData['password'],
            'role' => 'user',
        ]);
        return $user;
    }

    public function attemptLogin(string $guard, array $credentials)
    {
        if (! Auth::guard($guard)->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $user = Auth::guard($guard)->user();

        $plainTextToken = $user->createToken('auth-token-' . $user->id)->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken
        ];
    }
}
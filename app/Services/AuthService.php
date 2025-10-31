<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        $plainTextToken = $user->createToken('auth-token-'.$user->id)->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }

    public function attemptLogin(string $guard, array $credentials)
    {
        $providerName = config("auth.guards.$guard.provider");
        $modelClass = config("auth.providers.$providerName.model");

        $user = $modelClass::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $plainTextToken = $user->createToken('auth-token-'.$user->id)->plainTextToken;

        return [
            'user' => $user,
            'token' => $plainTextToken,
        ];
    }
}

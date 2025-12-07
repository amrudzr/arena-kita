<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required','email', 'exists:users,email'],
            'otp_code' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

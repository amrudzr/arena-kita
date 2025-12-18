<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'exists:bookings,id'],
            'payment_method' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_user')->check();
    }
}

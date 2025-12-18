<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pricing_scheme_id' => ['required', 'exists:pricing_schemes,id'],
            'booking_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'total_price' => ['required'],
            'booking_status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_user')->check();
    }
}

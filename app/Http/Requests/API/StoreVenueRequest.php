<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVenueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'venue_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'gps_coordinates' => 'nullable|string|max:100',
            'opening_times' => 'nullable|string|max:100',
            'closing_times' => 'nullable|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('owner')->check();
    }
}

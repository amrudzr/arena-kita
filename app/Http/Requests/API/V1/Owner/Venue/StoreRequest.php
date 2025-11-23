<?php

namespace App\Http\Requests\API\V1\Owner\Venue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'venue_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'gps_coordinate' => 'nullable|string|max:100',
            'opening_times' => 'nullable|string|max:100',
            'closing_times' => 'nullable|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_owner')->check();
    }

    public function messages(): array
    {
        return [
            'venue_name.required' => 'Nama venue wajib diisi.',
            'venue_name.string' => 'Nama venue harus teks.',
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus teks.',
            'city.required' => 'Kota wajib diisi.',
            'city.string' => 'Kota harus teks.',
        ];
    }
}

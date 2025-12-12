<?php

namespace App\Http\Requests\API\V1\Owner\Venue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'venue_name' => 'sometimes|string|max:150',
            'description' => 'sometimes|nullable|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string|max:50',
            'gps_coordinate' => 'sometimes|nullable|string|max:100',
            'opening_time' => 'sometimes|nullable|string|max:100',
            'closing_time' => 'sometimes|nullable|string|max:100',
            'owner_id' => 'sometimes|exists:owners,id',
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_owner')->check();
    }

    public function messages(): array
    {
        return [
            'venue_name.string' => 'Nama venue harus teks.',
            'address.string' => 'Alamat harus teks.',
            'owner_id.exists' => 'Pemilik yang dipilih tidak valid.',
        ];
    }
}

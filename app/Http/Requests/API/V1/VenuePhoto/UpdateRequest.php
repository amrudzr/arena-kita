<?php

namespace App\Http\Requests\API\V1\VenuePhoto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'photo_url' => 'sometimes|image|max:5120', // Maksimum 5MB
            'venue_id' => 'sometimes|exists:venues,id',
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_owner')->check();
    }
}

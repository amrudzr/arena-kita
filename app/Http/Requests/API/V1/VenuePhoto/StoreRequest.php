<?php

namespace App\Http\Requests\API\V1\VenuePhoto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return ['photo_url' => 'required|image|mimes:png,jpg,jpeg|max:5120', // Maksimum 5MB
        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_owner')->check();
    }

    public function messages(): array
    {
        return [
            'photo_url.required' => 'Foto venue wajib diunggah.',
            'photo_url.image' => 'File yang diunggah harus berupa gambar.',
            'photo_url.max' => 'Ukuran foto venue maksimal 5MB.',
        ];
    }
}

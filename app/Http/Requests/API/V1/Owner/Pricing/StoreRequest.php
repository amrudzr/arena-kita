<?php

namespace App\Http\Requests\API\V1\Owner\Pricing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $field = $this->route('field');

        return $field && $field->venue->owner_id == Auth::guard('api_owner')->id();
    }

    public function rules(): array
    {
        return [
            'duration_minutes' => 'required|integer|min:30',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'duration_minutes.required' => 'Durasi menit wajib diisi.',
            'duration_minutes.integer' => 'Durasi menit harus berupa angka bulat.',
            'duration_minutes.min' => 'Durasi menit minimal 30 menit.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal 0.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'description.max' => 'Deskripsi maksimal 100 karakter.',
        ];
    }
}

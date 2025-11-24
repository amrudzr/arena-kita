<?php

namespace App\Http\Requests\API\V1\Owner\Field;

use App\Enums\FieldStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum as EnumRule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $venue = $this->route('venue');

        return $venue && $venue->owner_id == Auth::guard('api_owner')->id();
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Anda tidak memiliki akses untuk menambahkan lapangan di venue ini.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_name' => 'required|string|max:100',
            'sport_type' => 'required|string|max:50',
            'field_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => ['required', new EnumRule(FieldStatus::class)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'field_name.required' => 'Nama lapangan wajib diisi.',
            'sport_type.required' => 'Jenis olahraga wajib diisi.',
            'status.required' => 'Status lapangan wajib diisi.',
            'status' => 'Status yang dipilih tidak valid.',
            'field_photo.image' => 'File foto lapangan harus berupa gambar.',
            'field_photo.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'field_photo.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ];
    }
}

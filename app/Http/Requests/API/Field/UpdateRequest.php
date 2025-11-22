<?php

namespace App\Http\Requests\API\Field;

use App\Enums\FieldStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum as EnumRule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $field = $this->route('field');

        return $field && $field->venue->owner_id == Auth::guard('api_owner')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_name' => 'sometimes|required|string|max:100',
            'sport_type' => 'sometimes|required|string|max:50',
            'field_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => ['sometimes', 'required', new EnumRule(FieldStatus::class)],
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

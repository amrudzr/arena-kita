<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'venue_id' => 'required|exists:venues,id',
            'field_name' => 'required|string|max:100',
            'sport_type' => 'required|string|max:50',
            'field_photo_url' => 'nullable|image|max:2048', // 2MB Max
            'status' => 'required|string|in:AVAILABLE,UNAVAILABLE,MAINTENANCE'
        ];

        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            $rules = array_map(function ($rule) {
                return 'sometimes|' . $rule;
            }, $rules);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'venue_id.required' => 'The venue is required',
            'venue_id.exists' => 'The selected venue does not exist',
            'field_name.required' => 'The field name is required',
            'field_name.max' => 'The field name cannot exceed 100 characters',
            'sport_type.required' => 'The sport type is required',
            'sport_type.max' => 'The sport type cannot exceed 50 characters',
            'field_photo_url.image' => 'The field photo must be an image',
            'field_photo_url.max' => 'The field photo cannot exceed 2MB',
            'status.required' => 'The status is required',
            'status.in' => 'The status must be either AVAILABLE, UNAVAILABLE, or MAINTENANCE'
        ];
    }
}
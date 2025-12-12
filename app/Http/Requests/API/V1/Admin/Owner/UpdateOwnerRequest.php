<?php

namespace App\Http\Requests\API\V1\Admin\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ownerId = $this->route('id');

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:owners,email,'.$ownerId,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:100',
        ];
    }
}

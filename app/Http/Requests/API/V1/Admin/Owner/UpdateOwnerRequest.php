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
        $owner = $this->route('owner');

        if (! $owner) {
            $owner = $this->route('id');
        }

        $ownerId = ($owner instanceof \App\Models\Owner) ? $owner->id : $owner;

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

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Mohon masukkan alamat email yang valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'bank_name.string' => 'Nama bank harus berupa teks.',
            'bank_name.max' => 'Nama bank tidak boleh lebih dari 50 karakter.',
            'account_number.string' => 'Nomor rekening harus berupa teks.',
            'account_number.max' => 'Nomor rekening tidak boleh lebih dari 50 karakter.',
            'account_name.string' => 'Nama pemilik rekening harus berupa teks.',
            'account_name.max' => 'Nama pemilik rekening tidak boleh lebih dari 100 karakter.',
        ];
    }
}

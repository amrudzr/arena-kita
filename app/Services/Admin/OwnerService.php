<?php

namespace App\Services\Admin;

use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerService
{
    public function createOwner(array $data)
    {
        return Owner::create([
            'full_name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'account_name' => $data['account_name'] ?? null,
        ]);
    }

    public function updateOwner(Owner $owner, array $data)
    {
        $updateData = [
            'full_name' => $data['name'] ?? $owner->full_name,
            'email' => $data['email'],
            'phone_number' => $data['phone'] ?? $owner->phone_number,
            'bank_name' => $data['bank_name'] ?? $owner->bank_name,
            'account_number' => $data['account_number'] ?? $owner->account_number,
            'account_name' => $data['account_name'] ?? $owner->account_name,
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $owner->update($updateData);

        return $owner->refresh();
    }

    public function deleteOwner(Owner $owner)
    {
        $owner->delete();
    }
}

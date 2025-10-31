<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileService
{
    public function updateProfile(User $user, array $validatedData)
    {
        return DB::transaction(function () use ($user, $validatedData) {
            
            // (Nanti di sini kita tambahkan logic untuk upload foto)
            // if (isset($validatedData['profile_photo'])) {
            //     // 1. Hapus foto lama (jika ada)
            //     // 2. Upload foto baru ke storage
            //     // 3. Simpan URL baru ke $validatedData['profile_photo_url']
            // }

            $user->update([
                'full_name' => $validatedData['full_name'],
                'phone_number' => $validatedData['phone_number'] ?? null,
                // 'profile_photo_url' => $validatedData['profile_photo_url'] ?? $user->profile_photo_url,
            ]);

            return $user;
        });
    }
}
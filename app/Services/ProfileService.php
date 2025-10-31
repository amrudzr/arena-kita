<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $user, array $validatedData)
    {
        return DB::transaction(function () use ($user, $validatedData) {
            
            $updateData = [
                'full_name' => $validatedData['full_name'],
                'phone_number' => $validatedData['phone_number'] ?? null,
            ];

            if (isset($validatedData['profile_photo'])) {
                if ($user->getRawOriginal('profile_photo_url')) {
                    Storage::disk('public')->delete($user->getRawOriginal('profile_photo_url'));
                }
                
                $file = $validatedData['profile_photo'];
                $path = $file->store('profile_photos', 'public');
                $updateData['profile_photo_url'] = $path;
            }
            
            $user->update($updateData);

            return $user;
        });
    }
}
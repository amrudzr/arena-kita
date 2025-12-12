<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'profile_photo_url' => $this->profile_photo_url
                ?? 'https://placehold.co/200x200/0d47a1/ffffff?text='.urlencode($this->full_name),
            'email_verified_at' => $this->email_verified_at
                ? $this->email_verified_at->locale('id')->translatedFormat('d F Y H:i')
                : null,
            'joined_at' => $this->created_at->locale('id')->translatedFormat('d F Y'),
        ];
    }
}

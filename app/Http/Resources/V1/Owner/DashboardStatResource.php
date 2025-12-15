<?php

namespace App\Http\Resources\V1\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_bookings' => $this->total_bookings ?? 0,
            'pending_bookings' => $this->pending_bookings ?? 0,
            'total_income' => 'Rp ' . number_format($this->total_income ?? 0, 0, ',', '.'),
            'raw_total_income' => (int) ($this->total_income ?? 0),
            'potential_income' => 'Rp ' . number_format($this->potential_income ?? 0, 0, ',', '.'),
            'raw_potential_income' => (int) ($this->potential_income ?? 0),
        ];
    }
}

<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'transaction' => [
                'booking_id' => $this['transaction']->booking_id,
                'payment_method' => $this['transaction']->payment_method,
                'payment_status' => $this['transaction']->payment_status,
            ],
            'amount' => $this['amount'],
            'qr_image_url' => $this['qr_image_url'],
        ];
    }
}

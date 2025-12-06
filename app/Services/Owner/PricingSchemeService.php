<?php

namespace App\Services\Owner;

use App\Models\Field;
use App\Models\PricingScheme;

class PricingSchemeService
{
    public function createScheme(Field $field, array $data)
    {
        return $field->pricingSchemes()->create($data);
    }

    public function updateScheme(PricingScheme $scheme, array $data)
    {
        $scheme->update($data);

        return $scheme->refresh();
    }

    public function deleteScheme(PricingScheme $scheme)
    {
        if ($scheme->bookings()->exists()) {
            throw new \Exception('Tidak dapat menghapus harga yang sudah memiliki riwayat booking.');
        }

        return $scheme->delete();
    }
}

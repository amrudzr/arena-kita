<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FieldService
{
    public function storeField(Venue $venue, array $validatedData)
    {
        $path = null;
        if (isset($validatedData['field_photo'])) {
            $path = $validatedData['field_photo']->store('fields', 'public');
        }

        $field = $venue->fields()->create([
            'field_name' => $validatedData['field_name'],
            'sport_type' => $validatedData['sport_type'],
            'status' => $validatedData['status'],
            'field_photo_url' => $path,
        ]);

        return $field;
    }

    public function updateField(Field $field, array $validatedData)
    {
        $updateData = $validatedData;

        if (isset($validatedData['field_photo'])) {
            if ($oldPath = $field->getRawOriginal('field_photo_url')) {
                Storage::disk('public')->delete($oldPath);
            }

            $newPath = $validatedData['field_photo']->store('fields', 'public');
            $updateData['field_photo_url'] = $newPath;
        }

        unset($updateData['field_photo']);

        $field->update($updateData);

        return $field->refresh();
    }

    public function deleteField(Field $field): void
    {
        DB::transaction(function () use ($field) {
            if ($oldPath = $field->getRawOriginal('field_photo_url')) {
                Storage::disk('public')->delete($oldPath);
            }

            $field->pricingSchemes()->delete();

            $field->delete();
        });
    }
}

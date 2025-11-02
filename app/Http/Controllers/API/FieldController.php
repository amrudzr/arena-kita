<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Field\StoreRequest;
use App\Http\Requests\API\Field\UpdateRequest;
use App\Models\Field;
use App\Models\Venue;
use App\Services\FieldService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    use ApiResponseTrait;

    protected $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function store(StoreRequest $request, Venue $venue)
    {
        try {
            $field = $this->fieldService->storeField($venue, $request->validated());

            return $this->sendSuccessWithData($field, 'Lapangan berhasil dibuat.', 201);

        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'venue_id' => $venue->id,
            ];

            return $this->sendInternalError($e, 'Gagal membuat lapangan.', 500, $context);
        }
    }

    public function update(UpdateRequest $request, Field $field)
    {
        try {
            $field = $this->fieldService->updateField($field, $request->validated());

            return $this->sendSuccessWithData($field, 'Lapangan berhasil diperbarui.');

        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'field_id' => $field->id,
            ];

            return $this->sendInternalError($e, 'Gagal memperbarui lapangan.', 500, $context);
        }
    }

    public function destroy(Request $request, Field $field)
    {
        if ($field->venue->owner_id !== $request->user('api_owner')->id) {
            return $this->sendError('Anda tidak memiliki izin untuk menghapus lapangan ini.', [], 403);
        }

        try {
            $this->fieldService->deleteField($field);

            return $this->sendSuccess('Lapangan berhasil dihapus.');

        } catch (Exception $e) {
            $context = ['context' => __METHOD__, 'field_id' => $field->id];

            return $this->sendInternalError($e, 'Gagal menghapus lapangan.', 500, $context);
        }
    }
}

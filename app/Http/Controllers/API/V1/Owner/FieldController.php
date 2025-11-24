<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Field\StoreRequest;
use App\Http\Requests\API\V1\Owner\Field\UpdateRequest;
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

    public function index(Request $request, Venue $venue)
    {
        try {
            $query = $venue->fields();

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('sport_type')) {
                $query->where('sport_type', $request->sport_type);
            }

            return $this->sendSuccessWithData($query->get(), 'Berhasil menampilkan daftar lapangan.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show(Venue $venue, Field $field)
    {
        try {
            if ($field->venue_id !== $venue->id) {
                return $this->sendError('Lapangan tidak ditemukan di venue ini.', [], 404);
            }

            $field->load('pricingSchemes');

            return $this->sendSuccessWithData($field, 'Berhasil menampilkan detail lapangan.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(StoreRequest $request, Venue $venue)
    {
        try {
            $ownerId = $request->user('api_owner')->id;

            if (! $venue || $venue->owner_id !== $ownerId) {
                return $this->sendError('Lapangan tidak valid untuk venue ini.', [], 404);
            }

            $field = $this->fieldService->storeField($venue, $request->validated());

            return $this->sendSuccessWithData($field, 'Lapangan berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateRequest $request, Venue $venue, Field $field)
    {
        try {
            $ownerId = $request->user('api_owner')->id;
            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Venue tidak ditemukan atau Anda tidak memiliki akses.', [], 404);
            }

            if ($field->venue_id !== $venue->id) {
                return $this->sendError('Lapangan tidak ditemukan di venue ini.', [], 404);
            }

            $updatedField = $this->fieldService->updateField($field, $request->validated());

            return $this->sendSuccessWithData($updatedField, 'Lapangan berhasil diperbarui.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(Request $request, Venue $venue, Field $field)
    {
        try {
            if ($field->venue_id !== $venue->id) {
                return $this->sendError('Lapangan tidak ditemukan.', [], 404);
            }

            if ($venue->owner_id !== $request->user('api_owner')->id) {
                return $this->sendError('Anda tidak memiliki izin.', [], 403);
            }

            $this->fieldService->deleteField($field);

            return $this->sendSuccess('Lapangan berhasil dihapus.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

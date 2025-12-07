<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Field\StoreRequest;
use App\Http\Requests\API\V1\Owner\Field\UpdateRequest;
use App\Http\Resources\V1\Owner\FieldResource;
use App\Models\Field;
use App\Models\Venue;
use App\Services\Owner\FieldService;
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

            return $this->sendSuccessWithData(FieldResource::collection($query->get()), 'Berhasil menampilkan daftar lapangan.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show(Field $field)
    {
        $field->load('venue:id,owner_id', 'pricingSchemes');

        if ($field->venue->owner_id !== auth()->id()) {
            return $this->sendError('Unauthorized', [], 403);
        }

        return $this->sendSuccessWithData(new FieldResource($field), 'Detail lapangan berhasil diambil.');
    }

    public function store(StoreRequest $request, Venue $venue)
    {
        try {
            $ownerId = $request->user('api_owner')->id;

            if (! $venue || $venue->owner_id !== $ownerId) {
                return $this->sendError('Lapangan tidak valid untuk venue ini.', [], 404);
            }

            $field = $this->fieldService->storeField($venue, $request->validated());

            return $this->sendSuccessWithData(new FieldResource($field), 'Lapangan berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateRequest $request, Field $field)
    {
        try {
            if ($field->venue->owner_id !== auth()->id()) {
                return $this->sendError('Unauthorized', [], 403);
            }
            $updatedField = $this->fieldService->updateField($field, $request->validated());

            return $this->sendSuccessWithData(new FieldResource($field->refresh()), 'Data lapangan berhasil diperbarui.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(Field $field)
    {
        try {
            if ($field->venue->owner_id !== auth()->id()) {
                return $this->sendError('Anda tidak memiliki akses ke lapangan ini.', [], 403);
            }

            $field->delete();

            return $this->sendSuccess('Lapangan berhasil dihapus.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

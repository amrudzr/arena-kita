<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Pricing\StoreRequest;
use App\Http\Resources\V1\Owner\PricingSchemeResource;
use App\Models\Field;
use App\Models\PricingScheme;
use App\Services\Owner\PricingSchemeService;
use App\Traits\ApiResponseTrait;

class PricingSchemeController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(PricingSchemeService $service)
    {
        $this->service = $service;
    }

    public function index(Field $field)
    {
        if ($field->venue->owner_id !== auth()->guard('api_owner')->id()) {
            return $this->sendError('Anda tidak memiliki akses.', [], 403);
        }

        return $this->sendSuccessWithData(PricingSchemeResource::collection($field->pricingSchemes), 'Data harga berhasil diambil.');
    }

    public function store(StoreRequest $request, Field $field)
    {
        try {
            $data = $this->service->createScheme($field, $request->validated());

            return $this->sendSuccessWithData(new PricingSchemeResource($data), 'Skema harga berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(Field $field, PricingScheme $pricing)
    {
        try {
            if ($pricing->field_id !== $field->id || $field->venue->owner_id !== auth()->guard('api_owner')->id()) {
                return $this->sendError('Skema harga tidak ditemukan atau Anda tidak memiliki akses.', [], 403);
            }

            $this->service->deleteScheme($pricing);

            return $this->sendSuccess('Skema harga berhasil dihapus.');
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

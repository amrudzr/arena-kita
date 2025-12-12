<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Owner\StoreOwnerRequest;
use App\Http\Requests\API\V1\Admin\Owner\UpdateOwnerRequest;
use App\Http\Resources\V1\Admin\OwnerResource;
use App\Models\Owner;
use App\Services\Admin\OwnerService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    use ApiResponseTrait;

    protected $ownerService;

    public function __construct(OwnerService $ownerService)
    {
        $this->ownerService = $ownerService;
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);

            if ($limit > 100) {
                $limit = 100;
            }

            $owners = Owner::withCount('venues')
                ->latest()
                ->paginate($limit);

            return $this->sendSuccessWithData(
                OwnerResource::collection($owners),
                'Daftar owner berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(StoreOwnerRequest $request)
    {
        try {
            $owner = $this->ownerService->createOwner($request->validated());

            return $this->sendSuccessWithData(
                new OwnerResource($owner),
                'Owner berhasil ditambahkan.',
                201
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show($id)
    {
        try {
            $owner = Owner::withCount('venues')->find($id);

            if (! $owner) {
                return $this->sendError('Owner tidak ditemukan.', [], 404);
            }

            return $this->sendSuccessWithData(
                new OwnerResource($owner),
                'Detail owner berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateOwnerRequest $request, $id)
    {
        try {
            $owner = Owner::find($id);
            if (! $owner) {
                return $this->sendError('Owner tidak ditemukan.', [], 404);
            }

            $updatedOwner = $this->ownerService->updateOwner($owner, $request->validated());

            return $this->sendSuccessWithData(
                new OwnerResource($updatedOwner),
                'Data owner berhasil diperbarui.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $owner = Owner::find($id);
            if (! $owner) {
                return $this->sendError('Owner tidak ditemukan.', [], 404);
            }

            // Cek aset sebelum hapus
            if ($owner->venues()->count() > 0) {
                return $this->sendError('Tidak dapat menghapus Owner yang masih memiliki Venue aktif.', [], 400);
            }

            $this->ownerService->deleteOwner($owner);

            return $this->sendSuccess('Owner berhasil dihapus.');
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Venue\StoreRequest;
use App\Http\Requests\API\V1\Admin\Venue\UpdateRequest;
use App\Http\Resources\V1\Admin\VenueResource;
use App\Models\Venue;
use App\Services\Admin\VenueService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    use ApiResponseTrait;

    private VenueService $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);

            $data = Venue::with('owner:id,full_name,email')
                ->latest()
                ->paginate($limit);

            return $this->sendSuccessWithData(
                VenueResource::collection($data),
                'Berhasil menampilkan daftar venue.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil daftar venue.', 500);
        }
    }

    public function show(Venue $venue)
    {
        try {
            $venue->load([
                'owner:id,full_name,email',
                'venuePhoto',
                'fields',
            ]);

            if (! $venue) {
                return $this->sendError('Venue tidak ditemukan.', [], 404);
            }

            return $this->sendSuccessWithData(
                new VenueResource($venue),
                'Detail venue berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil detail venue.', 500);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $venue = $this->venueService->createVenue($request->validated());

            $venue->load('owner:id,full_name,email');

            return $this->sendSuccessWithData(
                new VenueResource($venue),
                'Venue berhasil ditambahkan.',
                201
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateRequest $request, Venue $venue)
    {
        try {
            $updatedVenue = $this->venueService->updateVenue($request->validated(), $venue);

            $updatedVenue->load('owner:id,full_name,email', 'venuePhoto', 'fields');

            return $this->sendSuccessWithData(
                new VenueResource($updatedVenue),
                'Venue berhasil diperbarui.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal memperbarui venue.', 500);
        }
    }

    public function destroy(Venue $venue)
    {
        try {
            $this->venueService->deleteVenue($venue);

            return $this->sendSuccess('Venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal menghapus venue.', 500);
        }
    }
}

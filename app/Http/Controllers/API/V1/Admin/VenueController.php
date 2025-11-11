<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Venue\DestroyRequest;
use App\Http\Requests\API\V1\Admin\Venue\StoreRequest;
use App\Http\Requests\API\V1\Admin\Venue\UpdateRequest;
use App\Models\Venue;
use App\Services\Admin\VenueService;
use App\Traits\ApiResponseTrait;
use Exception;

class VenueController extends Controller
{
    use ApiResponseTrait;

    private VenueService $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function index()
    {
        try {
            $data = Venue::with('owner:id,full_name,email')->get();

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show(Venue $venue)
    {
        try {
            $data = Venue::with('owner:id,full_name,email')->find($venue->id);

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $venue = $this->venueService->createVenue($request->validated());

            return $this->sendSuccessWithData($venue, 'Venue berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateRequest $request, Venue $venue)
    {
        try {
            $venue = $this->venueService->updateVenue($request->validated(), $venue);

            return $this->sendSuccessWithData($venue, 'Venue berhasil diupdate.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue)
    {
        try {
            $venue = $this->venueService->deleteVenue($venue);

            return $this->sendSuccessWithData($venue, 'Venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

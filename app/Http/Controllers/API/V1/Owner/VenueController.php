<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Venue\DestroyRequest;
use App\Http\Requests\API\V1\Owner\Venue\StoreRequest;
use App\Http\Requests\API\V1\Owner\Venue\UpdateRequest;
use App\Models\Owner;
use App\Models\Venue;
use App\Services\Owner\VenueService;
use App\Traits\ApiResponseTrait;
use Exception;


/**
 * @group Venue Management
 *
 * APIs for managing venues
 */
class VenueController extends Controller
{
    use ApiResponseTrait;

    private VenueService $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function index(Owner $owner)
    {
        try {
            $data = Venue::with('owner:id,full_name,email')->where('owner_id', $owner->id)->get();
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

    public function update(UpdateRequest $request, Venue $venue)    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) return $this->sendError('Venue tidak bisa diubah.', [], 400);
            $update = $this->venueService->updateVenue($request->validated(), $venue);
            return $this->sendSuccessWithData($update, 'Venue berhasil diupdate.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue)
    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) return $this->sendError('Venue tidak bisa dihapus.', [], 400);
            $delete = $this->venueService->deleteVenue($venue);
            return $this->sendSuccessWithData($delete, 'Venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

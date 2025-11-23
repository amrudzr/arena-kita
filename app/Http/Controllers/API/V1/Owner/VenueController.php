<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Venue\DestroyRequest;
use App\Http\Requests\API\V1\Owner\Venue\StoreRequest;
use App\Http\Requests\API\V1\Owner\Venue\UpdateRequest;
use App\Models\Venue;
use App\Services\Owner\VenueService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        try {
            $data = Venue::with('owner:id,full_name,email')
                ->where('owner_id', Auth::guard('api_owner')->id())
                ->whereNull('deleted_at')
                ->get();

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan venue.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show(Venue $venue)
    {
        try {
            $ownerId = Auth::guard('api_owner')->id();

            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Tidak dapat mengakses venue yang bukan milik Anda.', [], 403);
            }

            $data = $venue->load(['owner:id,full_name,email', 'venuePhoto:id,venue_id,photo_url']);

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan detail venue.', 200);
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
            $ownerId = Auth::guard('api_owner')->id();

            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Tidak dapat mengakses venue yang bukan milik Anda.', [], 403);
            }

            $update = $this->venueService->updateVenue($request->validated(), $venue);

            return $this->sendSuccessWithData($update, 'Venue berhasil diupdate.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue)
    {
        try {
            $ownerId = Auth::guard('api_owner')->id();

            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Tidak dapat mengakses venue yang bukan milik Anda.', [], 403);
            }

            $delete = $this->venueService->deleteVenue($venue);

            return $this->sendSuccessWithData($delete, 'Venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

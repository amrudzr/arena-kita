<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\Venue\DestroyRequest;
use App\Http\Requests\API\V1\Owner\Venue\StoreRequest;
use App\Http\Requests\API\V1\Owner\Venue\UpdateRequest;
<<<<<<< HEAD
=======
use App\Models\Owner;
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4
use App\Models\Venue;
use App\Services\Owner\VenueService;
use App\Traits\ApiResponseTrait;
use Exception;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
=======
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4

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

<<<<<<< HEAD
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
=======
    public function index(Owner $owner)
    {
        try {
            $data = Venue::with('owner:id,full_name,email')->where('owner_id', $owner->id)->get();

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan.', 200);
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4
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
<<<<<<< HEAD
            $ownerId = Auth::guard('api_owner')->id();

            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Tidak dapat mengakses venue yang bukan milik Anda.', [], 403);
            }

=======
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) {
                return $this->sendError('Venue tidak bisa diubah.', [], 400);
            }
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4
            $update = $this->venueService->updateVenue($request->validated(), $venue);

            return $this->sendSuccessWithData($update, 'Venue berhasil diupdate.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue)
    {
        try {
<<<<<<< HEAD
            $ownerId = Auth::guard('api_owner')->id();

            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Tidak dapat mengakses venue yang bukan milik Anda.', [], 403);
            }

=======
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) {
                return $this->sendError('Venue tidak bisa dihapus.', [], 400);
            }
>>>>>>> 5431e20e28c40d63fceb54a631c3a9d9e58160b4
            $delete = $this->venueService->deleteVenue($venue);

            return $this->sendSuccessWithData($delete, 'Venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

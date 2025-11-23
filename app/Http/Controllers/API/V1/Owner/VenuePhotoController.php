<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Owner\VenuePhoto\DestroyRequest;
use App\Http\Requests\API\V1\Owner\VenuePhoto\StoreRequest;
use App\Models\Venue;
use App\Models\VenuePhoto;
use App\Services\Owner\VenuePhotoService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * @group Venue Photo Management
 *
 * APIs for managing venue photos
 */
class VenuePhotoController extends Controller
{
    use ApiResponseTrait;

    private VenuePhotoService $venuePhotoService;

    public function __construct(VenuePhotoService $venuePhotoService)
    {
        $this->venuePhotoService = $venuePhotoService;
    }

    public function index(Venue $venue)
    {
        try {
            $ownerId = Auth::guard('api_owner')->id();
            if ($venue->owner_id !== $ownerId) {
                return $this->sendError('Venue tidak ditemukan atau Anda tidak memiliki akses.', [], 404);
            }

            $data = $venue->venuePhoto()
                ->with('venue:id,venue_name')
                ->get();

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan foto.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(StoreRequest $request, Venue $venue)
    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) {
                return $this->sendError('Foto venue tidak dapat ditambah.', [], 400);
            }

            $venuePhoto = $this->venuePhotoService->createPhoto($request->validated(), $venue);

            return $this->sendSuccessWithData($venuePhoto, 'Foto venue berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue, VenuePhoto $photo)
    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) {
                return $this->sendError('Foto venue tidak dapat dihapus.', [], 400);
            }

            if ($photo->venue_id !== $venue->id) {
                return $this->sendError('Foto tidak ditemukan di venue ini.', [], 404);
            }

            $this->venuePhotoService->deletePhoto($photo);

            return $this->sendSuccess('Foto venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

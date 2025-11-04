<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\VenuePhoto\DestroyRequest;
use App\Http\Requests\API\V1\VenuePhoto\UpdateRequest;
use App\Traits\ApiResponseTrait;
use App\Services\VenuePhotoService;
use App\Models\VenuePhoto;
use App\Models\Venue;
use App\Http\Requests\API\V1\VenuePhoto\StoreRequest;
use Exception;
use Illuminate\Support\Facades\Log;

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
            $data = VenuePhoto::with('venue:id,owner_id,venue_name')->where('venue_id', $venue->id)->get();
            return $this->sendSuccessWithData($data, 'Berhasil menampilkan foto.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(StoreRequest $request, Venue $venue) {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) return $this->sendError('Foto venue tidak dapat ditambah.', [], 400);
            $venuePhoto = $this->venuePhotoService->createPhoto($request->validated(), $venue);
            return $this->sendSuccessWithData($venuePhoto, 'Foto venue berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function update(UpdateRequest $request, Venue $venue, VenuePhoto $photo)
    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) return $this->sendError('Foto venue tidak bisa diubah.', [], 400);
            $update = $this->venuePhotoService->updatePhoto($request->validated(), $photo);
            return $this->sendSuccessWithData($update, 'Foto venue berhasil diupdate.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function destroy(DestroyRequest $request, Venue $venue, VenuePhoto $photo)
    {
        try {
            $owner = $request->user('api_owner');
            if ($venue->owner_id !== $owner->id) return $this->sendError('Foto venue tidak dapat dihapus.', [], 400);
            $delete = $this->venuePhotoService->deletePhoto($photo);
            return $this->sendSuccessWithData($delete, 'Foto venue berhasil dihapus.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

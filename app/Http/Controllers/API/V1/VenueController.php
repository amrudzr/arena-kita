<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\VenueResource;
use App\Models\Venue;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('limit', 10);

            $query = Venue::with('venuePhoto');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('venue_name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            }

            if ($request->has('sport_type')) {
                $query->whereHas('fields', function ($q) use ($request) {
                    $q->where('sport_type', $request->sport_type);
                });
            }

            $venues = $query->latest()->paginate($perPage);

            return $this->sendSuccessWithData(
                VenueResource::collection($venues),
                'Daftar venue berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show($id)
    {
        try {
            $venue = Venue::with(['venuePhoto', 'fields.pricingSchemes'])->find($id);

            if (! $venue) {
                return $this->sendError('Venue tidak ditemukan.', [], 404);
            }

            return $this->sendSuccessWithData(
                new VenueResource($venue),
                'Detail venue berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

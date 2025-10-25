<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\DestroyVenueRequest;
use App\Http\Requests\API\StoreVenueRequest;
use App\Http\Requests\API\UpdateVenueRequest;
use App\Models\Venue;
use Exception;


/**
 * @group Venue Management
 *
 * APIs for managing venues
 */
class VenueController extends Controller
{
    public function index()
    {
        try {
            $data = Venue::all();

            return response()->json([
                'status' => true,
                'message' => 'List of venues',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching venues: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(StoreVenueRequest $request)
    {
        try {
            $validated = $request->validated();
            $owner = $request->user('owner');

            if (!$owner) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }

            $venue = $owner->venues()->create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Venue created successfully',
                'data' => $venue
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating venue: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(UpdateVenueRequest $request, Venue $venue)
    {
        try {
            $validated = $request->validated();
            $owner = $request->user('owner');

            if (!$owner) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }

            if ($venue->owner_id !== $owner->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Forbidden',
                    'data' => null
                ], 403);
            }

            $venue->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Venue berhasil di update',
                'data' => $venue
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating venue: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy(DestroyVenueRequest $request, Venue $venue)
    {
        try {
            $owner = $request->user('owner');

            if (!$owner) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            if ($venue->owner_id !== $owner->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Forbidden',
                ], 403);
            }

            $venue->delete();

            return response()->json([
                'status' => true,
                'message' => 'Venue berhasil di hapus',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting venue: ' . $e->getMessage(),
            ], 500);
        }
    }
}

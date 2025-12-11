<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\VenueResource;
use App\Models\Field;
use App\Models\Venue;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $categories = Field::select('sport_type')->distinct()->pluck('sport_type');

            $nearestVenues = collect();

            // SKENARIO A: User mengizinkan GPS (Lat & Long tersedia)
            if ($request->has('lat') && $request->has('long')) {
                $userLat = $request->lat;
                $userLong = $request->long;

                $nearestVenues = Venue::with('venuePhoto')
                    ->select('*')
                    ->selectRaw("
                        ( 6371 * acos( cos( radians(?) ) *
                          cos( radians( SUBSTRING_INDEX(gps_coordinate, ',', 1) ) ) *
                          cos( radians( SUBSTRING_INDEX(gps_coordinate, ',', -1) ) - radians(?) ) +
                          sin( radians(?) ) *
                          sin( radians( SUBSTRING_INDEX(gps_coordinate, ',', 1) ) ) )
                        ) AS distance", [$userLat, $userLong, $userLat])
                    ->orderBy('distance')
                    ->limit(6)
                    ->get();
            }
            // SKENARIO B: User memilih Kota dari Dropdown Navbar
            elseif ($request->has('city') && $request->city != null) {
                $nearestVenues = Venue::with('venuePhoto')
                    ->where('city', 'like', '%'.$request->city.'%')
                    ->latest() // Karena tidak ada hitungan jarak, urutkan dari terbaru/populer
                    ->limit(6)
                    ->get();
            }
            // SKENARIO C: User baru buka, belum set apa-apa (Default)
            else {
                $nearestVenues = Venue::with('venuePhoto')->inRandomOrder()->limit(6)->get();
            }

            $recommendations = Venue::with('venuePhoto');

            // Opsional: Jika user pilih kota, rekomendasi juga harus dari kota itu
            if ($request->has('city') && $request->city != null) {
                $recommendations->where('city', 'like', '%'.$request->city.'%');
            }

            $recommendations = $recommendations->inRandomOrder()->limit(6)->get();

            $data = [
                'categories' => $categories,
                'nearest' => VenueResource::collection($nearestVenues),
                'recommendations' => VenueResource::collection($recommendations),
            ];

            return $this->sendSuccessWithData($data, 'Data beranda berhasil diambil.');

        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

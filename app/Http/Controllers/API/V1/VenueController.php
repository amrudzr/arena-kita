<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Traits\ApiResponseTrait;
use Exception;

class VenueController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $data = Venue::with('owner:id,full_name,email')->get();

            return $this->sendSuccessWithData($data, 'Berhasil menampilkan.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

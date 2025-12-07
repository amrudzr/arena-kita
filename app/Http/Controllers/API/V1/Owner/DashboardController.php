<?php

namespace App\Http\Controllers\API\V1\Owner;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\Owner\DashboardService;
use App\Http\Resources\V1\Owner\BookingResource;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function bookings(Request $request)
    {
        try {
            $status = $request->query('status');

            $data = $this->service->getOwnerBookings($status);

            return $this->sendSuccessWithData(BookingResource::collection($data), 'Daftar booking berhasil diambil.');
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function stats()
    {
        try {
            $data = $this->service->getStats();

            return $this->sendSuccessWithData($data, 'Statistik dashboard berhasil diambil.');
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

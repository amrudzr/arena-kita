<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\BookingResource;
use App\Http\Resources\V1\Owner\DashboardStatResource;
use App\Services\Owner\DashboardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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

            return $this->sendSuccessWithData(new DashboardStatResource($data), 'Statistik dashboard berhasil diambil.');
        } catch (\Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

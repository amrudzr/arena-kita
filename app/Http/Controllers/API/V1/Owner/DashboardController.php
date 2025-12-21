<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\BookingResource;
use App\Http\Resources\V1\Owner\DashboardStatResource;
use App\Services\Owner\DashboardService;
use App\Traits\ApiResponseTrait;
use Exception;
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
            $query = $this->service->getOwnerBookingsQuery($request->query('status'));

            $limit = $request->input('limit', 5);

            if ($limit > 100) {
                $limit = 100;
            }

            $data = $query->paginate($limit);

            return $this->sendSuccessWithData(
                BookingResource::collection($data),
                'Daftar booking dashboard berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil daftar booking dashboard.', 500);
        }
    }

    public function stats()
    {
        try {
            $data = $this->service->getStats();

            return $this->sendSuccessWithData(new DashboardStatResource($data), 'Statistik dashboard berhasil diambil.');
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil statistik dashboard.', 500);
        }
    }
}

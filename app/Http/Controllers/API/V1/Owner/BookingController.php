<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\BookingResource;
use App\Models\Booking;
use App\Services\Owner\BookingService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function approve(Booking $booking)
    {
        try {
            $updatedBooking = $this->service->approveBooking($booking);

            return $this->sendSuccessWithData(new BookingResource($updatedBooking), 'Booking berhasil disetujui.');
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function reject(Booking $booking)
    {
        try {
            $updatedBooking = $this->service->rejectBooking($booking);

            return $this->sendSuccessWithData(new BookingResource($updatedBooking), 'Booking telah ditolak.');
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

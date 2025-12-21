<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Owner\BookingResource;
use App\Models\Booking;
use App\Services\Owner\BookingService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->service->getOwnerBookingsQuery($request->query('status'));
            $limit = $request->input('limit', 10);

            if ($limit > 100) {
                $limit = 100;
            }

            $bookings = $query->paginate($limit);

            return $this->sendSuccessWithData(
                BookingResource::collection($bookings),
                'Daftar booking berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil daftar booking.', 500);
        }
    }

    public function approve(Booking $booking)
    {
        try {
            $updatedBooking = $this->service->approveBooking($booking);

            return $this->sendSuccessWithData(new BookingResource($updatedBooking), 'Booking berhasil disetujui.');
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal menyetujui booking.', 500);
        }
    }

    public function reject(Booking $booking)
    {
        try {
            $updatedBooking = $this->service->rejectBooking($booking);

            return $this->sendSuccessWithData(new BookingResource($updatedBooking->load('user', 'pricingScheme.field.venue')), 'Booking telah ditolak.');
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal menolak booking.', 500);
        }
    }
}

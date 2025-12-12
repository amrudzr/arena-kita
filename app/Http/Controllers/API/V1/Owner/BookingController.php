<?php

namespace App\Http\Controllers\API\V1\Owner;

use Exception;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Owner\BookingService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\V1\Owner\BookingResource;

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
            $ownerId = Auth::guard('api_owner')->id();

            // Ambil filter status dari URL parameter jika ada
            $status = $request->query('status');

            $query = Booking::query()
                // 1. Filter Booking milik Owner ini saja
                // (Booking -> Pricing -> Field -> Venue -> Owner)
                ->whereHas('pricingScheme.field.venue', function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId);
                })
                // 2. Load relasi agar data lengkap (N+1 Solution)
                ->with(['user', 'pricingScheme.field.venue']);

            // 3. Terapkan Filter Status (Jika ada)
            if ($status) {
                $query->where('booking_status', $status);
            }

            // Urutkan dari yang mainnya paling dekat (segera)
            $bookings = $query->orderBy('booking_date', 'asc')
                              ->orderBy('start_time', 'asc')
                              ->paginate(10);

            return $this->sendSuccessWithData(
                BookingResource::collection($bookings),
                'Daftar booking berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e);
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
            return $this->sendInternalError($e);
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
            return $this->sendInternalError($e);
        }
    }
}

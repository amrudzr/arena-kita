<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\BookingRequest;
use App\Http\Resources\V1\BookingDetailResource;
use App\Http\Resources\V1\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use ApiResponseTrait;

    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        try {
            $booking = Booking::where('user_id', Auth::guard('api_user')->id())->get();

            return $this->sendSuccessWithData(BookingResource::collection($booking), 'Berhasil menampilkan booking.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function show(Booking $booking)
    {
        try {
            $user = Auth::guard('api_user')->id();

            if ($booking->user_id !== $user) {
                return $this->sendError('Tidak dapat mengakses booking yang bukan milik Anda.', [], 403);
            }

            $detail = $this->bookingService->detailBooking($booking);

            return $this->sendSuccessWithData(new BookingDetailResource($detail), 'Berhasil menampilkan detail booking.', 200);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }

    public function store(BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());

            return $this->sendSuccessWithData($booking, 'Booking berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

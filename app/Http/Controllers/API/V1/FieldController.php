<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\FieldScheduleResource;
use App\Models\Booking;
use App\Models\Field;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    use ApiResponseTrait;

    public function checkAvailability(Request $request, $id)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $date = $request->query('date');

            $field = Field::with(['venue', 'pricingSchemes'])->find($id);

            if (! $field) {
                return $this->sendError('Lapangan tidak ditemukan.', [], 404);
            }

            $bookings = Booking::query()
                ->whereHas('pricingScheme', function ($q) use ($id) {
                    $q->where('field_id', $id);
                })
                ->where('booking_date', $date)
                ->whereIn('booking_status', ['PENDING', 'CONFIRMED', 'COMPLETED'])
                ->orderBy('start_time')
                ->get();

            $data = [
                'field' => $field,
                'bookings' => $bookings,
                'date' => $date,
            ];

            return $this->sendSuccessWithData(
                new FieldScheduleResource($data),
                'Data ketersediaan jadwal berhasil diambil.'
            );

        } catch (Exception $e) {
            return $this->sendInternalError($e);
        }
    }
}

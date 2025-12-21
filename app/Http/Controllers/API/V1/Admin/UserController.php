<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            if ($limit > 100) {
                $limit = 100;
            }

            $users = User::where('role', 'user')
                ->latest()
                ->paginate($limit);

            return $this->sendSuccessWithData(
                UserResource::collection($users),
                'Daftar user penyewa berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil daftar user penyewa.', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with([
                'bookings' => function ($query) {
                    $query->latest()->limit(10)
                        ->with('pricingScheme.field.venue');
                },
            ])
                ->where('role', 'user')
                ->find($id);

            if (! $user) {
                return $this->sendError('User tidak ditemukan.', [], 404);
            }

            return $this->sendSuccessWithData(
                new UserResource($user),
                'Detail user berhasil diambil.'
            );
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal mengambil detail user.', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('role', 'user')->find($id);

            if (! $user) {
                return $this->sendError('User tidak ditemukan.', [], 404);
            }

            $user->delete();

            return $this->sendSuccess('User berhasil dinonaktifkan.');
        } catch (Exception $e) {
            return $this->sendInternalError($e, 'Gagal menonaktifkan user.', 500);
        }
    }
}

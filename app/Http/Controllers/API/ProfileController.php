<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function __construct() {

    }

    public function show(Request $request)
    {
        // Middleware 'auth:api_user' akan memastikan
        // $request->user() berisi data user yang login.
        return $this->sendSuccessWithData(
            $request->user(),
            'Data profil berhasil diambil.'
        );
    }

    /**
     * Memperbarui data profil user yang sedang login.
     * Sesuai F-1.4
     */
    public function update(Request $request) // <-- Nanti akan kita ganti dengan UpdateProfileRequest
    {
        // TODO: Panggil ProfileService untuk validasi dan update data
        // (Akan diimplementasikan di tahap selanjutnya)

        // Placeholder response
        return $this->sendSuccess('Profil berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Profile\UpdateRequest;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        return $this->sendSuccessWithData(
            $request->user(),
            'Data profil berhasil diambil.'
        );
    }

    public function update(UpdateRequest $request)
    {
        
        try {
            $user = $request->user(); 

            $updatedUser = $this->profileService->updateProfile($user, $request->validated());

            return $this->sendSuccessWithData(
                $updatedUser,
                'Profil berhasil diperbarui.'
            );

        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'user_id' => $request->user()->id,
            ];
            return $this->sendInternalError($e, 'Gagal memperbarui profil.', 500, $context);
        }
    }
}

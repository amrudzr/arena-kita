<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\VerifyOtpRequest;
use App\Http\Resources\V1\Owner\OwnerResource;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function registerUser(RegisterRequest $request)
    {
        try {
            $result = $this->authService->registerUser($request->validated());

            return $this->sendSuccessWithData(
                [
                    'user' => $result['user'],
                ],
                'Silahkan cek email Anda untuk verifikasi akun.',
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'request_data' => $request->except('password'),
            ];

            return $this->sendInternalError($e, 'Gagal melakukan registrasi.', 500, $context);
        }
    }

    public function verifyUserEmail(VerifyOtpRequest $request)
    {
        try {
            $result = $this->authService->verifyOtp($request->validated());

            return $this->sendSuccessWithData(
                [
                    'user' => $result['user'],
                    'token' => $result['token'],
                ],
                'Verifikasi email berhasil.',
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'email' => $request->input('email'),
            ];

            return $this->sendInternalError($e, 'Gagal melakukan verifikasi email.', 500, $context);
        }
    }

    public function loginUser(LoginRequest $request)
    {
        try {
            $result = $this->authService->attemptLogin('api_user', $request->validated());

            return $this->sendSuccessWithData([
                'token' => $result['token'],
                'user' => $result['user'],
            ], 'Login berhasil.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'email' => $request->input('email'),
            ];

            return $this->sendInternalError($e, 'Gagal melakukan login.', 500, $context);
        }
    }

    public function loginOwner(LoginRequest $request)
    {
        try {
            $result = $this->authService->attemptLogin('api_owner', $request->validated());

            $user = $result['user'];

            return $this->sendSuccessWithData([
                'token' => $result['token'],
                'user' => new OwnerResource($user),
            ], 'Login Owner berhasil.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
                'email' => $request->input('email'),
            ];

            return $this->sendInternalError($e, 'Gagal melakukan login.', 500, $context);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()?->currentAccessToken()?->delete();

            return $this->sendSuccess('Logout berhasil.');

        } catch (Exception $e) {
            $context = [
                'context' => __METHOD__,
            ];

            return $this->sendInternalError($e, 'Gagal logout.', 500, $context);
        }
    }
}

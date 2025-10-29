<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Add custom API exception rendering
        $this->renderable(function (Throwable $e, $request) {

            // Only format responses for API requests
            if ($request->is('api/*')) {

                // 404 - Model or Route not found
                if ($e instanceof NotFoundHttpException) {
                    return $this->sendNotFound('Sumber daya atau route tidak ditemukan');
                }

                // 422 - Validation Failed (from FormRequest)
                if ($e instanceof ValidationException) {
                    return $this->sendError(
                        'Validasi gagal',
                        $e->errors(),
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }

                // 401 - Unauthenticated
                if ($e instanceof AuthenticationException) {
                    return $this->sendError(
                        'Tidak terautentikasi',
                        [],
                        Response::HTTP_UNAUTHORIZED
                    );
                }

                // 500 - Other Server Errors
                $errorMessage = app()->isProduction() ? 'Terjadi kesalahan pada server' : $e->getMessage();

                // Prepare context for logging
                $context = [
                    'context' => 'GlobalExceptionHandler', // Mark as globally caught
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                ];

                return $this->sendInternalError($e, $errorMessage, 500, $context);
            }
        });
    }
}

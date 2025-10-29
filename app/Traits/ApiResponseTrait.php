<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{
    /**
     * Send a success response without data.
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccess(string $message = 'Sukses', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], $code);
    }

    /**
     * Send a success response with data.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccessWithData($data, string $message = 'Sukses', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Send a formatted error response.
     *
     * @param string $message
     * @param array $errors
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError(string $message = 'Terjadi kesalahan', array $errors = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Send a 404 not found response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendNotFound(string $message = 'Data tidak ditemukan'): JsonResponse
    {
        return $this->sendError($message, [], Response::HTTP_NOT_FOUND);
    }

    /**
     * Send a 500 internal server error response and log the exception.
     *
     * @param \Exception $exception
     * @param string $message
     * @param int $code
     * @param array $context
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendInternalError(
        \Exception $exception, 
        string $message = 'Terjadi kesalahan pada server', 
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $context = [] // Custom context
    ): JsonResponse
    {
        // Prepare basic log data from the exception
        $logData = [
            'file' => $exception->getFile(), // File where the error occurred
            'line' => $exception->getLine(), // Line where the error occurred
        ];

        // Merge $logData with your custom $context
        $fullContext = array_merge($logData, $context);

        // Log the error with the main exception message and full context
        Log::error($exception->getMessage(), $fullContext);
        
        return $this->sendError($message, [], $code);
    }
}
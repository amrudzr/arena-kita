<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/user/register', 'registerUser');
        Route::post('/user/login', 'loginUser');
        Route::post('/owner/login', 'loginOwner');
        Route::post('/logout', 'logout')->middleware(['auth:api_user,api_owner']);
    });
});

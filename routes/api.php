<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FieldController;
use App\Http\Controllers\API\ProfileController;
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

    Route::middleware('auth:api_user')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'show');
            Route::post('/profile', 'update');
        });
    });

    Route::middleware('auth:api_owner')->group(function () {
        Route::controller(FieldController::class)->group(function () {
            Route::post('venues/{venue}/fields', 'store');
            Route::put('fields/{field}', 'update');
            Route::delete('fields/{field}', 'destroy');
        });

    });
});

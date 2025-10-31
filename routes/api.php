<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\V1\VenueController;
use App\Http\Controllers\API\V1\VenuePhotoController;
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

    Route::prefix('venues')->controller(VenueController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{venue}', 'update');
        Route::delete('/{venue}', 'destroy');

        Route::prefix('{venue}')->group(function () {
            Route::prefix('photos')->controller(VenuePhotoController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::put('/{photo}', 'update');
                Route::delete('/{photo}', 'destroy');
            });
        });
    });

    Route::middleware('auth:api_user')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'show');
            Route::post('/profile', 'update');
        });
    });
});

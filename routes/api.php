<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\V1\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\API\V1\Owner\FieldController as OwnerFieldController;
use App\Http\Controllers\API\V1\Owner\VenueController as OwnerVenueController;
use App\Http\Controllers\API\V1\Owner\VenuePhotoController as OwnerVenuePhotoController;
use App\Http\Controllers\API\V1\VenueController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(VenueController::class)->group(function () {
        Route::get('/venues', 'index');
    });

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/user/register', 'registerUser');
        Route::post('/user/login', 'loginUser');
        Route::post('/owner/login', 'loginOwner');
        Route::post('/logout', 'logout')->middleware(['auth:api_user,api_owner']);
    });

    Route::middleware('auth:api_owner')->prefix('owners')->group(function () {
        Route::prefix('venues')->controller(OwnerVenueController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{venue}', 'show');
            Route::put('/{venue}', 'update');
            Route::delete('/{venue}', 'destroy');

            Route::prefix('{venue}')->group(function () {
                Route::prefix('photos')->controller(OwnerVenuePhotoController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::put('/{photo}', 'update');
                    Route::delete('/{photo}', 'destroy');
                });

                Route::prefix('fields')->controller(OwnerFieldController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::get('/{field}', 'show');
                    Route::put('/{field}', 'update');
                    Route::delete('/{field}', 'destroy');
                });
            });
        });
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('venues')->controller(AdminVenueController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{venue}', 'show');
            Route::post('/', 'store');
            Route::put('/{venue}', 'update');
            Route::delete('/{venue}', 'destroy');
        });
    });

    Route::middleware('auth:api_user')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'show');
            Route::post('/profile', 'update');
        });
    });

});

<?php

use App\Http\Controllers\API\V1\VenueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('venues')->controller(VenueController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{venue}', 'update');
        Route::delete('/{venue}', 'destroy');
    });
});

Route::middleware(['auth:owner'])->get('/owner', function (Request $request) {
    return $request->user('owner');
});

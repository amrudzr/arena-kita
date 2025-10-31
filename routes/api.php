<?php

use App\Http\Controllers\FieldController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Field Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('fields', FieldController::class);
});

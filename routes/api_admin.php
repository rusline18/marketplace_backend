<?php

use App\Http\Controllers\Admin\Api\AuthController;
use App\Http\Controllers\Admin\Api\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['message' => 'pong']));

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/listings', [ListingController::class, 'index']);
    Route::post('/listings/{listing}/approve', [ListingController::class, 'approve']);
    Route::post('/listings/{listing}/reject', [ListingController::class, 'reject']);
});

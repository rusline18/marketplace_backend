<?php

use App\Http\Controllers\Admin\Api\AuthController;
use App\Http\Controllers\Admin\Api\ListingController;
use App\Http\Controllers\Admin\Api\OrderController;
use App\Http\Controllers\Admin\Api\PartnerController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['message' => 'pong']));

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/listings', [ListingController::class, 'index']);
    Route::post('/listings/{listing}/approve', [ListingController::class, 'approve']);
    Route::post('/listings/{listing}/reject', [ListingController::class, 'reject']);

    Route::get('/partners', [PartnerController::class, 'index']);
    Route::post('/partners/{partner}/approve', [PartnerController::class, 'approve']);
    Route::post('/partners/{partner}/reject', [PartnerController::class, 'reject']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

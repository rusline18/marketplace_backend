<?php

use App\Http\Controllers\Admin\Api\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['message' => 'pong']));

Route::middleware('auth:admin')->get('/me', fn (Request $request) => $request->user('admin'));

Route::middleware('auth:admin')->group(function () {
    Route::get('/listings', [ListingController::class, 'index']);
    Route::post('/listings/{listing}/approve', [ListingController::class, 'approve']);
    Route::post('/listings/{listing}/reject', [ListingController::class, 'reject']);
});

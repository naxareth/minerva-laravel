<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\PasswordRecoveryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Password Recovery Routes
Route::post('/password-recovery/send-otp', [PasswordRecoveryController::class, 'sendOtp']);
Route::post('/password-recovery/verify-otp', [PasswordRecoveryController::class, 'verifyOtp']);
Route::post('/password-recovery/change-password', [PasswordRecoveryController::class, 'changePassword']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('favorites', FavoritesController::class);
    // Explicitly defining the delete route
    Route::delete('/favorites/{anime_id}', [FavoritesController::class, 'destroy']);
});

// User Route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
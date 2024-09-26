<?php

use App\Http\Controllers\Api\FavoritesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('favorites', FavoritesController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

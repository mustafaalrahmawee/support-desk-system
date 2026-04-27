<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ShowOwnProfileController;
use App\Http\Controllers\Auth\UpdateOwnProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', ShowOwnProfileController::class);
    Route::patch('/me', UpdateOwnProfileController::class);
});

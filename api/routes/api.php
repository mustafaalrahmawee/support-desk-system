<?php

use App\Http\Controllers\Admin\InternalUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Eigenes Profil
    Route::get('/me', [ProfileController::class, 'show']);
    Route::patch('/me', [ProfileController::class, 'update']);

    // Admin: Interne Benutzer
    Route::prefix('admin')->group(function () {
        Route::get('/internal-users', [InternalUserController::class, 'index']);
        Route::post('/internal-users', [InternalUserController::class, 'store']);
        Route::patch('/internal-users/{internal_user}', [InternalUserController::class, 'update']);
        Route::delete('/internal-users/{internal_user}', [InternalUserController::class, 'destroy']);
        Route::get('/roles', fn () => response()->json([
            'data' => \App\Models\Role::all(['id', 'name', 'display_name']),
        ]));
    });
});

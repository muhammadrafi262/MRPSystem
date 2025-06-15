<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UserProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Register
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserProfileController::class, 'show']);
    Route::post('/user', [UserProfileController::class, 'update']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});


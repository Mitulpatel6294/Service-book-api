<?php

use App\Http\Controllers\Api\AdminProviderController;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\ProviderProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/set-password', [PasswordController::class, 'setPassword']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:provider'])
    ->prefix('provider-profile')
    ->group(function () {
        Route::post('/', [ProviderProfileController::class, 'store']);
        Route::patch('/', [ProviderProfileController::class, 'update']);
        Route::get('/', [ProviderProfileController::class, 'show']);
    });

Route::middleware(['auth:sanctum', 'role:customer'])
    ->prefix('customer-profile')
    ->group(function () {
        Route::post('/', [CustomerProfileController::class, 'store']);
        Route::patch('/', [CustomerProfileController::class, 'update']);
        Route::get('/', [CustomerProfileController::class, 'show']);
    });

Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::patch('/admin/provider/{id}/approve', [AdminProviderController::class, 'approve']);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

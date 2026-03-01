<?php

use App\Http\Controllers\Api\AdminProviderController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProviderProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/services', [ServiceController::class, 'index']);
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
Route::middleware(['auth:sanctum', 'role:provider', 'verified.provider'])
    ->prefix('provider/services')
    ->group(function () {
        Route::post('/', [ServiceController::class, 'store']);
        Route::patch('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
        Route::get('/', [ServiceController::class, 'my']);
        Route::patch('/bookings/{id}/quote', [BookingController::class, 'quote']);
    });

Route::middleware(['auth:sanctum', 'role:customer'])
    ->prefix('customer-profile')
    ->group(function () {
        Route::post('/', [CustomerProfileController::class, 'store']);
        Route::patch('/', [CustomerProfileController::class, 'update']);
        Route::get('/', [CustomerProfileController::class, 'show']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::patch('/bookings/{id}/accept', [BookingController::class, 'accept']);
        Route::patch('/bookings/{id}/reject', [BookingController::class, 'reject']);
        Route::get('/bookings/{id}/pay', [PaymentController::class, 'checkout']);
    });


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::patch('/admin/provider/{id}/approve', [AdminProviderController::class, 'approve']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


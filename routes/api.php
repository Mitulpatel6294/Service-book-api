<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[AuthController::class,'register']);
Route::post('/verify-otp',[AuthController::class,'verifyOtp']);
Route::post('/set-password',[PasswordController::class,'setPassword']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\OtpMail;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $auth)
    {
        $auth->register($request->validated());
        return response()->json(['message' => 'OTP sent']);
    }

    public function verifyOtp(VerifyOtpRequest $request, AuthService $auth)
    {
        $auth->verifyOtp($request->validated());
        return response()->json(['message' => 'Email verified']);
    }

    public function login(LoginRequest $request, AuthService $auth)
    {
        return response()->json($auth->login($request->validated()));
    }

      public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
    
    
}   
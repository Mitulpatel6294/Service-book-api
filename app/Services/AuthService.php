<?php

namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function register(array $data)
    {
        $otp = random_int(100000, 999999);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'] ?? 'customer',
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10)
        ]);
        Mail::to($user->email)->send(new OtpMail($otp));

        return $user;
    }
    public function verifyOtp(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {   
            throw ValidationException::withMessages(['email' => 'User not found']);
        }

        if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'OTP expired']);
        }

        if (!Hash::check($data['otp'], $user->otp_hash)) {
            throw ValidationException::withMessages(['otp' => 'Invalid OTP']);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp_hash' => null,
            'otp_expires_at' => null
        ]);

        return true;
    }
    public function setPassword(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->email_verified_at) {
            throw ValidationException::withMessages(['email' => 'Email not verified']);
        }

        if ($user->password) {
            throw ValidationException::withMessages(['password' => 'Password already set']);
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return true;
    }
    
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->email_verified_at || !$user->password) {
            throw ValidationException::withMessages(['email' => 'Account not ready']);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['password' => 'Invalid credentials']);
        }

        return [
            'token' => $user->createToken('api')->plainTextToken,
            'user' => $user
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function setPassword(SetPasswordRequest $request, AuthService $auth)
    {
        $auth->setPassword($request->validated());
        return response()->json(['message' => 'Password set']);
    }
}

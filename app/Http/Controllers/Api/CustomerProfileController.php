<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerProfileRequest;
use App\Services\CustomerProfileService;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function store(CustomerProfileRequest $customerProfileRequest, CustomerProfileService $customerProfileService)
    {
        return response()->json(
            $customerProfileService->create(auth()->id(), $customerProfileRequest->validated(), $customerProfileRequest->file('profile_image'))
        );
    }
    public function update(CustomerProfileRequest $customerProfileRequest, CustomerProfileService $customerProfileService)
    {
        return response()->json(
            $customerProfileService->update(auth()->id(), $customerProfileRequest->validated(), $customerProfileRequest->file('profile_image'))
        );
    }
    public function show(CustomerProfileService $customerProfileService)
    {
        return response()->json($customerProfileService->show(auth()->id()));
    }
}

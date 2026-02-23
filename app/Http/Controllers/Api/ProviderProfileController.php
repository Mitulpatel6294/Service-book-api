<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderProfileRequest;
use App\services\ProviderProfileService;
use Illuminate\Http\Request;

class ProviderProfileController extends Controller
{
    public function store(ProviderProfileRequest $request, ProviderProfileService $service)
    {
        return response()->json(
            $service->create(auth()->id(), $request->validated())
        );
    }

    public function update(ProviderProfileRequest $request, ProviderProfileService $service)
    {
        return response()->json(
            $service->update(auth()->id(), $request->validated())
        );
    }

    public function show(ProviderProfileService $service)
    {
        return response()->json($service->show(auth()->id()));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function store(ServiceRequest $request, ServiceService $service)
    {
        return response()->json(
            $service->create(auth()->id(), $request->validated())
        );
    }

    public function update($id, ServiceRequest $request, ServiceService $service)
    {
        return response()->json(
            $service->update($id, auth()->id(), $request->validated())
        );
    }

    public function destroy($id, ServiceService $service)
    {
        $service->delete($id, auth()->id());
        return response()->json(['message' => 'Deleted']);
    }

    public function my(ServiceService $service)
    {
        return response()->json($service->providerServices(auth()->id()));
    }

    public function index(ServiceService $service)
    {
        return response()->json($service->allActive());
    }
}

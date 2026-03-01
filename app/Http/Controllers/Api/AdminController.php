<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingProviders(AdminService $service)
    {
        return response()->json($service->pendingProviders());
    }

    public function approve($id, AdminService $service)
    {
        return response()->json($service->approveProvider($id));
    }

    public function reject($id, AdminService $service)
    {
        return response()->json($service->rejectProvider($id));
    }

    public function bookings(AdminService $service)
    {
        return response()->json($service->allBookings());
    }

    public function payments(AdminService $service)
    {
        return response()->json($service->payments());
    }

    public function stats(AdminService $service)
    {
        return response()->json($service->dashboardStats());
    }
}

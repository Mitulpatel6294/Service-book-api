<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function pendingProviders()
    {
        return ProviderProfile::where('is_verified', false)
            ->with('user')
            ->paginate(10);
    }

    public function approveProvider($id)
    {
        $profile = ProviderProfile::findOrFail($id);

        if ($profile->is_verified) {
            throw ValidationException::withMessages(['provider' => 'Already approved']);
        }

        $profile->update(['is_verified' => true]);

        return $profile;
    }

    public function rejectProvider($id)
    {
        $profile = ProviderProfile::findOrFail($id);

        $profile->update(['is_verified' => false]);

        return $profile;
    }

    public function allBookings()
    {
        return Booking::with(['customer', 'provider', 'service'])
            ->latest()
            ->paginate(15);
    }

    public function payments()
    {
        return Payment::with('booking')
            ->latest()
            ->paginate(15);
    }

    public function dashboardStats()
    {
        return [
            'total_users' => User::count(),
            'total_providers' => ProviderProfile::count(),
            'total_bookings' => Booking::count(),
            'total_transaction_total' => Payment::where('status', 'paid')->sum('amount')
        ];
    }
}

<?php

namespace App\services;

use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProviderProfileService
{
    /**
     * Create a new class instance.
     */
    public function create($userId, $data)
    {
        if (ProviderProfile::where('user_id', $userId)->exists()) {
            throw ValidationException::withMessages(['profile' => 'Profile already exists']);
        }

        $data['user_id'] = $userId;

        return ProviderProfile::create($data);
    }

    public function update($userId, $data)
    {
        $profile = ProviderProfile::where('user_id', $userId)->firstOrFail();
        $profile->update($data);
        return $profile;
    }

    public function show($userId)
    {
        return ProviderProfile::where('user_id', $userId)->firstOrFail();
    }
    public function getProviderDetails($providerId)
    {
        $provider = User::where('role', 'provider')
            ->with([
                'providerProfile',
                'services',
                'bookings' => function ($q) {
                    $q->where('status', 'completed')
                        ->with('service');
                }
            ])
            ->find($providerId);

        if (!$provider) {
            throw new ModelNotFoundException('Provider not found');
        }

        return [
            'id' => $provider->id,
            'name' => $provider->name,
            'profile' => $provider->providerProfile,
            'services' => $provider->services,
            'completed_bookings_count' => $provider->bookings->count(),
            'completed_bookings' => $provider->bookings
        ];
    }
}

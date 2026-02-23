<?php

namespace App\services;

use App\Models\ProviderProfile;
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
}

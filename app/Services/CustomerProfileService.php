<?php

namespace App\Services;

use App\Models\CustomerProfile;
use Illuminate\Validation\ValidationException;

class CustomerProfileService
{
    /**
     * Create a new class instance.
     */
    public function create($userId, $data, $image = null)
    {
        if (CustomerProfile::where('user_id', $userId)->exists()) {
            throw ValidationException::withMessages(['profile' => 'Profile already exists']);
        }
        if ($image) {
            $data['profile_image'] = $image->store('profiles', 'public');
        }
        $data['user_id'] = $userId;
        return CustomerProfile::create($data);
    }
    public function update($userId, $data, $image = null)
    {
        $profile = CustomerProfile::where('user_id', $userId)->firstOrFail();

        if ($image) {
            $data['profile_image'] = $image->store('profiles', 'public');
        }

        $profile->update($data);

        return $profile;
    }

    public function show($userId)
    {
        return CustomerProfile::where('user_id', $userId)->firstOrFail();
    }
}

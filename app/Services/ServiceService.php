<?php

namespace App\Services;

use App\Models\Service;

class ServiceService
{
    /**
     * Create a new class instance.
     */
    public function create($userId, $data)
    {
        $data['provider_id'] = $userId;
        return Service::create($data);
    }

    public function update($id, $userId, $data)
    {
        $service = Service::where('id', $id)
            ->where('provider_id', $userId)
            ->firstOrFail();

        $service->update($data);
        return $service;
    }

    public function delete($id, $userId)
    {
        $service = Service::where('id', $id)
            ->where('provider_id', $userId)
            ->firstOrFail();

        $service->delete();
        return true;
    }

    public function providerServices($userId)
    {
        return Service::where('provider_id', $userId)->get();
    }

    public function allActive()
    {
        return Service::where('is_active', true)->get();
    }
}

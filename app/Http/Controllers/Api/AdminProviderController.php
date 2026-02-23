<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use Illuminate\Http\Request;

class AdminProviderController extends Controller
{
    public function approve($id)
    {
        // dd($id);
        $profile = ProviderProfile::findOrFail($id);
        $profile->update(['is_verified' => true]);

        return response()->json(['message' => 'Provider approved']);
    }
}

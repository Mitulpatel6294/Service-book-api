<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProviderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         dd($request->user());
        $provider = $request->user()->providerProfile;
       
        if (!$provider || !$provider->is_verified) {
            return response()->json(['message' => 'Provider not verified'], 403);
        }

        return $next($request);
    }
}

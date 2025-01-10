<?php 
// app/Http/Middleware/PreventBackHistory.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the user is authenticated with the web guard
        if (Auth::guard('web')->check()) {
            Cache::store('superadmin_cache')->forget('superadmin_session');
            // Add headers to prevent back navigation
            $response->headers->add([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } 

        // Check if the user is authenticated with the masteradmins guard
        if (Auth::guard('masteradmins')->check()) {
            Cache::store('masteradmin_cache')->forget('masteradmin_session');
            // Add headers to prevent back navigation
            $response->headers->add([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return $response;
    }
}

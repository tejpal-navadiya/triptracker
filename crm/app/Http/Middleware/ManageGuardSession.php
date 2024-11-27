<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ManageGuardSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard)
    {
        // Check if the user is authenticated with the specified guard
        if (Auth::guard($guard)->check()) {
            // Optionally, set a specific session cookie for the guard
            // This can be useful if you need to handle cookies differently based on the guard
            if ($guard === 'web') {
                // Example for web guard
                $cookieName = Config::get('session.cookie');
            } elseif ($guard === 'masteradmins') {
                // Example for masteradmins guard
                $cookieName = Config::get('session.masteradmins_cookie');
            } else {
                $cookieName = Config::get('session.cookie');
            }

            // Set the session cookie name
            $request->session()->setId($request->cookie($cookieName));
        }

        return $next($request);
    }
}

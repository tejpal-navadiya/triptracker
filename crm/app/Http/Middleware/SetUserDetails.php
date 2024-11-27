<?php
// app/Http/Middleware/SetUserDetails.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetUserDetails
{
    public function handle(Request $request, Closure $next,  $guard = 'masteradmins')
    {
        if (Auth::guard('masteradmins')->check()) {
            $user = Auth::guard('masteradmins')->user();

            // Retrieve user details from the session
            $userDetails = session('user_details');
        
            if ($userDetails) {
                // Set the user details in the guard
                Auth::guard('masteradmins')->setUser($userDetails);
            } else {
                Auth::guard('masteradmins')->logout();
                return redirect()->route('masteradmin.login')->withErrors(['user' => 'Invalid session data']);
            }
        }

        return $next($request);
    }
}

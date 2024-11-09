<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetUserFolder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('masteradmins')->check()) {
            // Get the authenticated user
            $user = Auth::guard('masteradmins')->user();
            // dd($user);
            // Construct the $userFolder path
            $userFolder ='masteradmin/' . $user->buss_unique_id . '_' . $user->user_first_name;
            // dd($userFolder);
            // Share the variable globally with all views
            session(['userFolder' => $userFolder]);
            view()->share('userFolder', $userFolder);
        }

        return $next($request);
    }
}

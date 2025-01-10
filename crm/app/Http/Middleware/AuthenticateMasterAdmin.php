<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateMasterAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    // public function handle(Request $request, Closure $next, $guard = 'masteradmins')
    // {
    //     if (!Auth::guard($guard)->check()) {
    //         return redirect($this->redirectTo($request));
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, $guard = 'masteradmins')
{
    // Check if the user is authenticated using the specified guard
    if (!Auth::guard($guard)->check()) {
        // If the request expects JSON (AJAX request), return a 401 response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 200);
        }

        // If the request is not an AJAX request, proceed with the usual redirect
        return redirect($this->redirectTo($request));
    }

    return $next($request);
}

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('masteradmin.login');
    }
}


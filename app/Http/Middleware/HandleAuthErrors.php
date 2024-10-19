<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class HandleAuthErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'No token provided.'
            ], 401);
        }

        try {
            $user = Auth::guard('api')->authenticate($token);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Your token is either invalid or expired.'
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Your token is either invalid or expired.'
            ], 401);
        }

        return $next($request);
    }
}

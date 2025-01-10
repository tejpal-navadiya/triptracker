<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class CustomCsrfMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for CSRF token
        if ($request->method() == 'POST' || $request->method() == 'PUT' || $request->method() == 'DELETE') {
            $token = $request->header('X-CSRF-TOKEN') ?? $request->input('_token');
            
            // If token does not exist or mismatch, throw validation exception
            if (!$token || $token !== Session::token()) {
                // You can log the invalid CSRF attempt here for debugging
                Log::warning('CSRF Token mismatch or missing for request from ' . $request->ip());
                
                // You can return a custom error response, or handle it in another way
                throw new ValidationException('CSRF token mismatch');
            }
        }

        return $next($request);
    }
}

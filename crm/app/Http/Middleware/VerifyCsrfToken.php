<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;
use Closure;
use Illuminate\Http\Request;


class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'data/*',
        'login',
        'logout',
        'agency/follow_up_trips_details',
        'fetch-users'
    ];

    // public function handle($request, \Closure $next)
    // {
    //     if ($request->ajax()) {
    //         $response = $next($request);

    //         if ($response->getStatusCode() == 401) {
    //             return response()->json(['error' => 'CSRF token expired. Please reload the page.'], 401);
    //         }

    //         return $response;
    //     }

    //     return parent::handle($request, $next);
    // }

    public function handle(Request $request, Closure $next)
    {
        // Check if the request is a safe method (GET, HEAD, OPTIONS)
        if ($this->isReading($request)) {
            return $next($request);
        }

        // Check if CSRF token matches
        if (!$this->tokensMatch($request)) {
            // Log the CSRF mismatch for debugging purposes
            \Log::error('CSRF token mismatch for user ID: ' . auth()->id() . ' from IP: ' . $request->ip());

            // Redirect the user with an error message
            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        }

        // Continue processing the request
        return $next($request);
    }

    /**
     * Check if the CSRF token matches the session token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch(Request $request)
    {
        // Get the CSRF token from the request (either form data or header)
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        // Compare it with the session token
        return is_string($token) && hash_equals($token, $request->session()->token());
    }

    /**
     * Determine if the request is a "safe" request (such as GET, HEAD).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading(Request $request)
    {
        return $request->isMethod('get') || $request->isMethod('head') || $request->isMethod('options');
    }
}

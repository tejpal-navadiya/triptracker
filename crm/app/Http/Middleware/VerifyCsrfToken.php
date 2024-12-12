<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
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
}

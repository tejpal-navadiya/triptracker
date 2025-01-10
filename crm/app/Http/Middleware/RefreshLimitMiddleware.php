<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RefreshLimitMiddleware
{
    protected $refreshLimit = 3; // Set the limit to the desired number of refreshes
    protected $timeFrame = 60; // Time frame in seconds

    public function handle($request, Closure $next)
    {
        $refreshCount = session()->get('refresh_count', 0);
        $lastRefreshTime = session()->get('last_refresh_time', now());

        if (now()->diffInSeconds($lastRefreshTime) <= $this->timeFrame) {
            $refreshCount++;
        } else {
            $refreshCount = 1; // Reset count if time frame is exceeded
        }

        session()->put('refresh_count', $refreshCount);
        session()->put('last_refresh_time', now());

        if ($refreshCount > $this->refreshLimit) {
            Auth::logout();
            session()->flush();
            return redirect('/login')->withErrors(['message' => 'You have been logged out due to excessive refreshing.']);
        }

        return $next($request);
    }
}

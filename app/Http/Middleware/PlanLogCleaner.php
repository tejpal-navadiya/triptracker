<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\LogActivity; // Ensure correct import

class PlanLogCleaner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        try {
            LogActivity::deleteOldLogs();
        } catch (\Throwable $th) {
            // Log or handle the exception
            \Log::error("Failed to delete old logs: " . $th->getMessage());
        }

        return $next($request);
    }
}

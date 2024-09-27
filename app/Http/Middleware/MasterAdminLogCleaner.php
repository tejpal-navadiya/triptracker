<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\MasterLogActivity;
use Illuminate\Support\Facades\Auth;

class MasterAdminLogCleaner
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            MasterLogActivity::deleteOldLogs();
        } catch (\Throwable $th) {
            \Log::error("Failed to delete old logs for masteradmins: " . $th->getMessage());
        }

        return $next($request);
    }
}
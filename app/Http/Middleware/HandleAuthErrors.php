<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\MasterUserDetails;

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
        $uniqueId = $request->header('X-UniqueId');
 
         if (!$token) {
             return response()->json([
                 'error' => 'Unauthenticated',
                 'message' => 'No token provided.'
             ], 401);
         }

         if (!$uniqueId) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'No uniqueId provided.'
            ], 401);
        }
         $userModel = new MasterUserDetails();
     
         $userModel->setTableForUniqueId( $uniqueId);
         $userDetailRecord = $userModel->where(['api_token'=> $token])->first();

         if (!$userDetailRecord) {
             return response()->json([
                 'error' => 'Unauthenticated',
                 'message' => 'Your token is either invalid or expired.'
             ], 401);
         }

         $request->attributes->set('authenticated_user', $userDetailRecord);
         
         return $next($request);
     }
}

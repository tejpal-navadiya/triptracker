<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\MasterUserDetails;
use App\Models\MasterUser;


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
                 'success' => false,
                 'message' => 'No token provided.'
             ], 500);
         }

         if (!$uniqueId) {
            return response()->json([
                'success' =>  false,
                'message' => 'No uniqueId provided.'
            ], 500);
        }

        $user = MasterUser::where('buss_unique_id', $uniqueId)->first();
        // dd($user);
          if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid X-UniqueId header value. No matching user found.',
            ], 500);  // 404 Not Found status
        }
       
        $userModel = new MasterUserDetails();
        
         $userModel->setTableForUniqueId( $uniqueId);
         $userDetailRecord = $userModel->where(['api_token'=> $token])->first();

         if (!$userDetailRecord) {
             return response()->json([
                 'success' =>  false,
                 'message' => 'Your token is either invalid or expired.'
             ], 500);
         }

         $request->attributes->set('authenticated_user', $userDetailRecord);
         
         return $next($request);
     }
}

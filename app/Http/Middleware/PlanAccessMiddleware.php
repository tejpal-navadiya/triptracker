<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUserDetails;
use Illuminate\Support\Facades\View;
use App\Models\UserRole;
use App\Models\MasterUserAccess;

class PlanAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = 'masteradmins';
    
        $userDetailsFromSession = session('user_details');
        // dd($userDetailsFromSession);
        if ($userDetailsFromSession) {
            if(empty($userDetailsFromSession->role_id)){
                if (Auth::guard($guard)->check()) {
                    $user = Auth::guard($guard)->user();
                    
                    if ($user->id) {
                        $this->setUserAccessById($user);
                    } else {
                        View::share('access', []);
                    }
                } else {
                    View::share('access', []);
                }
            }else{
                $this->handleUserDetailsFromSession($guard, $userDetailsFromSession);
            }
            
        } 
          
    
        return $next($request);
    }
    
    private function handleUserDetailsFromSession($guard, $userDetailsFromSession)
    {
        $userDetailsModel = new MasterUserDetails();
        $userDetailsModel->setTableForUniqueId($userDetailsFromSession['user_id']);
        $user = $userDetailsModel->where('users_id', $userDetailsFromSession['users_id'])->first();
    
        if ($user) {
            Auth::guard($guard)->login($user);
    
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $userDetails = new MasterUserDetails();
                $userDetails->setTableForUniqueId($user->user_id);
                $existingUser = $userDetails->where('users_id', $user->users_id)->first();
    
                if ($existingUser) {
                    $userRole = new UserRole();
                    $userRole->setTable($user->user_id . '_tc_users_role');
                    $role = $userRole->where('role_id', $existingUser->role_id)->first();
    
                    if ($role) {
                        $userRoleAccess = new MasterUserAccess();
                        $userRoleAccess->setTable($user->user_id . '_tc_master_user_access');
                        $userAccess = $userRoleAccess->where('role_id', $role->role_id)->get();
    
                        if ($userAccess) {
                            $access = $userAccess->pluck('is_access', 'mname')->toArray();
                            View::share('access', $access);
                        } else {
                            View::share('access', []);
                        }
                    } else {
                        View::share('access', []);
                    }
                } else {
                    View::share('access', []);
                }
            } else {
                View::share('access', []);
            }
        } else {
            View::share('access', []);
        }
    }
    
    private function setUserAccessById($user)
    {
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);
        $existingUser = $userDetails->where('id', $user->id)->first();
    
        if ($existingUser) {
            $masterUser = $existingUser->masterUser;
    
            if ($masterUser) {
                $access = $masterUser->userAccess->pluck('is_access', 'mname')->toArray();
                View::share('access', $access);
            } else {
                View::share('access', []);
            }
        } else {
            View::share('access', []);
        }
    }
    

}
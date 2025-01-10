<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::guard('masteradmins')->user();
       //dD($user);
        $masterUser = $user->masterUser()->first();
        // dd($masterUser);
        $currentDate = Carbon::now();
        $sevenDaysBeforeExpiry = $currentDate->copy()->addDays(7); 

        $plan = $masterUser->sp_id;
        
        $plan_details = $user->checkplan()->first();
        //dd($plan_details); 
         $spMonthAmount = $plan_details->sp_month_amount ?? '0'; 
         $myAmount = $plan_details->sp_year_amount ?? '0'; 
         if ($spMonthAmount == 0 && $myAmount == 0) { return $next($request); }
        
        if ($user->role_id == 0) {
            // dd($user->role_id);
            if ($masterUser->sp_expiry_date < $currentDate && $user->end_date < $currentDate) {
                // dd('exp');
                // Plan has expired (both sp_expiry_date and user end_date are in the past)
              
                session()->flash('showModal', 'Your subscription plan has expired. Please renew your plan to continue accessing your account.');
                session()->reflash();
            } elseif ($masterUser->sp_expiry_date > $currentDate && $masterUser->sp_expiry_date < $sevenDaysBeforeExpiry) {
                // The plan is within 7 days before expiry
                // dd('show');
                session()->flash('beforshowModal', 'Your plan is expiring soon. Please renew your plan.');
            } else {
                // Plan is active (expired date is in the future)
                // if (!session()->has('plan_befor_configured')) {

                //     session()->flash('beforshowModal', "Your subscription is active. Thank you for renewing your plan.");
                //     session(['plan_befor_configured' => true]);
                // }
                // session()->flash('beforshowModal', 'Your subscription is active. Thank you for renewing your plan.');
                //session()->reflash();
            }
        }
        
        if($user->role_id != 0){
            if ($user->end_date < now()) {
                session()->flash('showModal', 'Your subscription plan is inactive. Please contact the administrator to complete the payment process and activate your subscription for access to the admin panel.');
            }
            else if ($masterUser->sp_expiry_date < now()) {
                session()->flash('showModal', "The main administrator's subscription plan has expired. Please contact the administrator to renew the plan and restore access.");
            }elseif ($user->end_date > $currentDate && $user->end_date < $sevenDaysBeforeExpiry) {
                // The plan is within 7 days before expiry
               // session()->flash('beforshowModal', 'Your plan is expiring soon. Please renew your plan.');
               // session()->reflash();
            } 
        }   
        return $next($request);
    }
}

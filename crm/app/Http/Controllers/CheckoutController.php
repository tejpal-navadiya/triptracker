<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Mail;
use App\Models\Plan;
use Carbon\Carbon;
use App\Models\MasterUserDetails;


class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    // public function __invoke(Request $request, $plan="prod_RIgizvRN5IDrhc")
    // {

        
    //     //dd('hii');
    //     //
    //     return $request->user()
    //     ->newSubscription('prod_RIgizvRN5IDrhc', $plan)
    //     ->checkout([
    //         'success_url' => route('masteradmin.home'),
    //         'cancel_url' => route('masteradmin.login'),
    //     ]);
    // }

    public function stripe(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();

        $stripePriceId = 'price_1QQ5LQCLWbq1l7svZaXNGW0J';
     
        $quantity = 1;
     
        return $user->checkout([$stripePriceId => $quantity], [
            'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
            'mode' => 'subscription',
        ]);
        
      
    
       
    }


    public function success(Request $request)
    {

        $userId = $request->query('user_id');
        $email = $request->query('email');
        $stripe_id = $request->query('stripe_id');
        $plan_id = $request->query('plan_id');
        $period = $request->query('period');

        $loginUrl = route('masteradmin.login');

            

        try {
            Mail::to($email)->send(new UserRegistered($userId, $loginUrl, $email));

            $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
            $price_stripe_id = $plan->stripe_id ?? '';
    
            // Calculate the expiration date
            $startDate = Carbon::now();
            $startDateFormatted = $startDate->toDateString(); 
            if($period == 'monthly'){
                $months = 1;
            }else if($period == 'yearly'){
                $months = 12;
            }else{
                $months = 6;
            }
            $expirationDate = $startDate->addMonths($months);
          
            $expiryDate = $expirationDate->toDateString();
    
            // Update user details in the database
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId(strtolower($userId));
            $user_data = $userDetails->orderBy('created_at', 'desc')->first();
    
            if ($user_data) {
                // Update the user details
                $user_data->stripe_status = 'active'; // Assuming 'active' is the status after successful payment
                $user_data->plan_id = $plan_id; // Set the plan ID
                $user_data->stripe_id = $stripe_id;
                $user_data->start_date = $startDateFormatted; // Set the start date
                $user_data->end_date = $expiryDate; // Set the end date
                $user_data->save(); // Save the changes
            }
    

            return back()->with(['link-success' => __('messages.masteradmin.register.link_send_success')]);
        } catch (\Exception $e) {
            return back()->with(['link-error' => __('messages.masteradmin.register.link_send_error')]);
        }

            
            
            return "Payment is sucessfully.";
       
   
    }

    public function cancel()
    {
        return "Payment is canceled.";
    }
    
}

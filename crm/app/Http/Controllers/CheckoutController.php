<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Mail;
use App\Models\Plan;
use Carbon\Carbon;
use App\Models\MasterUserDetails;
use App\Notifications\UsersDetails;
use App\Models\UserCertification;
use App\Models\MasterUser;

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
        $stripe_id = $request->query('stripe_id'); // Subscription ID
        $plan_id = $request->query('plan_id');
        $period = $request->query('period');
        $latestInvoiceId = $request->query('latestInvoiceId'); // Invoice ID, if passed
    
        $loginUrl = route('masteradmin.login');
    
        try {
            $invoiceUrl = "";
    
            // // Set Stripe API Key
            // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
            // // If `latestInvoiceId` is provided, fetch the invoice directly
            // if ($latestInvoiceId) {
            //     // Fetch invoice by ID
            //     $invoice = \Stripe\Invoice::retrieve($latestInvoiceId);
    
            //     // Check if the invoice is finalized and has a hosted URL
            //     if ($invoice && $invoice->status == 'paid' && isset($invoice->hosted_invoice_url)) {
            //         $invoiceUrl = $invoice->hosted_invoice_url;
            //     }
            // } else {
            //     // Fallback: Retrieve the latest invoice from the subscription
            //     $subscription = \Stripe\Subscription::retrieve($stripe_id);
    
            //     if (isset($subscription->latest_invoice)) {
            //         // Retrieve the latest invoice using the latest_invoice ID
            //         $invoice = \Stripe\Invoice::retrieve($subscription->latest_invoice);
    
            //         // Check if the invoice is finalized and has a hosted URL
            //         if ($invoice && $invoice->status == 'paid' && isset($invoice->hosted_invoice_url)) {
            //             $invoiceUrl = $invoice->hosted_invoice_url;
            //         }
            //     }
            // }
    
            // If an invoice URL is found, send the email
            // if ($invoiceUrl) {
                // Send email with invoice URL
                Mail::to($email)->send(new UserRegistered($userId, $loginUrl, $email, $invoiceUrl));
            // } else {
            //     // Handle case where no invoice URL is found
            //     \Log::warning('Invoice URL not found or invoice is not finalized');
            //     return back()->with(['link-error' => 'Invoice URL is not available.']);
            // }
    
            // Retrieve the plan
            $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
            $price_stripe_id = $plan->stripe_id ?? '';
    
            // Calculate expiration date
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
    
            // Update user details
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId(strtolower($userId));
            $user_data = $userDetails->orderBy('created_at', 'desc')->first();

            $admin = MasterUser::where(['id' => $user_data->id, 'buss_unique_id' => $user_data->user_id])->firstOrFail();
            if( $admin){
                // $admin->update([
                //     'sp_id' => $plan_id,
                //     'plan_type' => $period,
                //     'start_date' => $startDateFormatted,
                //     'sp_expiry_date' => $startDateFormatted,
                //     'isActive' => 1,
                //     'stripe_id' => $stripe_id,
                //     'subscription_status' => 'active',
                // ]);

                $admin->sp_id = $plan_id; 
                $admin->plan_type = $period; 
                $admin->start_date = $startDateFormatted; 
                $admin->sp_expiry_date = $expiryDate; 
                $admin->isActive = 1; 
                $admin->stripe_id = $stripe_id; 
                $admin->subscription_status = 'active'; 
                $admin->user_status = 1;
            
                // Save the changes to the database
                $admin->save();
            }
    
            if ($user_data) {
                $user_data->stripe_status = 'active';
                $user_data->plan_id = $plan_id;
                $user_data->stripe_id = $stripe_id;
                $user_data->start_date = $startDateFormatted;
                $user_data->end_date = $expiryDate;
                $user_data->plan_type = $period;
                $user_data->users_status = 1;
                $user_data->save();
                
            }
    
            // Redirect to thank you page
            return redirect()->route('thank-you');
    
        } catch (\Exception $e) {
            // Log any error for debugging
            \Log::error('Error processing payment success:', ['error' => $e->getMessage()]);
            return back()->with(['link-error' => 'Something went wrong. Please try again later.']);
        }
    }
    


    public function cancel()
    {
        return "Payment is canceled.";
    }


    
    public function agencySuccess(Request $request)
    {
        $userId = $request->query('user_id');
        $email = $request->query('email');
        $stripe_id = $request->query('stripe_id'); // Subscription ID
        $plan_id = $request->query('plan_id');
        $period = $request->query('period');
        $latestInvoiceId = $request->query('latestInvoiceId'); // Invoice ID, if passed
        $users_id = $request->query('users_id'); 
    
        $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $email, 'user_id' => $userId]);

    
        try {
            $invoiceUrl = "";
    
            // // Set Stripe API Key
            // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
            // // If `latestInvoiceId` is provided, fetch the invoice directly
            // if ($latestInvoiceId) {
            //     // Fetch invoice by ID
            //     $invoice = \Stripe\Invoice::retrieve($latestInvoiceId);
    
            //     // Check if the invoice is finalized and has a hosted URL
            //     if ($invoice && $invoice->status == 'paid' && isset($invoice->hosted_invoice_url)) {
            //         $invoiceUrl = $invoice->hosted_invoice_url;
            //     }
            // } else {
            //     // Fallback: Retrieve the latest invoice from the subscription
            //     $subscription = \Stripe\Subscription::retrieve($stripe_id);
    
            //     if (isset($subscription->latest_invoice)) {
            //         // Retrieve the latest invoice using the latest_invoice ID
            //         $invoice = \Stripe\Invoice::retrieve($subscription->latest_invoice);
    
            //         // Check if the invoice is finalized and has a hosted URL
            //         if ($invoice && $invoice->status == 'paid' && isset($invoice->hosted_invoice_url)) {
            //             $invoiceUrl = $invoice->hosted_invoice_url;
            //         }
            //     }
            // }
    
            // If an invoice URL is found, send the email
            // if ($invoiceUrl) {
                // Send email with invoice URL
               
              // } else {
            //     // Handle case where no invoice URL is found
            //     \Log::warning('Invoice URL not found or invoice is not finalized');
            //     return back()->with(['link-error' => 'Invoice URL is not available.']);
            // }
    
            // Retrieve the plan
            $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
            $price_stripe_id = $plan->stripe_id ?? '';
    
            // Calculate expiration date
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
    
            // Update user details
          
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId(strtolower($userId));
            $user_data = $userDetails->where('users_id', $users_id)->first();
    
            if ($user_data) {
                $user_data->stripe_status = 'active';
                $user_data->plan_id = $plan_id;
                $user_data->stripe_id = $stripe_id;
                $user_data->start_date = $startDateFormatted;
                $user_data->end_date = $expiryDate;
                $user_data->plan_type = $period;
                $user_data->users_status = 1;
                $user_data->save();
            }
    
            try {
                Mail::to($email)->send(new UsersDetails($userId, $loginUrl, $email));
                session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
            } catch (\Exception $e) {
                session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
            }     
            // Redirect to thank you page
            return redirect()->route('agency.index')->with('success', 'Agecy User entry created successfully.');

    
        } catch (\Exception $e) {
            // Log any error for debugging
            \Log::error('Error processing payment success:', ['error' => $e->getMessage()]);
            return back()->with(['link-error' => 'Something went wrong. Please try again later.']);
        }
    }
    


    public function agencyCancel()
    {
        return "Payment is canceled.";
    }
    
}

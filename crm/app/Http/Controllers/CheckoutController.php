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
use Illuminate\Support\Facades\Hash;
use App\Models\AgencyPhones;
use Stripe\Stripe;
use App\Models\Subscription;

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


    
    public function agencySuccess1(Request $request)
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
    
    public function agencySuccess2(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id;
        
        // Retrieve the required parameters
        $userId = $request->query('user_id');
        $email = $request->query('email');
        $stripe_id = $request->query('stripe_id'); // Subscription ID
        $plan_id = $request->query('plan_id');
        $period = $request->query('period');
        $latestInvoiceId = $request->query('latestInvoiceId'); // Invoice ID, if passed
        $users_id = $request->query('users_id'); 
        $validated = $request->query('validated'); 
        $items = $request->query('items'); 
        
        // Generate the link for password change
        $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $email, 'user_id' => $userId]);
    
        try {
            // Create a new agency record
            $agency = new MasterUserDetails();
            $agency->setTableForUniqueId($userId); // Set the correct table based on user
            $tableName = $agency->getTable();
            
            // Assign the users_id and dynamicId
            $agency->users_id = $users_id;
            $agency->id = $dynamicId;
    
            // Check if email already exists
          
    
            // Fill in the rest of the validated data
            $agency->user_id = $user->user_id;   
            $agency->user_agency_numbers = $validated['user_agency_numbers'] ?? '';
            $agency->user_work_email = $validated['user_work_email'] ?? '';
            $agency->user_dob = $validated['user_dob'] ?? '';
            $agency->user_emergency_contact_person = $validated['user_emergency_contact_person'] ?? '';  // Use an empty string if the key doesn't exist
            $agency->user_emergency_phone_number = $validated['user_emergency_phone_number'] ?? '';
            $agency->user_emergency_email = $validated['user_emergency_email'] ?? '';
            $agency->users_country = $validated['users_country'] ?? '';
            $agency->users_state = $validated['users_state'] ?? '';
            $agency->users_city = $validated['users_city'] ?? '';
            $agency->role_id = $validated['role_id'] ?? '';
            $agency->users_first_name = $validated['users_first_name'] ?? '';
            $agency->users_last_name = $validated['users_last_name'] ?? '';
            $agency->users_email  = $validated['users_email'] ?? '';
            $agency->users_address = $validated['users_address'] ?? '';
            $agency->users_zip = $validated['users_zip'] ?? '0';
            $agency->users_bio = '';  
            $agency->users_status = 1;  
            $agency->users_password = Hash::make($validated['users_password']) ?? ''; // Hash the password
            $agency->save();
    
            // Handle agency phone items if they are provided
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {
                    if (empty($item) || !is_array($item)) {
                        continue;
                    }
    
                    // Create a new phone record for the agency
                    $travelerItem = new AgencyPhones();
                    $tableName = $travelerItem->getTable();
                    $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
    
                    // Assign the generated unique ID
                    $travelerItem->age_id = $agency->users_id;
                    $travelerItem->id = $dynamicId;
                    $travelerItem->age_user_phone_id = $ageid;
    
                    // Fill in the phone item data and save
                    $travelerItem->fill($item);
                    $travelerItem->save();
                }
            }
    
            // Calculate expiration date for the user's subscription
            $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
            $price_stripe_id = $plan->stripe_id ?? '';
    
            // Set subscription period (monthly, yearly, or custom)
            $startDate = Carbon::now();
            $startDateFormatted = $startDate->toDateString();
    
            if ($period == 'monthly') {
                $months = 1;
            } else if ($period == 'yearly') {
                $months = 12;
            } else {
                $months = 6; // Default to 6 months
            }
    
            $expirationDate = $startDate->addMonths($months);
            $expiryDate = $expirationDate->toDateString();
    
            // Update user details with the new subscription information
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
    
            // Send the email with login URL
            try {
                Mail::to($email)->send(new UsersDetails($userId, $loginUrl, $email));
                session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
            } catch (\Exception $e) {
                session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
            }
    
            // Redirect to agency index page with success message
            return redirect()->route('agency.index')->with('success', 'Agency User entry created successfully.');
    
        } catch (\Exception $e) {
            // Log any error for debugging
            \Log::error('Error processing payment success:', ['error' => $e->getMessage()]);
            return back()->with(['link-error' => 'Something went wrong. Please try again later.']);
        }
    }

    public function agencySuccess3(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id;
        
        // Retrieve the required parameters
        $userId = $request->query('user_id');
        $email = $request->query('email');
        $stripe_id = $request->query('stripe_id'); // Subscription ID
        $plan_id = $request->query('plan_id');
        $period = $request->query('period');
        $latestInvoiceId = $request->query('latestInvoiceId'); // Invoice ID, if passed
        $users_id = $request->query('users_id'); 
        $validated = $request->query('validated'); 
        $items = $request->query('items'); 
        
        // Generate the link for password change
        $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $email, 'user_id' => $userId]);
    
        try {
            // Create a new agency record
            $agency = new MasterUserDetails();
            $agency->setTableForUniqueId($userId); // Set the correct table based on user
            $tableName = $agency->getTable();
            
            // Assign the users_id and dynamicId
            $agency->users_id = $users_id;
            $agency->id = $dynamicId;
    
            // Check if email already exists
          
    
            // Fill in the rest of the validated data
            $agency->user_id = $user->user_id;   
            $agency->user_agency_numbers = $validated['user_agency_numbers'] ?? '';
            $agency->user_work_email = $validated['user_work_email'] ?? '';
            $agency->user_dob = $validated['user_dob'] ?? '';
            $agency->user_emergency_contact_person = $validated['user_emergency_contact_person'] ?? '';  // Use an empty string if the key doesn't exist
            $agency->user_emergency_phone_number = $validated['user_emergency_phone_number'] ?? '';
            $agency->user_emergency_email = $validated['user_emergency_email'] ?? '';
            $agency->users_country = $validated['users_country'] ?? '';
            $agency->users_state = $validated['users_state'] ?? '';
            $agency->users_city = $validated['users_city'] ?? '';
            $agency->role_id = $validated['role_id'] ?? '';
            $agency->users_first_name = $validated['users_first_name'] ?? '';
            $agency->users_last_name = $validated['users_last_name'] ?? '';
            $agency->users_email  = $validated['users_email'] ?? '';
            $agency->users_address = $validated['users_address'] ?? '';
            $agency->users_zip = $validated['users_zip'] ?? '0';
            $agency->users_bio = '';  
            $agency->users_status = 1;  
            $agency->users_password = Hash::make($validated['users_password']) ?? ''; // Hash the password
            $agency->save();
    
            // Handle agency phone items if they are provided
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {
                    if (empty($item) || !is_array($item)) {
                        continue;
                    }
    
                    // Create a new phone record for the agency
                    $travelerItem = new AgencyPhones();
                    $tableName = $travelerItem->getTable();
                    $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
    
                    // Assign the generated unique ID
                    $travelerItem->age_id = $agency->users_id;
                    $travelerItem->id = $dynamicId;
                    $travelerItem->age_user_phone_id = $ageid;
    
                    // Fill in the phone item data and save
                    $travelerItem->fill($item);
                    $travelerItem->save();
                }
            }
    
            // Calculate expiration date for the user's subscription
            $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
            $price_stripe_id = $plan->stripe_id ?? '';
    
            // Set subscription period (monthly, yearly, or custom)
            $startDate = Carbon::now();
            $startDateFormatted = $startDate->toDateString();
    
            if ($period == 'monthly') {
                $months = 1;
            } else if ($period == 'yearly') {
                $months = 12;
            } else {
                $months = 6; // Default to 6 months
            }
    
            $expirationDate = $startDate->addMonths($months);
            $expiryDate = $expirationDate->toDateString();
    
            // Update user details with the new subscription information
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
    
            // Send the email with login URL
            try {
                Mail::to($email)->send(new UsersDetails($userId, $loginUrl, $email));
                session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
            } catch (\Exception $e) {
                session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
            }
    
            // Redirect to agency index page with success message
            return redirect()->route('agency.index')->with('success', 'Agency User entry created successfully.');
    
        } catch (\Exception $e) {
            // Log any error for debugging
            \Log::error('Error processing payment success:', ['error' => $e->getMessage()]);
            return back()->with(['link-error' => 'Something went wrong. Please try again later.']);
        }
    }

    public function agencySuccess(Request $request)
{
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->id;

    // Retrieve parameters from the request
    $userId = $request->query('user_id');
    $email = $request->query('email');
    $stripe_id = $request->query('stripe_id');
    $plan_id = $request->query('plan_id');
    $period = $request->query('period');
    $latestInvoiceId = $request->query('latestInvoiceId');
    $users_id = $request->query('users_id');
    $validated = $request->query('validated');
    $items = $request->query('items');
    $sessionId = $request->query('session_id');

    Stripe::setApiKey(env('STRIPE_SECRET'));

    // Generate the login URL for password change
    $loginUrl = route('masteradmin.userdetail.changePassword', [
        'email' => $email,
        'user_id' => $userId,
    ]);

    try {

        // Fetch the session details from Stripe
        $session = \Stripe\Checkout\Session::retrieve($sessionId);  // This gets the session object

        // Get the customer ID from the session object
        $customerId = $session->customer;

        // Fetch the subscription using the subscription ID
        $stripeSubscription = \Stripe\Subscription::retrieve($stripe_id);  // This is your subscription ID

        // Retrieve the customer using the customer ID from the session
        $stripeCustomer = \Stripe\Customer::retrieve($customerId);

        // Now proceed to create the subscription in your database
        $subscription = Subscription::create([
            'user_id' => $stripeCustomer->id,
            'master_user_details_id' => $dynamicId,
            'type' => $period,
            'stripe_id' => $stripeSubscription->id,
            'stripe_status' => $stripeSubscription->status,
            'stripe_price' => $stripeSubscription->items->data[0]->price->id,
            'quantity' => $stripeSubscription->items->data[0]->quantity,
            'trial_ends_at' => $stripeSubscription->trial_end ? Carbon::createFromTimestamp($stripeSubscription->trial_end) : null,
            'ends_at' => $stripeSubscription->ended_at ? Carbon::createFromTimestamp($stripeSubscription->ended_at) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            // Redirect to a success page with a success message
           
        // Create a new agency record
        $agency = new MasterUserDetails();
        $agency->setTableForUniqueId($userId);

        $agency->fill([
            'users_id' => $users_id,
            'id' => $dynamicId,
            'user_id' => $user->user_id,
            'user_agency_numbers' => $validated['user_agency_numbers'] ?? '',
            'user_work_email' => $validated['user_work_email'] ?? '',
            'user_dob' => $validated['user_dob'] ?? '',
            'user_emergency_contact_person' => $validated['user_emergency_contact_person'] ?? '',
            'user_emergency_phone_number' => $validated['user_emergency_phone_number'] ?? '',
            'user_emergency_email' => $validated['user_emergency_email'] ?? '',
            'users_country' => $validated['users_country'] ?? '',
            'users_state' => $validated['users_state'] ?? '',
            'users_city' => $validated['users_city'] ?? '',
            'role_id' => $validated['role_id'] ?? '',
            'users_first_name' => $validated['users_first_name'] ?? '',
            'users_last_name' => $validated['users_last_name'] ?? '',
            'users_email' => $validated['users_email'] ?? '',
            'users_address' => $validated['users_address'] ?? '',
            'users_zip' => $validated['users_zip'] ?? '0',
            'users_bio' => '',
            'users_status' => 1,
            'users_password' => Hash::make($validated['users_password']),
        ]);
        
        $agency->users_status = 1;
        $agency->save();

        // Process agency phone items
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                if (empty($item) || !is_array($item)) {
                    continue;
                }

                $travelerItem = new AgencyPhones();
                $travelerItem->age_id = $agency->users_id;
                $travelerItem->id = $dynamicId;
                $travelerItem->age_user_phone_id = $this->GenerateUniqueRandomString(
                    $travelerItem->getTable(),
                    'age_user_phone_id',
                    6
                );
                $travelerItem->fill($item);
                $travelerItem->save();
            }
        }

        // Calculate subscription expiration
        $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
        $startDate = Carbon::now();
        $months = ($period == 'monthly') ? 1 : (($period == 'yearly') ? 12 : 6);
        $expiryDate = $startDate->addMonths($months)->toDateString();

        // Update user details for subscription
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId(strtolower($userId));
        $user_data = $userDetails->where('users_id', $users_id)->first();

        if ($user_data) {
            $user_data->update([
                'stripe_status' => 'active',
                'plan_id' => $plan_id,
                'stripe_id' => $stripe_id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $expiryDate,
                'plan_type' => $period,
                'users_status' => 1,
            ]);
        }

        // Send the email with the login URL
        try {
            Mail::to($email)->send(new UsersDetails($userId, $loginUrl, $email));
            session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
        } catch (\Exception $e) {
            session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
        }

        return redirect()->route('agency.index')->with('success', 'Agency User entry created successfully.');
    // } else {
    //     return redirect()->route('agencycancel')->withErrors(['error' => 'Payment failed or incomplete.']);
    // }
        // Redirect to the agency index page with a success message
      
    } catch (\Exception $e) {
        \Log::error('Error processing payment success:', ['error' => $e->getMessage()]);
        return back()->withErrors(['error' => 'Something went wrong. Please try again later.']);
    }
}

    

    public function agencyCancel()
    {
        return "Payment is canceled.";
    }
    
}

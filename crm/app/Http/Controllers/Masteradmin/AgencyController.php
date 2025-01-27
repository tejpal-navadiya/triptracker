<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Agency;
use App\Models\AgencyPhones;
use App\Models\StaticAgentPhone;
use App\Models\UserRole;
use App\Models\MasterUserDetails;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UsersDetails;
use App\Models\Customer;
use App\Models\Subscription;
use Stripe\Stripe;
use App\Models\Plan;
use Carbon\Carbon;


class AgencyController extends Controller
{
    public function index(): View
    {
      $user = Auth::guard('masteradmins')->user();
      
      $agency = new MasterUserDetails();
      $agency->setTableForUniqueId($user->user_id);
      $agency = $agency->get();
      
      $agency->each(function($detail) {
          $detail->load('userRole');
      });
      
      //dd($agency); 
      $users_role = UserRole::all(); 

      return view('masteradmin.agency.index',compact('agency','users_role'));
    }

    public function create(): View
    {
      $user = Auth::guard('masteradmins')->user();

        $phones_type = StaticAgentPhone::all();
        $users_role = UserRole::all();
        $country = Countries::all();

        $agency = new MasterUserDetails();
        $agency->setTableForUniqueId($user->user_id);
        $agency = $agency->count();

        $nextAgencyNumber = str_pad($agency + 1, 3, '0', STR_PAD_LEFT); // Auto-increment logic
      // dd($nextAgencyNumber);
      
        return view('masteradmin.agency.create', compact('phones_type','users_role','country','nextAgencyNumber'));
    }

    public function store1(Request $request)
    {

      $user = Auth::guard('masteradmins')->user();
      // dd($user->plan_id);
      $dynamicId = $user->id;

      // \DB::enableQueryLog();

      $plan = Plan::where('sp_id', $user->plan_id)->first();
      // dd(\DB::getQueryLog()); 

      // dd($plan);
      $price_stripe_id = $plan->stripe_id ?? '';

      $period = $user->plan_type ?? '';
      $startDate = Carbon::now();
      if($period == 'monthly'){
          $months = 1;
      }else if($period == 'yearly'){
          $months = 12;
      }else{
          $months = 6;
      }
    

      // dd($user);
     // dd($request->all());
    
      $validatedData = $request->validate([
        'users_first_name' => 'required|string|max:255',
        'users_last_name' => 'required|string|max:255',
        'users_email' => 'required|email|max:255',
        'users_address' => 'nullable|string|max:255',
        'users_zip' => 'nullable|numeric|digits_between:1,6',
        'user_agency_numbers' => 'required|string|max:255',
        'user_work_email' => 'required|email|max:255',
        'user_dob' => 'nullable|date',
        'user_emergency_contact_person' => 'nullable|string|max:255',
        'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
        'user_emergency_email' => 'nullable|email|max:255',
        'users_country' => 'nullable|string|max:255',
        'users_state' => 'nullable|string|max:255',
        'users_city' => 'nullable|string|max:255',
        'role_id' => 'required|string|max:255',
        'users_password' => 'required|string|min:6', // Assuming min length for password
    ], [
        'users_first_name.required' => 'First name is required',
        'users_last_name.required' => 'Last name is required',
        'users_email.required' => 'Email is required',
        'user_agency_numbers.required' => 'ID Number is required',
        'users_password.required' => 'Password is required',
        'users_password.min' => 'Password must be at least 6 characters long',
        'role_id.required' => 'Role is required',
        'user_work_email.regex' => 'Please enter a valid email address.',
        'users_email.regex' => 'Please enter a valid email address.',
        'user_emergency_email.regex' => 'Please enter a valid email address.',
    ]);


    $agency = new MasterUserDetails();
    $agency->setTableForUniqueId($user->user_id);
    $tableName = $agency->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);

    //dd($agency);

        $agency->users_id = $users_id;
        $agency->id = $dynamicId;


    $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();

    // if ($existingAgency) {
    //     return redirect()->back()->withErrors(['users_email' => 'The email address is already in use.'])->withInput();
    // }

    // $existingWorkemail = $agency->where('user_work_email', $validatedData['user_work_email'])->first();

    // if ($existingWorkemail) {
    //     return redirect()->back()->withErrors(['user_work_email' => 'The email address is already in use.'])->withInput();
    // }


        $agency->user_id = $user->user_id;   

      $agency->user_agency_numbers = $validatedData['user_agency_numbers'];
      $agency->user_work_email = $validatedData['user_work_email'];
      $agency->user_dob = $validatedData['user_dob'];
      $agency->user_emergency_contact_person = $validatedData['user_emergency_contact_person'];
      $agency->user_emergency_phone_number = $validatedData['user_emergency_phone_number'];
      $agency->user_emergency_email = $validatedData['user_emergency_email'];
      $agency->users_country = $validatedData['users_country'];
      $agency->users_state = $validatedData['users_state'];
      $agency->users_city = $validatedData['users_city'];
      $agency->role_id = $validatedData['role_id'];
      $agency->users_first_name = $validatedData['users_first_name'];
      $agency->users_last_name = $validatedData['users_last_name'];
      $agency->users_email  = $validatedData['users_email'];
      $agency->users_address = $validatedData['users_address'];
      $agency->users_zip = $validatedData['users_zip'];
      $agency->users_bio = '';  
      $agency->users_status = 1;  
      $agency->users_password = Hash::make($validatedData['users_password']);
      $agency->save();

      
    $rawItems = $request->input('items', []);   

      foreach ($rawItems as $item) {
        if (empty($item) || !is_array($item)) {
          continue;
      }
          $travelerItem = new AgencyPhones();
          $tableName = $travelerItem->getTable();
          $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
      
            // Assign the generated unique ID
            $travelerItem->age_id = $agency->users_id;
            $travelerItem->id = $dynamicId;
            $travelerItem->age_user_phone_id = $ageid;

          $travelerItem->fill($item);

          $travelerItem->save();
      }
      $userDetails = new MasterUserDetails();
      $userDetails->setTableForUniqueId(strtolower($user->user_id));
      $user_data = $userDetails->orderBy('created_at', 'desc')->first();



      Stripe::setApiKey(env('STRIPE_SECRET'));

      $stripePriceId = $price_stripe_id;
   
      $quantity = 1;

      try {

          $stripeCustomer = \Stripe\Customer::create([
              'email' => $request->users_email,
              'name' => $request->users_first_name . ' ' . $request->users_last_name,
          ]);
          

          $customer = Customer::create([
              'stripe_id' => $stripeCustomer->id,
              'email' => $request->users_email,
              'name' => $request->users_first_name . ' ' . $request->users_last_name,
          ]);

          // dd($customer);

          $Stripesubscription = \Stripe\Subscription::create([
              'customer' => $stripeCustomer->id,
              'items' => [['price' => $stripePriceId]], // Assuming $request->plan_id contains the price ID
              'trial_period_days' => ($period == 'monthly') ? 14 : null, // Trial period if needed
          ]);
          $latestInvoiceId = $Stripesubscription->latest_invoice;
          

          $subscription = Subscription::create([
              'user_id' => $stripeCustomer->id, // Assuming you have a user_id to associate with the subscription
              'master_user_details_id' => $users_id, // Set this if you have a master user details ID
              'type' => $period, // Set the type of subscription
              'stripe_id' => $Stripesubscription->id,
              'stripe_status' => $Stripesubscription->status,
              'stripe_price' => $Stripesubscription->items->data[0]->price->id, // Assuming you want to store the price ID
              'quantity' => $Stripesubscription->items->data[0]->quantity,
              'trial_ends_at' => $Stripesubscription->trial_end ? Carbon::createFromTimestamp($Stripesubscription->trial_end) : null,
              'ends_at' => $Stripesubscription->ended_at ? Carbon::createFromTimestamp($Stripesubscription->ended_at) : null,
              'created_at' => now(),
              'updated_at' => now(),
          ]);


          // dd($subscription);
   
        return $user_data->checkout([$stripePriceId => $quantity], [
            'success_url' => route('agencysuccess', [
                        'user_id' => $user_data->user_id,
                        'email' => $user_data->users_email, 
                        'stripe_id' => $subscription->stripe_id,
                        'plan_id' => $user->plan_id,
                        'period' => $period,
                        'latestInvoiceId' => $latestInvoiceId,
                        'users_id' => $users_id,

                    ]),
            'cancel_url' => route('agencycancel'),
            'mode' => 'subscription',
        ]);

      
      // return $user_data
      // ->newSubscription($stripePriceId, $stripePriceId)
      // ->checkout([
      //     'success_url' => route('success', [
      //         'session_id' => '{CHECKOUT_SESSION_ID}',
      //         'user_id' => $user_data->user_id,
      //         'email' => $user_data->users_email, // If email must be included
      //     ]),
      //     'cancel_url' => route('cancel'),
      //     'mode' => 'subscription',
      // ]);
  

      } catch (\Exception $e) {
          // Handle Stripe error
          return back()->with(['link-error' => 'There was an issue creating the subscription: ' . $e->getMessage()]);
      }
      
      // $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $request->users_email, 'user_id' => $user->user_id]);
      //   try {
      //       Mail::to($request->users_email)->send(new UsersDetails($user->user_id, $loginUrl, $request->users_email));
      //       session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
      //   } catch (\Exception $e) {
      //       session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
      //   }

      // return redirect()->route('agency.index')->with('success', 'Agecy User entry created successfully.');

      \MasterLogActivity::addToLog('Master Admin Agency Users is Created.');

    }

    public function store2(Request $request)
{
    // Get the current user and plan details
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->id;
    $period = $user->plan_type ?? '';
    // $stripePriceId = Plan::where('sp_id', $user->plan_id)->value('stripe_id');
    $plan = Plan::where('sp_id', $user->plan_id)->firstOrFail();
    $stripePriceId = $plan->stripe_id ?? '';
    
    $quantity = 1;

    $validatedData = $request->validate([
      'users_first_name' => 'required|string|max:255',
      'users_last_name' => 'required|string|max:255',
      'users_email' => 'required|email|max:255',
      'users_address' => 'nullable|string|max:255',
      'users_zip' => 'nullable|numeric|digits_between:1,6',
      'user_agency_numbers' => 'required|string|max:255',
      'user_work_email' => 'required|email|max:255',
      'user_dob' => 'nullable|date',
      'user_emergency_contact_person' => 'nullable|string|max:255',
      'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
      'user_emergency_email' => 'nullable|email|max:255',
      'users_country' => 'nullable|string|max:255',
      'users_state' => 'nullable|string|max:255',
      'users_city' => 'nullable|string|max:255',
      'role_id' => 'required|string|max:255',
      'users_password' => 'required|string|min:6', // Assuming min length for password
  ], [
      'users_first_name.required' => 'First name is required',
      'users_last_name.required' => 'Last name is required',
      'users_email.required' => 'Email is required',
      'user_agency_numbers.required' => 'ID Number is required',
      'users_password.required' => 'Password is required',
      'users_password.min' => 'Password must be at least 6 characters long',
      'role_id.required' => 'Role is required',
      'user_work_email.regex' => 'Please enter a valid email address.',
      'users_email.regex' => 'Please enter a valid email address.',
      'user_emergency_email.regex' => 'Please enter a valid email address.',
  ]);
  $rawItems = $request->input('items', []);

    $userDetails = new MasterUserDetails();
    $userDetails->setTableForUniqueId(strtolower($user->user_id));
    $user_data = $userDetails->orderBy('created_at', 'desc')->first();

    $agency = new MasterUserDetails();
    $agency->setTableForUniqueId($user->user_id);
    $tableName = $agency->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);
    
    $errors = [];

    // Check if the personal email is already in use
    $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();
    if ($existingAgency) {
        $errors['users_email'] = 'The email address is already in use.';
    }
    
    // Check if the work email is already in use
    $existingAgency = $agency->where('user_work_email', $validatedData['user_work_email'])->first();
    if ($existingAgency) {
        $errors['user_work_email'] = 'The work email address is already in use.';
    }

    
    // If there are errors, pass them back to the form
    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }

    // Stripe payment processing (customer creation and subscription)
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
       
      
      $stripeCustomer = \Stripe\Customer::create([
        'email' => $request->users_email,
        'name' => $request->users_first_name . ' ' . $request->users_last_name,
    ]);
    

    $customer = Customer::create([
        'stripe_id' => $stripeCustomer->id,
        'email' => $request->users_email,
        'name' => $request->users_first_name . ' ' . $request->users_last_name,
    ]);

    // dd($customer);

    $Stripesubscription = \Stripe\Subscription::create([
        'customer' => $stripeCustomer->id,
        'items' => [['price' => $stripePriceId]], // Assuming $request->plan_id contains the price ID
        'trial_period_days' => ($period == 'monthly') ? 14 : null, // Trial period if needed
    ]);
   
    $latestInvoiceId = $Stripesubscription->latest_invoice;
    
 
    $subscription = Subscription::create([
        'user_id' => $stripeCustomer->id, // Assuming you have a user_id to associate with the subscription
        'master_user_details_id' => $users_id, // Set this if you have a master user details ID
        'type' => $period, // Set the type of subscription
        'stripe_id' => $Stripesubscription->id,
        'stripe_status' => $Stripesubscription->status,
        'stripe_price' => $Stripesubscription->items->data[0]->price->id, // Assuming you want to store the price ID
        'quantity' => $Stripesubscription->items->data[0]->quantity,
        'trial_ends_at' => $Stripesubscription->trial_end ? Carbon::createFromTimestamp($Stripesubscription->trial_end) : null,
        'ends_at' => $Stripesubscription->ended_at ? Carbon::createFromTimestamp($Stripesubscription->ended_at) : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    // dd($subscription);
        // Get the latest invoice ID

        // Create the user record (you need to save it after successful payment, hence no user data is created here yet)

        // Step 2: Redirect to success_url after Stripe payment
        return $user_data->checkout([$stripePriceId => $quantity], [
            'success_url' => route('agencysuccess', [
                'user_id' => $user->user_id, // Pass user_id
                'email' => $request->users_email, // Pass email
                'stripe_id' => $Stripesubscription->id, // Pass Stripe subscription ID
                'plan_id' => $user->plan_id, // Pass plan_id
                'period' => $period, // Pass period (monthly/yearly)
                'latestInvoiceId' => $latestInvoiceId, // Pass invoice ID
                'users_id' => $users_id,
                'validated' => $validatedData,
                'items' => $rawItems,
                
            ]),
            'cancel_url' => route('agencycancel'),
            'mode' => 'subscription',
        ]);

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()]);
    }
}

public function store3(Request $request)
{
    // Get the current user and plan details
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->id;
    $period = $user->plan_type ?? '';
    $plan = Plan::where('sp_id', $user->plan_id)->firstOrFail();
    $stripePriceId = $plan->stripe_id ?? '';
    
    $quantity = 1;

    $validatedData = $request->validate([
      'users_first_name' => 'required|string|max:255',
      'users_last_name' => 'required|string|max:255',
      'users_email' => 'required|email|max:255',
      'users_address' => 'nullable|string|max:255',
      'users_zip' => 'nullable|numeric|digits_between:1,6',
      'user_agency_numbers' => 'required|string|max:255',
      'user_work_email' => 'required|email|max:255',
      'user_dob' => 'nullable|date',
      'user_emergency_contact_person' => 'nullable|string|max:255',
      'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
      'user_emergency_email' => 'nullable|email|max:255',
      'users_country' => 'nullable|string|max:255',
      'users_state' => 'nullable|string|max:255',
      'users_city' => 'nullable|string|max:255',
      'role_id' => 'required|string|max:255',
      'users_password' => 'required|string|min:6', // Assuming min length for password
    ]);

    $rawItems = $request->input('items', []);

    $userDetails = new MasterUserDetails();
    $userDetails->setTableForUniqueId(strtolower($user->user_id));
    $user_data = $userDetails->orderBy('created_at', 'desc')->first();

    // $agency = new MasterUserDetails();
    // $agency->setTableForUniqueId($user->user_id);
    $tableName = $userDetails->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);
    
    $errors = [];

    // // Check if the personal email is already in use
    $existingAgency = $userDetails->where('users_email', $validatedData['users_email'])->first();
    if ($existingAgency) {
        $errors['users_email'] = 'The email address is already in use.';
    }
    
    // Check if the work email is already in use
    $existingAgency = $userDetails->where('user_work_email', $validatedData['user_work_email'])->first();
    if ($existingAgency) {
        $errors['user_work_email'] = 'The work email address is already in use.';
    }

    //If there are errors, pass them back to the form
    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }

    // Stripe payment processing (customer creation and subscription)
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Step 1: Create the customer in Stripe
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $request->users_email,
            'name' => $request->users_first_name . ' ' . $request->users_last_name,
        ]);
        \Log::info('Stripe customer created: ' . $stripeCustomer->id);

        // dd($stripeCustomer->id);

        // Step 2: Store the customer locally in your database
        $customer = Customer::create([
            'stripe_id' => $stripeCustomer->id,
            'email' => $request->users_email,
            'name' => $request->users_first_name . ' ' . $request->users_last_name,
        ]);

        // Step 3: Create a subscription for the customer
        $Stripesubscription = \Stripe\Subscription::create([
            'customer' => $stripeCustomer->id,
            'items' => [['price' => $stripePriceId]], // Assuming $request->plan_id contains the price ID
            'trial_period_days' => ($period == 'monthly') ? 14 : null, // Trial period if needed
        ]);
        \Log::info('Stripe subscription created: ' . $Stripesubscription->id);
        \Log::info('Subscription status: ' . $Stripesubscription->status);


        // Step 4: Store the subscription locally in your database
        $latestInvoiceId = $Stripesubscription->latest_invoice;

        $subscription = Subscription::create([
            'user_id' => $stripeCustomer->id, // Associate the subscription with the customer ID
            'master_user_details_id' => $users_id,
            'type' => $period,
            'stripe_id' => $Stripesubscription->id,
            'stripe_status' => $Stripesubscription->status,
            'stripe_price' => $Stripesubscription->items->data[0]->price->id,
            'quantity' => $Stripesubscription->items->data[0]->quantity,
            'trial_ends_at' => $Stripesubscription->trial_end ? Carbon::createFromTimestamp($Stripesubscription->trial_end) : null,
            'ends_at' => $Stripesubscription->ended_at ? Carbon::createFromTimestamp($Stripesubscription->ended_at) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Step 5: Redirect to success_url after Stripe payment
        return $user_data->checkout([$stripePriceId => $quantity], [
            'success_url' => route('agencysuccess', [
                'user_id' => $user->user_id,
                'email' => $request->users_email,
                'stripe_id' => $stripeCustomer->id,
                'plan_id' => $user->plan_id,
                'period' => $period,
                'latestInvoiceId' => $latestInvoiceId,
                'users_id' => $users_id,
                'validated' => $validatedData,
                'items' => $rawItems,
            ]),
            'cancel_url' => route('agencycancel'),
            'mode' => 'subscription',
        ]);
    } catch (\Exception $e) {
        // Log the error message for debugging
        return back()->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()]);
    }
}

public function store4(Request $request)
{
    // Get the current user and plan details
    $user = Auth::guard('masteradmins')->user();
    //dd($user);
    $dynamicId = $user->id;
    $period = $user->plan_type ?? '';
    $plan = Plan::where('sp_id', $user->plan_id)->firstOrFail();

    // $stripePriceId = Plan::where('sp_id', $user->plan_id)->value('stripe_id');
    // if($period == 'monthly'){
    //     $months = 1;
    //     $stripePriceId = $plan->stripe_id ?? '';
    // }else if($period == 'yearly'){
    //     $months = 12;
    //     $stripePriceId = $plan->stripe_id_yr ?? '';
    // }else{
    //     $months = '';
    //     $stripePriceId = '';
    // }

    if ($period == 'monthly') {
        $months = 1;
        $price_stripe_id = $plan->stripe_id ?? ''; // Monthly price ID
        $trialPeriodDays = 7; // 7-day trial for monthly plan
    } else if ($period == 'yearly') {
        $months = 12;
        $price_stripe_id = $plan->stripe_id_yr ?? ''; // Yearly price ID
        $trialPeriodDays = 30; // 30-day trial for yearly plan
    } else {
        $months = 0;
        $price_stripe_id = '';
        $trialPeriodDays = null; // No trial for invalid period
    }
    $quantity = 1;

    $validatedData = $request->validate([
      'users_first_name' => 'required|string|max:255',
      'users_last_name' => 'required|string|max:255',
      'users_email' => 'required|email|max:255',
      'users_address' => 'nullable|string|max:255',
      'users_zip' => 'nullable|numeric|digits_between:1,6',
      'user_agency_numbers' => 'required|string|max:255',
      'user_work_email' => 'required|email|max:255',
      'user_dob' => 'nullable|date',
      'user_emergency_contact_person' => 'nullable|string|max:255',
      'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
      'user_emergency_email' => 'nullable|email|max:255',
      'users_country' => 'nullable|string|max:255',
      'users_state' => 'nullable|string|max:255',
      'users_city' => 'nullable|string|max:255',
      'role_id' => 'required|string|max:255',
      'users_password' => 'required|string|min:6', // Assuming min length for password
  ], [
      'users_first_name.required' => 'First name is required',
      'users_last_name.required' => 'Last name is required',
      'users_email.required' => 'Email is required',
      'user_agency_numbers.required' => 'ID Number is required',
      'users_password.required' => 'Password is required',
      'users_password.min' => 'Password must be at least 6 characters long',
      'role_id.required' => 'Role is required',
      'user_work_email.regex' => 'Please enter a valid email address.',
      'users_email.regex' => 'Please enter a valid email address.',
      'user_emergency_email.regex' => 'Please enter a valid email address.',
  ]);
  $rawItems = $request->input('items', []);

    $userDetails = new MasterUserDetails();
    $userDetails->setTableForUniqueId(strtolower($user->user_id));
    $user_data = $userDetails->orderBy('created_at', 'desc')->first();

    $agency = new MasterUserDetails();
    $agency->setTableForUniqueId($user->user_id);
    $tableName = $agency->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);
    
    $errors = [];

    // Check if the personal email is already in use
    $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();
    if ($existingAgency) {
        $errors['users_email'] = 'The email address is already in use.';
    }
    
    // Check if the work email is already in use
    $existingAgency = $agency->where('user_work_email', $validatedData['user_work_email'])->first();
    if ($existingAgency) {
        $errors['user_work_email'] = 'The work email address is already in use.';
    }

    $existingAgency = $agency->where('user_emergency_email', $validatedData['user_emergency_email'])->first();
    if ($existingAgency) {
        $errors['user_emergency_email'] = 'The Emergency email address is already in use.';
    }
    
    // If there are errors, pass them back to the form
    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }

    // Stripe payment processing (customer creation and subscription)
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
       
      
      $stripeCustomer = \Stripe\Customer::create([
        'email' => $request->users_email,
        'name' => $request->users_first_name . ' ' . $request->users_last_name,
    ]);
    

    $customer = Customer::create([
        'stripe_id' => $stripeCustomer->id,
        'email' => $request->users_email,
        'name' => $request->users_first_name . ' ' . $request->users_last_name,
    ]);

    // dd($customer);

    // $Stripesubscription = \Stripe\Subscription::create([
    //     'customer' => $stripeCustomer->id,
    //     'items' => [['price' => $stripePriceId]], // Assuming $request->plan_id contains the price ID
    //     'trial_period_days' => ($period == 'monthly') ? 14 : null, // Trial period if needed
    // ]);

    $Stripesubscription = \Stripe\Subscription::create([
        'customer' => $stripeCustomer->id,
        'items' => [
            ['price' => $price_stripe_id],
        ],
        'trial_period_days' => $trialPeriodDays, // Set trial period dynamically
    ]);

    
    $latestInvoiceId = $Stripesubscription->latest_invoice;
    

    $subscription = Subscription::create([
        'user_id' => $stripeCustomer->id, // Assuming you have a user_id to associate with the subscription
        'master_user_details_id' => $users_id, // Set this if you have a master user details ID
        'type' => $period, // Set the type of subscription
        'stripe_id' => $Stripesubscription->id,
        'stripe_status' => $Stripesubscription->status,
        'stripe_price' => $Stripesubscription->items->data[0]->price->id, // Assuming you want to store the price ID
        'quantity' => $Stripesubscription->items->data[0]->quantity,
        'trial_ends_at' => $Stripesubscription->trial_end ? Carbon::createFromTimestamp($Stripesubscription->trial_end) : null,
        'ends_at' => $Stripesubscription->ended_at ? Carbon::createFromTimestamp($Stripesubscription->ended_at) : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

        // Get the latest invoice ID
        $latestInvoiceId = $Stripesubscription->latest_invoice;

        // Create the user record (you need to save it after successful payment, hence no user data is created here yet)

        // Step 2: Redirect to success_url after Stripe payment
        return $user_data->checkout([$price_stripe_id => $quantity], [
            'success_url' => route('agencysuccess', [
                'user_id' => $user->user_id, // Pass user_id
                'email' => $request->users_email, // Pass email
                'stripe_id' => $Stripesubscription->id, // Pass Stripe subscription ID
                'plan_id' => $user->plan_id, // Pass plan_id
                'period' => $period, // Pass period (monthly/yearly)
                'latestInvoiceId' => $latestInvoiceId, // Pass invoice ID
                'users_id' => $users_id,
                'validated' => $validatedData,
                'items' => $rawItems,
                
            ]),
            'cancel_url' => route('agencycancel'),
            'mode' => 'subscription',
        ]);

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()]);
    }
}

public function store(Request $request)
{
    // Get the current user and plan details
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->id;
    $period = $user->plan_type ?? '';
   //  dd($period);
    $plan = Plan::where('sp_id', $user->plan_id)->first();
    
    if($plan->sp_month_amount == 0 && $plan->sp_year_amount == 0){
        
            $validatedData = $request->validate([
        'users_first_name' => 'required|string|max:255',
        'users_last_name' => 'required|string|max:255',
        'users_email' => 'required|email|max:255',
        'users_address' => 'nullable|string|max:255',
        'users_zip' => 'nullable|numeric|digits_between:1,6',
        'user_agency_numbers' => 'required|string|max:255',
        'user_work_email' => 'required|email|max:255',
        'user_dob' => 'nullable|date',
        'user_emergency_contact_person' => 'nullable|string|max:255',
        'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
        'user_emergency_email' => 'nullable|email|max:255',
        'users_country' => 'nullable|string|max:255',
        'users_state' => 'nullable|string|max:255',
        'users_city' => 'nullable|string|max:255',
        'role_id' => 'nullable|string|max:255',
        'users_password' => 'required|string|min:6', // Assuming min length for password
    ], [
        'users_first_name.required' => 'First name is required',
        'users_last_name.required' => 'Last name is required',
        'users_email.required' => 'Email is required',
        'user_agency_numbers.required' => 'ID Number is required',
        'users_password.required' => 'Password is required',
        'users_password.min' => 'Password must be at least 6 characters long',
    ]);


    $agency = new MasterUserDetails();
    $agency->setTableForUniqueId($user->user_id);
    $tableName = $agency->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);

    //dd($agency);

        $agency->users_id = $users_id;
        $agency->id = $dynamicId;


    $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();

    if ($existingAgency) {
        return redirect()->back()->withErrors(['users_email' => 'The email address is already in use.'])->withInput();
    }

    $existingWorkemail = $agency->where('user_work_email', $validatedData['user_work_email'])->first();

    if ($existingWorkemail) {
        return redirect()->back()->withErrors(['user_work_email' => 'The email address is already in use.'])->withInput();
    }


        $agency->user_id = $user->user_id;   

      $agency->user_agency_numbers = $validatedData['user_agency_numbers'];
      $agency->user_work_email = $validatedData['user_work_email'];
      $agency->user_dob = $validatedData['user_dob'];
      $agency->user_emergency_contact_person = $validatedData['user_emergency_contact_person'];
      $agency->user_emergency_phone_number = $validatedData['user_emergency_phone_number'];
      $agency->user_emergency_email = $validatedData['user_emergency_email'];
      $agency->users_country = $validatedData['users_country'];
      $agency->users_state = $validatedData['users_state'];
      $agency->users_city = $validatedData['users_city'];
      $agency->role_id = $validatedData['role_id'];
      $agency->users_first_name = $validatedData['users_first_name'];
      $agency->users_last_name = $validatedData['users_last_name'];
      $agency->users_email  = $validatedData['users_email'];
      $agency->users_address = $validatedData['users_address'];
      $agency->users_zip = $validatedData['users_zip'];
      $agency->users_bio = '';  
      $agency->users_status = 1;  
      $agency->users_password = Hash::make($validatedData['users_password']);
    
      $agency->save();

      
    $rawItems = $request->input('items', []);   

      foreach ($rawItems as $item) {
        if (empty($item) || !is_array($item)) {
          continue;
      }
          $travelerItem = new AgencyPhones();
          $tableName = $travelerItem->getTable();
          $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
      
            // Assign the generated unique ID
            $travelerItem->age_id = $agency->users_id;
            $travelerItem->id = $dynamicId;
            $travelerItem->age_user_phone_id = $ageid;

          $travelerItem->fill($item);

          $travelerItem->save();
      }

      $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $request->user_work_email, 'user_id' => $user->user_id]);
        try {
            Mail::to($request->user_work_email)->send(new UsersDetails($user->user_id, $loginUrl, $request->user_work_email));
            session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
        } catch (\Exception $e) {
            session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
        }

      return redirect()->route('agency.index')->with('success', 'Agecy User entry created successfully.');

      \MasterLogActivity::addToLog('Master Admin Users Certification Created.');
        
    }else{
        
        if ($period == 'monthly') {
            $months = 1;
            $price_stripe_id = $plan->stripe_id ?? ''; // Monthly price ID
            $trialPeriodDays = 7; // 7-day trial for monthly plan
        } else if ($period == 'yearly') {
            $months = 12;
            $price_stripe_id = $plan->stripe_id_yr ?? ''; // Yearly price ID
            $trialPeriodDays = 30; // 30-day trial for yearly plan
        } else {
            $months = 0;
            $price_stripe_id = '';
            $trialPeriodDays = null; // No trial for invalid period
        }
            
              //dd($plan);
            // $stripePriceId = Plan::where('sp_id', $user->plan_id)->value('stripe_id');
           
           
            $quantity = 1;
            
            $validatedData = $request->validate([
                'users_first_name' => 'required|string|max:255',
                'users_last_name' => 'required|string|max:255',
                'users_email' => 'required|email|max:255',
                'users_address' => 'nullable|string|max:255',
                'users_zip' => 'nullable|numeric|digits_between:1,6',
                'user_agency_numbers' => 'required|string|max:255',
                'user_work_email' => 'required|email|max:255',
                'user_dob' => 'nullable|date',
                'user_emergency_contact_person' => 'nullable|string|max:255',
                'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
                'user_emergency_email' => 'nullable|email|max:255',
                'users_country' => 'nullable|string|max:255',
                'users_state' => 'nullable|string|max:255',
                'users_city' => 'nullable|string|max:255',
                'role_id' => 'required|string|max:255',
                'users_password' => 'required|string|min:6',
            ], [
                'users_first_name.required' => 'First name is required',
                'users_last_name.required' => 'Last name is required',
                'users_email.required' => 'Email is required',
                'user_agency_numbers.required' => 'ID Number is required',
                'users_password.required' => 'Password is required',
                'users_password.min' => 'Password must be at least 6 characters long',
                'role_id.required' => 'Role is required',
                'user_work_email.regex' => 'Please enter a valid email address.',
                'users_email.regex' => 'Please enter a valid email address.',
                'user_emergency_email.regex' => 'Please enter a valid email address.',
            ]);
        
            $errors = [];
            $rawItems = $request->input('items', []);
        
            // Validate unique emails
            $agency = new MasterUserDetails();
            $agency->setTableForUniqueId($user->user_id);
            $tableName = $agency->getTable();
            $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);
        
            $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();
            if ($existingAgency) {
                $errors['users_email'] = 'The email address is already in use.';
            }
        
            $existingAgency = $agency->where('user_work_email', $validatedData['user_work_email'])->first();
            if ($existingAgency) {
                $errors['user_work_email'] = 'The work email address is already in use.';
            } 
            
        
            if (!empty($errors)) {
                return redirect()->route('agency.create')->withErrors($errors)->withInput();
            }

           // Stripe payment processing (customer creation and subscription)
            Stripe::setApiKey(env('STRIPE_SECRET'));
        
            try {
        
                $stripeCustomer = \Stripe\Customer::create([
                    'email' => $request->users_email,
                    'name' => $request->users_first_name . ' ' . $request->users_last_name,
                ]);
        
                      // Save customer details in the database
                      $customer = Customer::create([
                        'stripe_id' => $stripeCustomer->id,
                        'email' => $validatedData['users_email'],
                        'name' => $validatedData['users_first_name'] . ' ' . $validatedData['users_last_name'],
                    ]);
            
                    $Stripesubscription = \Stripe\Subscription::create([
                        'customer' => $stripeCustomer->id,
                        'items' => [
                            ['price' => $price_stripe_id],
                        ],
                        'trial_period_days' => $trialPeriodDays, // Set trial period dynamically
                    ]);
                    // // Create Stripe subscription
                    // $Stripesubscription = \Stripe\Subscription::create([
                    //     'customer' => $stripeCustomer->id,
                    //     'items' => [['price' => $stripePriceId]],
                    //     'trial_period_days' => ($period == 'monthly') ? 14 : null,
                    // ]);
            
                    // // Save subscription details in the database
                    // $subscription = Subscription::create([
                    //     'user_id' => $stripeCustomer->id,
                    //     'master_user_details_id' => $dynamicId,
                    //     'type' => $period,
                    //     'stripe_id' => $Stripesubscription->id,
                    //     'stripe_status' => $Stripesubscription->status,
                    //     'stripe_price' => $Stripesubscription->items->data[0]->price->id,
                    //     'quantity' => $Stripesubscription->items->data[0]->quantity,
                    //     'trial_ends_at' => $Stripesubscription->trial_end ? Carbon::createFromTimestamp($Stripesubscription->trial_end) : null,
                    //     'ends_at' => $Stripesubscription->ended_at ? Carbon::createFromTimestamp($Stripesubscription->ended_at) : null,
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
                    $latestInvoiceId = $Stripesubscription->latest_invoice;
        
                    $baseSuccessUrl = route('agencysuccess', [
                        'user_id' => $user->user_id,
                        'email' => $validatedData['user_work_email'],
                        'stripe_id' => $Stripesubscription->id,
                        'plan_id' => $user->plan_id,
                        'period' => $period,
                        'latestInvoiceId' => $latestInvoiceId,
                        'users_id' => $users_id,
                        'validated' => $validatedData,
                        'items' => $rawItems,
                    ]);
        
                    $successUrl = $baseSuccessUrl . '&session_id={CHECKOUT_SESSION_ID}';
        
               
                // Create Stripe Checkout session
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'mode' => 'subscription',
                    'line_items' => [[
                        'price' => $price_stripe_id,
                        'quantity' => $quantity,
                    ]],
                    'customer_email' => $validatedData['user_work_email'],
                    'success_url' => $successUrl,
                    'cancel_url' => route('agencycancel'),
                ]);
        
                // Redirect to Stripe's hosted Checkout page
                return redirect($session->url);
        
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Failed to process payment: ' . $e->getMessage()]);
            }
            
    }
 
}



    public function edit($id)
  {
   
    $user = Auth::guard('masteradmins')->user();

    $masteruser = new MasterUserDetails();
    $masteruser->setTableForUniqueId($user->user_id);
    $agency = $masteruser->where('users_id', $id)->firstOrFail();

    //dd($agency->);

    $country = Countries::all();

    $selectedCountryId = $agency->users_country;

    $states = States::where('country_id', $selectedCountryId)->get();

    $selectedStateId = $agency->users_state;

    $city = Cities::where('state_id', $selectedStateId)->get();

    $users_role = UserRole::all(); 
  
    $agent = AgencyPhones::where('age_id', $agency->users_id)->get();
    $phones_type = StaticAgentPhone::all();


    

    return view('masteradmin.agency.edit', compact('agency','phones_type', 'users_role', 'agent','country','states','city'));
    
    } 

   public function update(Request $request, $users_id)
  {
    $user = Auth::guard('masteradmins')->user();

    // dd($request->all());

    $masteruser = new MasterUserDetails();
    $masteruser->setTableForUniqueId($user->user_id);
    $tableName = $masteruser->getTable();
  
    $userdetailu = $masteruser->where(['users_id' => $users_id,'id' => $user->id])->firstOrFail();

    //dd( $userdetailu);

     // Validate incoming request data
     $validatedData = $request->validate([
      'users_first_name' => 'required|string|max:255',
      'users_last_name' => 'required|string|max:255',
      'users_email' => 'required|email|max:255',
      'users_address' => 'nullable|string|max:255',
      'users_zip' => 'nullable|numeric|digits_between:1,6',
      'user_agency_numbers' => 'required|string|max:255',
      'user_work_email' => 'required|email|max:255',
      'user_dob' => 'nullable|date',
      'user_emergency_contact_person' => 'nullable|string|max:255',
      'user_emergency_phone_number' => 'nullable|string|regex:/^[0-9]{1,12}$/',
      'user_emergency_email' => 'nullable|email|max:255',
      'users_country' => 'nullable|string|max:255',
      'users_state' => 'nullable|string|max:255',
      'users_city' => 'nullable|string|max:255',
      'role_id' => 'nullable|string|max:255',
      'users_password' => 'required|string|min:6', // Assuming min length for password
  ], [
      'users_first_name.required' => 'First name is required',
      'users_last_name.required' => 'Last name is required',
      'users_email.required' => 'Email is required',
      'user_agency_numbers.required' => 'ID Number is required',
      'users_password.required' => 'Password is required',
      'users_password.min' => 'Password must be at least 6 characters long',
      'role_id.required' => 'Role is required',
      'user_work_email.regex' => 'Please enter a valid email address.',
      'users_email.regex' => 'Please enter a valid email address.',
      'user_emergency_email.regex' => 'Please enter a valid email address.',
  ]);

  $validatedData['users_password'] = Hash::make($validatedData['users_password']) ?? '';

  // Prepare data for update
  $updateData = [
      'user_agency_numbers' => $validatedData['user_agency_numbers'],
      'users_first_name' => $validatedData['users_first_name'],
      'users_last_name' => $validatedData['users_last_name'],
      'user_work_email' => $validatedData['user_work_email'],
      'users_email' => $validatedData['users_email'],
      'user_dob' => $validatedData['user_dob'],
      'role_id' => $validatedData['role_id'],
      'users_password' => Hash::make($validatedData['users_password']), // Hash the password here
      'user_emergency_contact_person' => $validatedData['user_emergency_contact_person'],
      'user_emergency_email' => $validatedData['user_emergency_email'],
      'user_emergency_phone_number' => $validatedData['user_emergency_phone_number'],
      'users_address' => $validatedData['users_address'],
      'users_city' => $validatedData['users_city'],
      'users_country' => $validatedData['users_country'],
      'users_state' => $validatedData['users_state'],
      'users_zip' => $validatedData['users_zip'],
  ];

  $userdetailu->where(['users_id' => $users_id,'id' => $user->id])->update($validatedData);

  AgencyPhones::where('age_id', $users_id)->delete();


  //$rawItems = $request->input('items');  
  
    $rawItems = $request->input('items', []);   

  foreach ($rawItems as $item) {

      if (empty($item) || !is_array($item)) {
        continue;
    }

    $travelerItem = new AgencyPhones();

    $tableName = $travelerItem->getTable();

    $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
    
      // Assign the generated unique ID
      $travelerItem->age_id = $users_id;
      $travelerItem->id = $user->id;
      $travelerItem->age_user_phone_id = $ageid;


    $travelerItem->fill($item);


    $travelerItem->save();
}

    // Log the activity
    \MasterLogActivity::addToLog('Master Admin Agency Updated.');

    return redirect()->route('agency.index')->with('success', 'Agecy User Update created successfully.');
   }


   public function destroy($user_id)
  {
      // Get the authenticated master admin user
      $user = Auth::guard('masteradmins')->user();

     $masteruser = new MasterUserDetails();
     $masteruser->setTableForUniqueId($user->user_id);
     $agency = $masteruser->where('users_id', $user_id)->firstOrFail();


      if ($agency) {
        
        $agent_number = AgencyPhones::where('age_id', $user_id)->delete();
        $agency->where('users_id', $user_id)->delete();


      // Log the deletion
      \MasterLogActivity::addToLog('Master Admin Agency User is Deleted.');

      return redirect()->route( 'agency.index')->with('success', 'Agency deleted successfully');
   }
  }
  public function getStates($countryId)
  {
      $states = States::where('country_id', $countryId)->orderBy('name', 'ASC')->get();
      return response()->json($states);
  }
  
  public function getCities($stateId)
  {
      $cities = Cities::where('state_id', $stateId)->orderBy('name', 'ASC')->get();  // Fetch cities by state_id
      return response()->json($cities);
  }

  public function view($id)
  {
    $user = Auth::guard('masteradmins')->user();

    $masteruser = new MasterUserDetails();
    $masteruser->setTableForUniqueId($user->user_id);
    $agency = $masteruser->where('users_id', $id)->with('country','state','city')->firstOrFail();
    // dd($agency);

    return view('masteradmin.agency.view', compact('agency'));
  }


  public function assignUserRole(Request $request, $userId)
  {

    $user = Auth::guard('masteradmins')->user();
      
      $validatedData = $request->validate([
          'role_id' => 'required|string|max:255',
      ], [
          'role_id.required' => 'Role is required',
      ]);
  
      $agency = new MasterUserDetails();
      $agency->setTableForUniqueId($user->user_id);
      $tableName = $agency->getTable();
  
      $agencyUser = $agency->where('users_id', $userId)->first();
      if (!$agencyUser) {
          return redirect()->back()->withErrors(['user' => 'User not found.']);
      }
  
      $agencyUser->where('users_id', $userId)->update($validatedData);
  
      \MasterLogActivity::addToLog("Master Admin Agency User role assigned for user ID: {$userId}");
  
      return redirect()->route('agency.index')->with('success', 'User role assigned successfully.');
  }
  

}
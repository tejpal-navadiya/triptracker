<?php

namespace App\Http\Controllers\Auth\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\MasterUser;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\Plan;
use DB;
use App\Models\MasterUserDetails;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserRegistered;
use App\Models\States;
use App\Models\Countries;
use Illuminate\Support\Facades\Storage;
use App\Models\Cities;
use App\Models\Customer;
use App\Models\Subscription;
use Stripe\Stripe;
use App\Models\AdminMenu;


class RegisterController extends Controller
{
    //
    public function create(): View
    {     
        $states = States::get();
        $plan = Plan::get();
        $country = Countries::all();

        $librarycurrency = Countries::all();
        $librarystate = States::all();
      
        
        return view('masteradmin.auth.register',compact('states','plan','country','librarycurrency','librarystate'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $plan_id = $request->plan_id;
        $period = $request->period;

        $request->validate([
            'user_agencies_name' => ['required', 'string', 'max:255'],
            'user_franchise_name' => ['nullable', 'string', 'max:255'],
            'user_consortia_name' => ['nullable', 'string', 'max:255'],
            'user_first_name' => ['required', 'string', 'max:255'],
            'user_last_name' => ['nullable', 'string', 'max:255'],
            'user_iata_clia_number' => ['required', 'string', 'max:255'],
            'user_clia_number' => ['nullable', 'string', 'max:255'],
            'user_iata_number' => ['nullable', 'string', 'max:255'],
            'user_image' => ['nullable', 'string', 'max:255'],
            'user_address' => ['nullable', 'string', 'max:255'],
            'user_country' => ['nullable', 'string', 'max:255'],
            'user_state' => ['nullable', 'string', 'max:255'],
            'user_city' => ['nullable', 'string', 'max:255'],
            'user_zip' => ['nullable', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:'.MasterUser::class],
            'user_personal_email' => ['nullable', 'string', 'lowercase', 'email' ,'max:255', 'unique:'.MasterUser::class],
            'user_business_phone' => ['required', 'string', 'max:255'],
            'user_personal_phone' => ['nullable', 'string', 'max:255'],
            'user_password' => [
                function ($attribute, $value, $fail) {
                    $errors = [];

                    // Include all validation checks in the same message, even if empty
                    if (empty($value)) {
                        $errors[] = 'be provided';
                    }
                    if (strlen($value) < 8) {
                        $errors[] = 'have at least 8 characters';
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $errors[] = 'contain at least one lowercase letter';
                    }
                    if (!preg_match('/[A-Z]/', $value)) {
                        $errors[] = 'contain at least one uppercase letter';
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        $errors[] = 'contain at least one number';
                    }
                    if (!preg_match('/[@$!%*?&]/', $value)) {
                        $errors[] = 'contain at least one special character';
                    }

                    // Combine all errors into a single message
                    if (!empty($errors)) {
                        $fail('Password must ' . implode(', ', $errors) . '.');
                    }
                },
            ],
            'password_confirmation' => ['required', 'same:user_password'], // Confirm password must match user_password
        ], [
            'user_first_name.required' => 'The First Name field is required.',
            'user_agencies_name.required' => 'The Agencies Name field is required.',
            'user_iata_clia_number.required' => 'The IATA or CLIA Number field is required.',
            'user_email.required' => 'The Business Email field is required.',
            'user_email.email' => 'The Business Email must be a valid email address.',
            'user_email.unique' => 'The Business Email has already been taken.',
            'user_password.required' => 'The Password field is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
            'user_business_phone.required' => 'The Business Phone field is required.',
            'user_image.regex' => 'Please enter a valid email address.',
            'user_personal_email.regex' => 'Please enter a valid email address.',

        ]);



       
        $plan = Plan::where('sp_id', $plan_id)->firstOrFail();
        

        $startDate = Carbon::now();
        // if($period == 'monthly'){
        //     $months = 1;
        //     $price_stripe_id = $plan->stripe_id ?? '';
        // }else if($period == 'yearly'){
        //     $months = 12;
        //     $price_stripe_id = $plan->stripe_id_yr ?? '';
        // }else{
        //     $months = '0';
        //     $price_stripe_id = '';
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
        
        // dd($price_stripe_id);
        $expirationDate = $startDate->addMonths($months);
        $expiryDate = $expirationDate->toDateString();
        $uniqueId = $this->GenerateUniqueRandomString('buss_master_users', 'id', 6);  
              //dd($id);
        // $users_image = '';
        // if ($request->hasFile('image')) {
        //     // Handle the image upload and check the result
        //     $users_image = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/profile_image');
            
        //     // Debug output
        //     //dd($users_image); // This will output the image path and stop execution for debugging purposes
        // }
        $admin = MasterUser::create([
            'id' => $uniqueId,
            'user_agencies_name' => $request->user_agencies_name,
            'user_franchise_name' => $request->user_franchise_name,
            'user_consortia_name' => $request->user_consortia_name,
            'user_first_name' => $request->user_first_name,
            'user_last_name' => $request->user_last_name,
            'user_iata_clia_number' => $request->user_iata_clia_number,
            'user_email' => $request->user_email,
            'user_personal_email' => $request->user_personal_email,
            'user_business_phone' => $request->user_business_phone,
            'user_personal_phone' => $request->user_personal_phone,
            'user_clia_number' => $request->user_clia_number,
            'user_iata_number' => $request->user_iata_number,
            'user_address' => $request->user_address,
            'user_country' => $request->user_country,
            'user_state' => $request->user_state,
            'user_image' => '',
            'buss_unique_id' => '',
            'sp_id' => '',
            'user_password' => Hash::make($request->user_password),
            'sp_expiry_date' => '',
            'user_city' => $request->user_city,
            'user_zip' => $request->user_zip,
            'isActive' => '0'
        ]);
      
    
        //dd($admin);
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $admin->id );
        //dd($buss_unique_id);
        // Set buss_unique_id and updated_at fields
        $admin->buss_unique_id = strtolower($buss_unique_id);
        $admin->updated_at = now();  // Or \Carbon\Carbon::now() for the current timestamp

        //create own image floder 
        $userFolder = 'masteradmin/' .strtolower($buss_unique_id).'_'.$request->input('user_first_name');
        Storage::makeDirectory($userFolder, 0755, true);

        $users_image = '';
        if ($request->hasFile('image')) {
            // Handle the image upload and check the result
            $users_image =  $this->handleImageUpload($request, 'image', null, 'profile_image', $userFolder);

            // Debug output
            //dd($users_image); // This will output the image path and stop execution for debugging purposes
        }

        $admin->user_image = $users_image;

        // Save the updated values to the database
        $admin->save();

        // Uncomment this line if you want to log in the user after registration

        // dd($admin->id);
        
        $this->createTable($admin->id);

       // Create MasterUserDetails and set table name
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId(strtolower($buss_unique_id));
        $tableName = $userDetails->getTable();
        // dd($userDetails->getTable());
        $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);

        $userDetails->create([
            'users_agencies_name' => $request->user_agencies_name,
            'users_franchise_name' => $request->user_franchise_name,
            'users_consortia_name' => $request->user_consortia_name,
            'users_first_name' => $request->user_first_name,
            'users_last_name' => $request->user_last_name,
            'users_iata_clia_number' => $request->user_iata_clia_number,
            'users_email' => $request->user_email,
            'users_personal_email' => $request->user_personal_email,
            'users_business_phone' => $request->user_business_phone,
            'users_personal_phone' => $request->user_personal_phone,
            'users_clia_number' => $request->user_clia_number,
            'users_iata_number' => $request->user_iata_number,
            'users_address' => $request->user_address,
            'users_state' => $request->user_state,
            'users_country' => $request->user_country,
            'users_city' => $request->user_city,
            'users_zip' => $request->user_zip,
            'users_image' => $users_image,
            'id' => $admin->id,
            'role_id' => 0,
            'users_password' => Hash::make($request->user_password),
            'user_id' => strtolower($buss_unique_id),
            'users_status' => 1,
            'users_id' => $users_id
        ]);

        $this->assignMenuToRoles($admin->buss_unique_id,$users_id, $admin->id);

        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId(strtolower($buss_unique_id));
        $user_data = $userDetails->orderBy('created_at', 'desc')->first();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripePriceId = $price_stripe_id;
        // dd($stripePriceId);
        $quantity = 1;

        try {
          
            $stripeCustomer = \Stripe\Customer::create([
                'email' => $request->user_email,
                'name' => $request->user_first_name . ' ' . $request->user_last_name,
            ]);

            $user_data->stripe_id = $stripeCustomer->id;
            $user_data->save();
           

            $customer = Customer::create([
                'stripe_id' => $stripeCustomer->id,
                'email' => $request->user_email,
                'name' => $request->user_first_name . ' ' . $request->user_last_name,
            ]);

          

            // $Stripesubscription = \Stripe\Subscription::create([
            //     'customer' => $stripeCustomer->id,
            //     'items' => [['price' => $stripePriceId]], // Assuming $request->plan_id contains the price ID
            //     'trial_period_days' => ($request->period == 'yearly') ? 12 : null, // Trial period if needed
            // ]);

            $Stripesubscription = \Stripe\Subscription::create([
                'customer' => $stripeCustomer->id,
                'items' => [
                    ['price' => $price_stripe_id],
                ],
                'trial_period_days' => $trialPeriodDays, // Set trial period dynamically
            ]);

            $latestInvoiceId = $Stripesubscription->latest_invoice;
            // dd($Stripesubscription);

            $subscription = Subscription::create([
                'user_id' => $stripeCustomer->id, // Assuming you have a user_id to associate with the subscription
                'master_user_details_id' => $user_data->users_id, // Set this if you have a master user details ID
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
            'success_url' => route('success', [
                        'user_id' => $user_data->user_id,
                        'email' => $user_data->users_email, 
                        'stripe_id' => $subscription->stripe_id,
                        'plan_id' => $plan_id,
                        'period' => $period,
                        'latestInvoiceId' => $latestInvoiceId

                    ]),
            'cancel_url' => route('cancel'),
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
       
    }

    


    // private function generateUniqueId(string $user_business_name, string $current_id): string
    // {
    //     // Clean the business name by removing non-alphabetic characters
    //     $cleaned_name = preg_replace('/[^a-zA-Z]/', '', $user_business_name);
    
    //     // Generate the prefix from the first 4 characters of the cleaned name
    //     $prefix = strtolower(substr($cleaned_name, 0, 4));
    
    //     // Get the latest unique ID from the database that starts with the prefix
    //     $latest_unique_id = MasterUser::where('id', 'like', $prefix . '%')->max('id');
    
    //     // Handle case where no unique ID is found
    //     $latest_number = $latest_unique_id ? (int) substr($latest_unique_id, 4) : 0;
    
    //     // Combine the prefix and the new ID
    //     $uniqueId = $prefix . sprintf('%04d', $latest_number + 1); // Increment the number and format it
    
    //     return $uniqueId;
    // }
    
    

    private function generateUniqueId(string $user_business_name, string $current_id): string
    {
        // Clean the business name by removing non-alphabetic characters
        $cleaned_name = preg_replace('/[^a-zA-Z]/', '', $user_business_name);
    
        // Generate the prefix from the first 4 characters of the cleaned name
        $prefix = strtolower(substr($cleaned_name, 0, 4));
    
        // Combine the prefix and the current ID
        // Use only the first 2 characters of the current ID
        $uniqueId = $prefix . substr($current_id, 0,  4);
    
        return $uniqueId;
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
        
    // Assign roles to a user
public function assignMenuToRoles($userId,$users_id, $id)
{
    $roles = [
        'Owner & Co-Owner', 
        'Associate/IC',
        'Assistant',
        'Hybrid'
    ];
    $tableName = $userId. '_tc_users_role';

    foreach ($roles as $roleName) {
        $ageid = $this->GenerateUniqueRandomString(
            $table = $tableName, 
            $column = "role_id", 
            $chars = 6
        );

            DB::table($table)->insert([
                'id' => $id,           
                'role_id' => $ageid,   
                'role_name' => $roleName,  
                'created_at' => now(),  
                'role_status' => 1,  
            ]);

            $this->assignPermissionsToRole($userId, $ageid, $roleName, $users_id,  $id);
    }
}

protected function assignPermissionsToRole($userId, $roleId, $roleName, $users_id, $id)
{
    if($roleName == 'Owner & Co-Owner'){

        $modules = AdminMenu::where('is_deleted', 0)
        ->where('pmenu', 0) 
        ->get();

        foreach ($modules as $module) {
        $mid = $module->mid;
        $mtitle = $module->mtitle;

        // Fetch sub-permissions for this module
        $subPermissions = AdminMenu::where('is_deleted', 0)
            ->where('pmenu', $mid) 
            ->get();

        foreach ($subPermissions as $subPermission) {
            $tableName = $userId. '_tc_master_user_access';

            $uniqueId = $this->GenerateUniqueRandomString(
                $table = $tableName, 
                $column = "id", 
                $chars = 6
            );
    
            DB::table($tableName)->insert([
                'id' => $uniqueId,
                'u_id' => $id,                  
                'role_id' => $roleId,             
                'mname' => $subPermission->mname, 
                'mtitle' => $subPermission->mtitle,              
                'mid' => $subPermission->mid,                     
                'is_access' => 1          
            ]);  
            // MasterUserAccess::create([
            //     'id' => $uniqueId,
            //     'u_id' => $users_id,                  
            //     'role_id' => $roleId,             
            //     'mname' => $subPermission->mname, 
            //     'mtitle' => $mtitle,              
            //     'mid' => $mid,                     
            //     'is_access' => 1                  
            // ]);
        }
        }
    }else if($roleName == 'Associate/IC'){

        // 5, 124, 120, 115
        $modules = AdminMenu::where('is_deleted', 0)
        ->whereRaw('`ta_admin_menu`.`mid` IN (29, 124, 120, 115, 59)')
        // ->where('pmenu', 0) 
        ->get();

    foreach ($modules as $module) {
        $mid = $module->mid;
        $mtitle = $module->mtitle;

        // Fetch sub-permissions for this module
        $subPermissions = AdminMenu::where('is_deleted', 0)
            ->whereRaw('`ta_admin_menu`.`mid` IN (129, 130, 131,133, 134, 151,136,139, 116, 117, 119,123,147,148,149)')
            ->get();

        foreach ($subPermissions as $subPermission) {
            $tableName = $userId. '_tc_master_user_access';

            $uniqueId = $this->GenerateUniqueRandomString(
                $table = $tableName, 
                $column = "id", 
                $chars = 6
            );
    
            DB::table($tableName)->insert([
                'id' => $uniqueId,
                'u_id' => $id,                  
                'role_id' => $roleId,             
                'mname' => $subPermission->mname, 
                'mtitle' => $subPermission->mtitle,              
                'mid' => $subPermission->mid,                     
                'is_access' => 1          
            ]);  
            // MasterUserAccess::create([
            //     'id' => $uniqueId,
            //     'u_id' => $users_id,                  
            //     'role_id' => $roleId,             
            //     'mname' => $subPermission->mname, 
            //     'mtitle' => $mtitle,              
            //     'mid' => $mid,                     
            //     'is_access' => 1                  
            // ]);
        }
    }
    }else if($roleName == 'Assistant'){
        // 29, 125, 122, 116, 117, 118, 13, 14, 58
        $modules = AdminMenu::where('is_deleted', 0)
        ->whereRaw('`ta_admin_menu`.`mid` IN (29, 124, 120, 115, 59)')
        // ->where('pmenu', 0) 
        ->get();

    foreach ($modules as $module) {
        $mid = $module->mid;
        $mtitle = $module->mtitle;

        // Fetch sub-permissions for this module
        $subPermissions = AdminMenu::where('is_deleted', 0)
            ->whereRaw('`ta_admin_menu`.`mid` IN (139, 130, 131, 133, 134, 151, 136, 137, 138, 139, 116, 117, 118, 119, 152, 58, 56, 128,144,145,146,140,141,142,143,123,147,148,149)')
            ->get();

        foreach ($subPermissions as $subPermission) {
            $tableName = $userId. '_tc_master_user_access';

            $uniqueId = $this->GenerateUniqueRandomString(
                $table = $tableName, 
                $column = "id", 
                $chars = 6
            );
    
            DB::table($tableName)->insert([
                'id' => $uniqueId,
                'u_id' => $id,                  
                'role_id' => $roleId,             
                'mname' => $subPermission->mname, 
                'mtitle' => $subPermission->mtitle,              
                'mid' => $subPermission->mid,                     
                'is_access' => 1          
            ]);  
            // MasterUserAccess::create([
            //     'id' => $uniqueId,
            //     'u_id' => $users_id,                  
            //     'role_id' => $roleId,             
            //     'mname' => $subPermission->mname, 
            //     'mtitle' => $mtitle,              
            //     'mid' => $mid,                     
            //     'is_access' => 1                  
            // ]);
        }
    }
    }else if($roleName == 'Hybrid'){

        $modules = AdminMenu::where('is_deleted', 0)
        ->whereRaw('`ta_admin_menu`.`mid` IN (29, 124, 120, 115, 59)')
        // ->where('pmenu', 0) 
        ->get();

    foreach ($modules as $module) {
        $mid = $module->mid;
        $mtitle = $module->mtitle;

        // Fetch sub-permissions for this module
        $subPermissions = AdminMenu::where('is_deleted', 0)
            ->whereRaw('`ta_admin_menu`.`mid` IN (129,130,131,133,134,151,136,137,138,139,116,117,118,119,152,58,56,128,144,145,146,140,141,142,143,123,147,148,149)')
            ->get();

        foreach ($subPermissions as $subPermission) {
            $tableName = $userId. '_tc_master_user_access';

            $uniqueId = $this->GenerateUniqueRandomString(
                $table = $tableName, 
                $column = "id", 
                $chars = 6
            );
    
            DB::table($tableName)->insert([
                'id' => $uniqueId,
                'u_id' => $id,                  
                'role_id' => $roleId,             
                'mname' => $subPermission->mname, 
                'mtitle' => $subPermission->mtitle,              
                'mid' => $subPermission->mid,                     
                'is_access' => 1          
            ]);  
            // MasterUserAccess::create([
            //     'id' => $uniqueId,
            //     'u_id' => $users_id,                  
            //     'role_id' => $roleId,             
            //     'mname' => $subPermission->mname, 
            //     'mtitle' => $mtitle,              
            //     'mid' => $mid,                     
            //     'is_access' => 1                  
            // ]);
        }
    }
    }
   
}
}

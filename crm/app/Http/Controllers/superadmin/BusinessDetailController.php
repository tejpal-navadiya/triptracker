<?php

namespace App\Http\Controllers\superadmin;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\View\View;
use App\Models\MasterUser;
use App\Models\MasterUserDetails;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\States;
use App\Models\Countries;
use Illuminate\Validation\Rules\Password;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;
use App\Models\Cities;
use Illuminate\Support\Str; 
use App\Models\StaticAgentPhone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UsersDetails;
use Carbon\Carbon;
use App\Models\AgencyPhones;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminMenu;

class BusinessDetailController extends Controller
{
    public function index(): View
    {
        $MasterUser = MasterUser::with('plan')->orderBy('created_at', 'desc')->get();

       

        $MasterUser->each(function ($user) {

            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($user->buss_unique_id);
            $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();

            $tableName = $userDetails->getTable();

            $user->totalUserCount = $totalUserCount;

            $userdetails = $userDetails->where(['users_email' => $user->user_email, 'role_id' => '0'])->first();

            if ($userdetails) {
                $user->users_agencies_name = $userdetails->users_agencies_name ?? '';
                $user->users_first_name = $userdetails->users_first_name ?? '';
                $user->users_last_name = $userdetails->users_last_name ?? '';
                $user->users_email = $userdetails->users_email ?? '';
                $user->users_iata_number = $userdetails->users_iata_number ?? '';
            } else {
                $user->users_agencies_name = '';
                $user->users_first_name = '';
                $user->users_last_name = '';
                $user->users_email = '';
                $user->users_iata_number = '';
            }

        });
        
        //  dd($MasterUser);
        return view('superadmin.businessdetails.index')->with('MasterUser', $MasterUser);
    }

    public function show($id)
    {
        $user = MasterUser::findOrFail($id);

        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);

        $udetail = $userDetails
        ->where('users_email', '!=', $user->user_email) 
        ->get(); 

        $user_id = $id;

        $userdetailss = $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->first();
        if ($userdetailss) {

            $countries = DB::table('ta_countries')->where('id', $userdetailss->users_country)->first();
            $state = DB::table('ta_states')->where('id', $userdetailss->users_state)->first();
            $city = DB::table('ta_cities')->where('id', $userdetailss->users_city)->first();

            $userdetailss->country_name = $countries ? $countries->name : '';
            $userdetailss->state_name = $state ? $state->name : '';
            $userdetailss->city_name = $city ? $city->name : '';
            
        }

       // dd($udetail);

        $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;

        $tableName = $user->buss_unique_id . '_tc_users_role';

        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();

            $countries = DB::table('ta_countries')->where('id', $detail->users_country)->first();
            $state = DB::table('ta_states')->where('id', $detail->users_state)->first();
            $city = DB::table('ta_cities')->where('id', $detail->users_city)->first();

            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';

            $detail->country_name = $countries ? $countries->name : '';
            $detail->state_name = $state ? $state->name : '';
            $detail->city_name = $city ? $city->name : '';
            
        }
        //dd($udetail);
        return view('superadmin.businessdetails.view_business', compact('user', 'udetail','userdetailss','user_id'));
    }
    public function updateStatus($id)
    {
        $business = MasterUser::findOrFail($id);

        $business->user_status = $business->user_status == 1 ? 0 : 1;
        $business->save();

        return response()->json(['status' => 'success', 'new_status' => $business->user_status]);
    }


       public function edit($id) {

        $user = MasterUser::findOrFail($id);
       
        $userDetailss = new MasterUserDetails();
        $userDetailss->setTableForUniqueId($user->buss_unique_id);
    
        $udetail = $userDetailss->where('users_email', '!=', $user->user_email)->get();

        $userdetails = $userDetailss->where(['users_email' => $user->user_email, 'role_id' => 0])->firstOrFail();
        // dd($userdetails);
        $country = Countries::all();
    
        $selectedCountryId = $userdetails->users_country;

        $states = States::where('country_id', $selectedCountryId)->orderBy('name', 'ASC')->get();
    
        $selectedStateId = $userdetails->users_state;
    
        $cities = Cities::where('state_id', $selectedStateId)->orderBy('name', 'ASC')->get();

    
        $totalUserCount = $userDetailss->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;
    
        $tableName = $user->buss_unique_id . '_tc_users_role';
        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();
            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';
        }
    
        return view('superadmin.businessdetails.edit', compact('user', 'udetail', 'states', 'country', 'cities','userdetails'));
    }
         
public function update(Request $request, $id)
{
    //dd($request->all());
    $user = MasterUser::findOrFail($id);

    $userDetails = new MasterUserDetails();
    $userDetails->setTableForUniqueId($user->buss_unique_id);

    $userdetails = $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->firstOrFail();
    //dd($userdetails);
    $validatedData = $request->validate([
        'users_agencies_name' => 'required|string',
        'users_first_name' => 'required|string',
        'users_last_name' => 'required', 'string', 'max:255',
        'users_franchise_name' => 'nullable|string',
        'users_consortia_name' => 'nullable|string',
        'users_iata_clia_number' => 'required|string',
        'users_iata_number' => 'nullable|string',
        'users_clia_number' => 'nullable|string',
        'users_address' => 'nullable|string',
        'users_business_phone' => 'required|string',
        'users_personal_phone' => 'nullable|string',
        'users_personal_email' => 'nullable|string',  
        'users_zip' => 'nullable', 'string', 'max:255',
        'users_country' => 'nullable',
        'users_state' => 'nullable',
        'users_city' => 'nullable',

        'agency_logo' => 'nullable',
        'agency_logo.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',

    ], [
        'users_agencies_name.required' => 'Agency name is required',
        'users_first_name.required' => 'First Name is required',
        'users_iata_clia_number.required' => 'IATA CLIA Number is required',
        'users_business_phone.required' => 'Business Phone is required',

        'users_email.email' => 'Please enter a valid email address.',
        'users_email.regex' => 'Email contains invalid characters or format.',
        'users_email.max' => 'Email should not exceed 254 characters.',

        'agency_logo.image' => 'Uploaded file must be an image.',
        'agency_logo.mimes' => 'Image must be a file of type: jpeg, png, jpg, pdf.',
        'agency_logo.max' => 'Image size must not exceed 2MB.',
    ]);
    // dd($validatedData);
    

    $userdetails->updated_at = now(); 

    // dd($validatedData);
      $userFolder = 'masteradmin/' .$user->buss_unique_id.'_'.$user->user_first_name;
      Storage::makeDirectory($userFolder, 0755, true);

      $agency_logo = '';

        if ($request->hasFile('agency_logo')) {
            $agency_logo =  $this->handleImageUpload($request, 'agency_logo', null, 'profile_image', $userFolder);
            $validatedData['agency_logo'] = $agency_logo;
        }else{
            $validatedData['agency_logo'] = $userdetails->agency_logo ?? '';
        }


        $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->update($validatedData);

        return redirect()->route('businessdetails.index')->with('success', 'Admin Agency Updated successfully.');
 }
 
    public function agencyCreate($id)
    {
        $user = MasterUser::findOrFail($id);
        // dd($user);
        $user_id = $id;
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);
        $userDetails = $userDetails->count();
        
        $nextAgencyNumber = str_pad($userDetails + 1, 3, '0', STR_PAD_LEFT); // Auto-increment logic
        
         $tableName = $user->buss_unique_id . '_tc_users_role';
         $users_role = DB::table($tableName)->get();
        // dd($role);
         $country = Countries::all();
          $phones_type = StaticAgentPhone::all();

        
         return view('superadmin.businessdetails.create',compact('nextAgencyNumber','users_role','country','phones_type','user_id'));
    }

    public function agencyStore($id,Request $request)
    {
       // dd($request->all());
        $user = MasterUser::findOrFail($id);
        
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
    $agency->setTableForUniqueId($user->buss_unique_id);
    $tableName = $agency->getTable();
    
    $users_id = $this->GenerateUniqueRandomString($table= $tableName, $column="users_id", $chars=6);

    //dd($agency);

        $agency->users_id = $users_id;
        $agency->id = $id;


    $existingAgency = $agency->where('users_email', $validatedData['users_email'])->first();

    if ($existingAgency) {
        return redirect()->back()->withErrors(['users_email' => 'The email address is already in use.'])->withInput();
    }

    // $existingWorkemail = $agency->where('user_work_email', $validatedData['user_work_email'])->first();

    // if ($existingWorkemail) {
    //     return redirect()->back()->withErrors(['user_work_email' => 'The email address is already in use.'])->withInput();
    // }


        $plan = Plan::where('sp_id', $user->sp_id)->firstOrFail();
        $startDate = Carbon::now();
        $months = ($user->plan_type == 'monthly') ? 1 : (($user->plan_type == 'yearly') ? 12 : 0);
        $expiryDate = $startDate->addMonths($months)->toDateString();
        
        
        $agency->user_id = $user->buss_unique_id;   

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
      $agency->stripe_status = 'active';  
      $agency->plan_id = $user->sp_id;
      $agency->stripe_id = '';
      $agency->start_date = $startDate->toDateString();
      $agency->end_date = $expiryDate;
      $agency->plan_type = $user->plan_type;
      $agency->users_status = 1;
      $agency->save();

      
    $rawItems = $request->input('items', []);   


      
    //     $travelerItem = $user->buss_unique_id . '_tc_users_agency_phone';
    //      $tableName = $travelerItem->getTable();

    //     //   $tableName = $travelerItem->getTable();
    //       $ageid = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_user_phone_id", $chars = 6);
      
    //         // Assign the generated unique ID
    //         $travelerItem->age_id = $agency->users_id;
    //         $travelerItem->id = $id;
    //         $travelerItem->age_user_phone_id = $ageid;

    //       $travelerItem->fill($item);

    //       $travelerItem->save();
          
          
    //   }
      
      foreach ($rawItems as $item) {
    if (empty($item) || !is_array($item)) {
        continue;  // Skip if the item is empty or not an array
    }

    // Step 1: Define the table name dynamically
    $tableName = $user->buss_unique_id . '_tc_users_agency_phone';

    // Step 2: Generate a unique random string for the column `age_user_phone_id`
    $ageid = $this->GenerateUniqueRandomString(
        $table = $tableName, 
        $column = "age_user_phone_id", 
        $chars = 6
    );

    // Step 3: Prepare the data to be inserted
    $data = [
        'age_user_phone_id' => $ageid,           // Generated unique phone ID
        'age_id' => $agency->users_id,           // Assuming $agency is defined
        'id' => $id,                             // Assuming $id is defined
        'age_user_phone_number' => $item['age_user_phone_number'] ?? null, // Assuming this comes from the $item
        'age_user_type' => $item['age_user_type'] ?? null,               // Assuming this comes from the $item
    ];

    // Step 4: Merge any additional data from `$item` (assuming `$item` is an array)
    if (isset($item) && is_array($item)) {
        $data = array_merge($data, $item);
    }

    // Step 5: Insert the data into the dynamically defined table
    DB::table($tableName)->insert($data);
      }

      $loginUrl = route('masteradmin.userdetail.changePassword', ['email' => $request->users_email, 'user_id' => $user->buss_unique_id]);
        try {
            Mail::to($request->users_email)->send(new UsersDetails($user->buss_unique_id, $loginUrl, $request->users_email));
            session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
        } catch (\Exception $e) {
            session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
        }

      return redirect()->route('businessdetails.show', $id)->with('success', 'Agecy User entry created successfully.');

      \LogActivity::addToLog('Admin is created the Users.');

        
    }
    
     public function changePassword(Request $request): View
    {
        $email = $request->query('email');
        $user_id = $request->query('user_id');
        return view('masteradmin.agency.change-password', ['email' => $email ,'user_id' => $user_id ]);
    }
    
    public function agencyAdd()
    {
         $states = States::get();
        $plan = Plan::get();
        $country = Countries::all();

        return view('superadmin.businessdetails.create-agency',compact('country','states','plan'));
    }
    
        public function agencyInsert(Request $request)
    {
      
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
            'sp_id' => ['required', 'string', 'max:255'],
            'plan_type' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.MasterUser::class],
            'user_password' => [
                'required',
                'string',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(), // Custom password rules
            ],
            'password_confirmation' => ['required', 'same:user_password'], // Confirm password must match user_password
        ], [
            'user_first_name.required' => 'The First Name field is required.',
            'user_agencies_name.required' => 'The Agencies Name field is required.',
            'user_iata_clia_number.required' => 'The IATA or CLIA Number field is required.',
            'user_email.required' => 'The Email field is required.',
            'user_email.email' => 'The Email must be a valid email address.',
            'user_email.unique' => 'The Email has already been taken.',
            'user_password.required' => 'The Password field is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
            'sp_id.required' => 'The plan field is required.',
            'plan_type.required' => 'The plan type field is required.',
        ]);



       
        $plan = Plan::where('sp_id', $request->sp_id)->firstOrFail();
        
    
            // Calculate expiration date
            $startDate = Carbon::now();
            $startDateFormatted = $startDate->toDateString();
            
             
            $period = $request->plan_type;
            if($period == 'monthly'){
                $months = 1;
                $price_stripe_id = $plan->stripe_id ?? '';
            }else if($period == 'yearly'){
                $months = 12;
                $price_stripe_id = $plan->stripe_id_yr ?? '';
            }else{
                $months = '';
                $price_stripe_id = '';
                // $period = 'yearly';
            }
            
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
            'user_clia_number' => $request->user_clia_number,
            'user_iata_number' => $request->user_iata_number,
            'user_address' => $request->user_address,
            'user_country' => $request->user_country,
            'user_state' => $request->user_state,
            'user_image' => '',
            'buss_unique_id' => '',
            'sp_id' => $request->sp_id,
            'plan_type' => $period,
            'user_password' => Hash::make($request->user_password),
            'start_date' => $startDateFormatted,
            'sp_expiry_date' => $expiryDate,
            'user_city' => $request->user_city,
            'stripe_id' => '',
            'user_zip' => $request->user_zip,
            'subscription_status' => 'active',
            'user_status' => 1,
            'isActive' => 1
        ]);
        
       // dD($admin );
        
        //dd($admin);
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $admin->id );
        //dd($buss_unique_id);
        // Set buss_unique_id and updated_at fields
        $admin->buss_unique_id = strtolower($buss_unique_id);
        $admin->updated_at = now();  // Or \Carbon\Carbon::now() for the current timestamp

        //create own image floder 
        $userFolder = 'masteradmin/' .$buss_unique_id.'_'.$request->input('user_first_name');
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
            'users_id' => $users_id,
            'stripe_status' => 'active',
            'plan_id' => $request->sp_id,
            'stripe_id' => '',
            'start_date' => $startDateFormatted,
            'end_date' => $expiryDate,
            'plan_type' => $period,
        ]);
        
        // dd($admin->id);
        $this->assignMenuToRoles($admin->buss_unique_id,$users_id, $admin->id);

        // login URL
        $loginUrl = route('masteradmin.login');

        try {
            Mail::to($request->user_email)->send(new UserRegistered($buss_unique_id, $loginUrl, $request->user_email,$invoiceUrl=''));

           session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
        } catch (\Exception $e) {
              session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
        }
    
        return redirect()->route('businessdetails.index')->with('success', 'Agecy entry created successfully.');
    }
    

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
    
     public function destroy($id,$storeId)
 {
    // dd($id, $storeId);

     $masterUser = MasterUser::where('id', $id)->where('buss_unique_id', $storeId)->first();
 
     if ($masterUser) {
         $masterUser->delete();
 
         $tables = DB::select('SHOW TABLES');
 
         $prefix = $storeId . '_';
 
         foreach ($tables as $table) {
             $tableName = reset($table); 
             if (str_starts_with($tableName, $prefix)) {
                 Schema::dropIfExists($tableName);
                 echo "Dropped table: $tableName\n"; 
             }
         }
         return redirect()->route('businessdetails.index')->with('success', 'Agency  with all associated tables deleted successfully.');
     } else {
         return response()->json(['message' => 'MasterUser not found for the provided storeId.'], 404);
     }
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
            ->whereRaw('`ta_admin_menu`.`mid` IN (129, 130, 131,133, 134, 151,136,139, 116, 117, 119)')
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
            ->whereRaw('`ta_admin_menu`.`mid` IN (139, 130, 131, 133, 134, 151, 136, 137, 138, 139, 116, 117, 118, 119, 152, 58, 56)')
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
            ->whereRaw('`ta_admin_menu`.`mid` IN (129,130,131,133,134,151,136,137,138,139,116,117,118,119,152,58,56)')
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

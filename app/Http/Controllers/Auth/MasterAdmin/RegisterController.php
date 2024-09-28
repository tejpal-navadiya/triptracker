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


class RegisterController extends Controller
{
    //
    public function create(): View
    {     
        $states = States::get();
        $plan = Plan::get();
        
        return view('masteradmin.auth.register',compact('states','plan'));
    }

    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
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
            'user_state' => ['nullable', 'string', 'max:255'],
            'user_city' => ['nullable', 'string', 'max:255'],
            'user_zip' => ['nullable', 'string', 'max:255'],
            'sp_id' => ['nullable', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.MasterUser::class],
            'user_password' => ['required', 'string', '', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ],[
            'user_first_name.required' => 'The First Name field is required.',
            'user_agencies_name.required' => 'The Agencies Name field is required.',
            'user_iata_clia_number.required' => 'The IATA or CLIA Number field is required.',
            'user_email.required' => 'The Email field is required.',
            'user_email.email' => 'The Email must be a valid email address.',
            'user_email.unique' => 'The Email has already been taken.',
            'user_password.required' => 'The Password field is required.',
        ]);


       
        $plan = Plan::where('sp_id', $request->sp_id)->firstOrFail();

        $startDate = Carbon::now();
        $months = $plan->sp_month;
        $expirationDate = $startDate->addMonths($months);
        $expiryDate = $expirationDate->toDateString();
        $uniqueId = $this->GenerateUniqueRandomString('buss_master_users', 'id', 6);  
              //dd($id);
        $users_image = '';
        if ($request->hasFile('image')) {
            // Handle the image upload and check the result
            $users_image = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/profile_image');
            
            // Debug output
            //dd($users_image); // This will output the image path and stop execution for debugging purposes
        }
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
            'user_state' => $request->user_state,
            'user_image' => $users_image,
            'buss_unique_id' => '',
            'sp_id' => $request->sp_id,
            'user_password' => Hash::make($request->user_password),
            'sp_expiry_date' => $expiryDate,
            'user_city' => $request->user_city,
            'user_zip' => $request->user_zip,
            'isActive' => 1
        ]);
      
    
        //dd($admin);
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $admin->id );
        //dd($buss_unique_id);
        // Set buss_unique_id and updated_at fields
        $admin->buss_unique_id = $buss_unique_id;
        $admin->updated_at = now();  // Or \Carbon\Carbon::now() for the current timestamp

        // Save the updated values to the database
        $admin->save();

        // Uncomment this line if you want to log in the user after registration
        
        $this->createTable($admin->id);

       // Create MasterUserDetails and set table name
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($buss_unique_id);
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
            'users_city' => $request->user_city,
            'users_zip' => $request->user_zip,
            'users_image' => $users_image,
            'id' => $admin->id,
            'role_id' => 0,
            'users_password' => Hash::make($request->user_password),
            'user_id' => $buss_unique_id,
            'users_status' => '1',
            'users_id' => $users_id
        ]);


        // login URL
        $loginUrl = route('masteradmin.login');

        try {
            Mail::to($request->user_email)->send(new UserRegistered($buss_unique_id, $loginUrl, $request->user_email));

            return back()->with(['link-success' => __('messages.masteradmin.register.link_send_success')]);
        } catch (\Exception $e) {
            return back()->with(['link-error' => __('messages.masteradmin.register.link_send_error')]);
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
        $uniqueId = $prefix . substr($current_id, 0, length: 4);
    
        return $uniqueId;
    }
    

    
}

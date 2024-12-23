<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUser;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserRegistered;
use App\Models\MasterUserDetails;
use Illuminate\Http\JsonResponse;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPasswordMail;
use App\Models\CustomPersonalAccessToken;


class AuthController extends Controller
{
    //
    use ApiResponse;

    public function register(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_agencies_name' => ['required', 'string', 'max:255'],
            'user_franchise_name' => ['nullable', 'string', 'max:255'],
            'user_consortia_name' => ['nullable', 'string', 'max:255'],
            'user_first_name' => ['required', 'string', 'max:255'],
            'user_last_name' => ['nullable', 'string', 'max:255'],
            'user_iata_clia_number' => ['required', 'string', 'max:255'],
            'user_clia_number' => ['nullable', 'string', 'max:255'],
            'user_iata_number' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'user_address' => ['nullable', 'string', 'max:255'],
            'user_country' => ['nullable', 'string', 'max:255'],
            'user_state' => ['nullable', 'string', 'max:255'],
            'user_city' => ['nullable', 'string', 'max:255'],
            'user_zip' => ['nullable', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:'.MasterUser::class],
            'user_personal_email' => ['nullable', 'string', 'lowercase', 'email' ,'max:255', 'unique:'.MasterUser::class],
            'user_business_phone' => ['required', 'string', 'max:255'],
            'user_personal_phone' => ['nullable', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . MasterUser::class],
            // 'user_password' => ['required', 'string', '', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
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
        ], [
            // Custom error messages
             'user_first_name.required' => 'The First Name field is required.',
            'user_agencies_name.required' => 'The Agencies Name field is required.',
            'user_iata_clia_number.required' => 'The IATA or CLIA Number field is required.',
            'user_email.required' => 'The Business Email field is required.',
            'user_email.email' => 'The Business Email must be a valid email address.',
            'user_email.unique' => 'The Business Email has already been taken.',
            // 'user_password.required' => 'The Password field is required.',
            // 'password_confirmation.same' => 'The password confirmation does not match.',
            'user_business_phone.required' => 'The Business Phone field is required.',
            'user_image.regex' => 'Please enter a valid email address.',
            'user_personal_email.regex' => 'Please enter a valid email address.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.api.user.invalid'),
                'errors' => $validator->errors()
            ], 500);
        }

        $plan = Plan::where('sp_id', $request->sp_id)->firstOrFail();

        $startDate = Carbon::now();
        $months = $plan->sp_month;
        $expirationDate = $startDate->addMonths($months);
        $expiryDate = $expirationDate->toDateString();
        $uniqueId = $this->GenerateUniqueRandomString('buss_master_users', 'id', 6);  
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
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $admin->id );
        $admin->buss_unique_id = strtolower($buss_unique_id);
        $admin->updated_at = now();
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

        $admin->save();

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

        $loginUrl = route('masteradmin.login');

        
            Mail::to($request->user_email)->send(new UserRegistered($buss_unique_id, $loginUrl, $request->user_email));
            // $success['token'] =  $admin->createToken('MyApp')->plainTextToken;
            $success['name'] =  $admin->user_first_name.' '.$admin->user_last_name;

            // return $this->sendResponse($success, 'User register successfully.');
            return $this->sendResponse($success, __('messages.api.user.register_success'));


        

    }

    private function generateUniqueId(string $user_business_name, string $current_id): string
    {
        // Clean the business name by removing non-alphabetic characters
        $cleaned_name = preg_replace('/[^a-zA-Z]/', '', $user_business_name);
    
        // Generate the prefix from the first 4 characters of the cleaned name
        $prefix = strtolower(substr($cleaned_name, 0, 4));
    
        // Combine the prefix and the current ID
        // Use only the first 2 characters of the current ID
        $uniqueId = $prefix . substr($current_id, 0, 4);
    
        return $uniqueId;
    }

    public function login(Request $request)
    {
        // Validate the input fields
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|string|email',
            'user_password' => 'required|string|min:8',
            'user_id' => 'required|string',
        ], [
            'user_email.required' => 'The email field is required.',
            'user_email.string' => 'The email must be a string.',
            'user_email.email' => 'Please provide a valid email address.',
            
            'user_password.required' => 'The password field is required.',
            'user_password.string' => 'The password must be a string.',
            'user_password.min' => 'The password must be at least 8 characters long.',
            
            'user_id.required' => 'The user ID is required.',
            'user_id.string' => 'The user ID must be a string.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.api.user.invalid'),
                'errors' => $validator->errors()
            ], 500);
        }
    
        
        $masterUser = MasterUser::where('buss_unique_id', $request->user_id)->first();

        if (!$masterUser) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user ID. No matching user found.',
            ], 404);
        }


        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($masterUser->buss_unique_id);

        $userDetailRecord = $userDetails->where(['user_id'=> $request->user_id, 'users_email' => $request->user_email])->first();
        
        if ($userDetailRecord && Hash::check($request->user_password, $userDetailRecord->users_password)) {

            $tokenResult = $userDetailRecord->createToken('MasterUserToken');
            $token = $tokenResult->plainTextToken; 

            $tokenEntry = $tokenResult->accessToken; 
            $tokenEntry->tokenable_id = $userDetailRecord->users_id; 
            $tokenEntry->tokenable_type = MasterUserDetails::class;
            $tokenEntry->save();  

            // $newApiToken = bin2hex(random_bytes(30)); // Generate a new random token
           
            $userDetailRecord->where(['user_id'=> $request->user_id, 'users_email' => $request->user_email])
                ->update(['api_token' => $token]);
            // $userDetailRecord->save();

            // $userDetailRecord->api_token = $newApiToken;
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($masterUser->buss_unique_id);
    
            $userDetailRecord = $userDetails->where(['user_id'=> $request->user_id, 'users_email' => $request->user_email])->first();
            
            $this->createTable($userDetailRecord->user_id);

            // $userDetailRecord->Authorization = $token;

            // $userDetailRecord->Authorization = $token;

                // return response()->json([
                //     'message' => 'Login successfully',
                //     'data' => $userDetailRecord,
                // ], 200);
                
                
            return $this->sendResponse($userDetailRecord, __('messages.api.user.login_success'));

            }

        return response()->json(['error' => 'Unauthorized: Invalid credentials'], 401);
    }
    
   
    public function GetUserProfile(Request $request)
    {

        try {
            $user = $request->attributes->get('authenticated_user');
      
            if (!$user) {
                return $this->sendError(__('messages.api.user.not_found'), config('global.null_object'), 404, false);
            }

            $country = Countries::where('id', $user->users_country)->first();
            $state = States::where('id', $user->users_state)->first();
            $city = Cities::where('id', $user->users_city)->first();
            $auth_user = MasterUser::where('id', $user->id)->first();
            $plan = Plan::where('sp_id', $auth_user->sp_id)->first();

            // Populate user details
            $user->country = $country ? $country->name : null; 
            $user->state = $state ? $state->name : null; 
            $user->city = $city ? $city->name : null; 
            $user->sp_id = $auth_user->sp_id; 
            $user->plan = $plan ? $plan->sp_name : null; 
            $user->sp_expiry_date = $auth_user->sp_expiry_date ? Carbon::parse($auth_user->sp_expiry_date)->format('M d, Y') : null; 
            $user->isActive = $auth_user->isActive; 
            $user->user_first_name = $auth_user->user_first_name;

            $user->token = $request->bearerToken();

            $user_data = $this->UserResponse($user);
            
            return $this->sendResponse($user_data, __('messages.api.user.user_get_profile_success'));
        } catch (\Exception $e) {
            $user = $request->attributes->get('authenticated_user');
            $this->serviceLogError('GetUserProfile', $user->users_id ?? null, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }
    }


    public function Logout(Request $request)
    {
        // echo 'in';die;
        try
        {
            $user = $request->attributes->get('authenticated_user');
            $uniqueId = $request->header('X-UniqueId');
            // dd($uniqueId);
            if ($user) {
                $currentToken = $user->currentAccessToken();
                
                $userDetails = new MasterUserDetails();
                $userDetails->setTableForUniqueId($uniqueId);
        
                $updateResult = $userDetails->where('user_id', $uniqueId)
                ->update(['api_token' => null]); 

            //  dd($currentToken);
          
                return $this->sendResponse(config('global.null_object'), __('messages.api.logout'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.user.user_not_found'), config('global.null_object'),404,false);
            }
        }
        catch(\Exception $e)
        {
            $user = $request->attributes->get('authenticated_user');
            $this->serviceLogError($service_name = 'Logout',$user_id = $user->users_id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getAllCountry(Request $request) 
    {
        try
        {
            $page = $request->input('page');
            $perPage = env('PER_PAGE', 10); // Default to 10 if not set

            $countries = Countries::orderBy('id', 'desc')->paginate($perPage);

            if ($countries->isEmpty()) {
                return $this->sendError('No countries found.', [], 404);
            }

            $response = [
                'total_records' => $countries->total(),
                'per_page' => $countries->perPage(),
                'current_page' => $countries->currentPage(),
                'total_page' => $countries->lastPage(),
                'data' => $countries->items(),
            ];

            return $this->sendResponse($response, __('messages.api.country.country_get_success'));          
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError('getAllCountry', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function getState(Request $request) 
    {
        try
        {
            $page = $request->input('page');
            $country = $request->input('country_id');
            $perPage = env('PER_PAGE', 10); // Default to 10 if not set

            $state = States::where('country_id',$country)->orderBy('id', 'desc')->paginate($perPage);

            if ($state->isEmpty()) {
                return $this->sendError('No state found.', [], 404);
            }

            $response = [
                'total_records' => $state->total(),
                'per_page' => $state->perPage(),
                'current_page' => $state->currentPage(),
                'total_page' => $state->lastPage(),
                'data' => $state->items(),
            ];

            return $this->sendResponse($response, __('messages.api.state.state_get_success'));          
        }
        catch(\Exception $e)
        {
            $this->serviceLogError('getState', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function getCity(Request $request) 
    {
        try
        {
            $page = $request->input('page');
            $country = $request->input('country_id');
            $state = $request->input('state_id');
            $perPage = env('PER_PAGE', 10); // Default to 10 if not set

            $city = Cities::where(['country_id' => $country, 'state_id' => $state])->orderBy('id', 'desc')->paginate($perPage);

            if ($city->isEmpty()) {
                return $this->sendError('No city found.', [], 404);
            }

            $response = [
                'total_records' => $city->total(),
                'per_page' => $city->perPage(),
                'current_page' => $city->currentPage(),
                'total_page' => $city->lastPage(),
                'data' => $city->items(),
            ];

            return $this->sendResponse($response, __('messages.api.city.city_get_success'));          
        }
        catch(\Exception $e)
        {
            $this->serviceLogError('getCity', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function getPlan(Request $request) 
    {
        try
        {
            $page = $request->input('page');
            $perPage = env('PER_PAGE', 10); // Default to 10 if not set

            $plan = Plan::orderBy('created_at', 'asc')->paginate($perPage);

            if ($plan->isEmpty()) {
                return $this->sendError('No Subscription Plans found.', [], 404);
            }

            $response = [
                'total_records' => $plan->total(),
                'per_page' => $plan->perPage(),
                'current_page' => $plan->currentPage(),
                'total_page' => $plan->lastPage(),
                'data' => $plan->items(),
            ];

            return $this->sendResponse($response, __('messages.api.subscription_plans.plan_get_success'));          
        }
        catch(\Exception $e)
        {
            $this->serviceLogError('getPlan', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function forgotPassword(Request $request) 
    {
        try
        {
            $request->validate([
                'user_email' => 'required|string|email|max:255',
            ]);

            $email = $request->input('user_email');
            $token = Str::random(64);
            $existingToken = DB::table('master_password_reset_tokens')->where('email', $email)->first();

            if ($existingToken) {
                // Update the existing record
                DB::table('master_password_reset_tokens')
                    ->where('email', $email)
                    ->update([
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
            } else {
                // Insert a new record
                DB::table('master_password_reset_tokens')->insert([
                    'email' => $email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }

            $users = MasterUser::where('user_email', $email)->first();

            if (!$users) {
                return $this->sendError('User Email  not found.', config('global.null_object'), 404);
            }

            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($users->buss_unique_id);
            $user = $userDetails->where('users_email', $email)->first();

            try {
                Mail::to($email)->send(new ResetPasswordMail($token, $email));
                return $this->sendResponse([], __('messages.masteradmin.forgot-password.link_send_success'));     
            } catch (\Exception $e) {
                return $this->sendError(__('messages.masteradmin.forgot-password.link_send_error'), config('global.null_object'),401,false);
            }


                 
        }
        catch(\Exception $e)
        {
            $this->serviceLogError('getPlan', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function updateUserProfile(Request $request)
    {
        try
        {
            $auth_user = $request->attributes->get('authenticated_user');
            $uniqueId = $request->header('X-UniqueId');
            $token = $request->bearerToken();
            $user = MasterUser::where('id', $auth_user->id)->first();
            
           // dd($user->user_first_name);
            if($auth_user)
            {
                
                $input = $request->all();
   
                $validator = Validator::make($request->all(), [
                    'users_first_name' => ['nullable', 'string', 'max:255'],
                    'users_last_name' => ['nullable', 'string', 'max:255'],
                    'users_email' => ['nullable', 'string', 'max:255'],
                    'users_phone' => ['nullable', 'string', 'max:255'],
                    'users_bio' => ['nullable', 'string', 'max:255']
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.api.user.invalid'),
                        'errors' => $validator->errors()
                    ], 500);
                }
            
                $userDetails = new MasterUserDetails();
                $userDetails->setTableForUniqueId($uniqueId);
                $existingUser = $userDetails->where(['user_id' => $uniqueId, 'api_token' => $token ])->firstOrFail();
                // dd($existingUser);
                if (!$existingUser) {
                    return $this->sendError('User not found.', config('global.null_object'), 404);
                }

                $users_image = '';
                if ($request->hasFile('users_image')) {

                    $userFolder = 'masteradmin/' .$auth_user->user_id.'_'.$user->user_first_name;
                    $users_image = $this->handleImageUpload($request, 'users_image', null, 'profile_image', $userFolder);

                }

                if ($users_image) {
                    $input['users_image'] = $users_image; 
                } else {
                    $input['users_image'] = $existingUser ->users_image;
                }

                $existingUser->where(['user_id' => $uniqueId, 'api_token' => $token ])->update($input);
                $updatedUser = $userDetails->where(['user_id' => $uniqueId, 'api_token' => $token ])->firstOrFail();
             
                
                $country = Countries::where('id', $updatedUser->users_country)->first();
                $state = States::where('id', $updatedUser->users_state)->first();
                $city = Cities::where('id', $updatedUser->users_city)->first();
                $plan = Plan::where('sp_id', $user->sp_id)->first();
                
                $updatedUser->country = $country ? $country->name : null; 
                $updatedUser->state = $state ? $state->name : null; 
                $updatedUser->city = $city ? $city->name : null; 
                $updatedUser->sp_id = $user ? $user->sp_id : null; 
                $updatedUser->plan = $plan ? $plan->sp_name : null; 
                $updatedUser->sp_expiry_date = $user ? Carbon::parse($user->sp_expiry_date)->format('M d, Y') : null; 
                $updatedUser->isActive = $user ? $user->isActive : null; 
                $updatedUser->user_first_name = $user->user_first_name;

                $updatedUser->token = $request->bearerToken();
                $user_data = $this->UserResponse($updatedUser);

                //dd($userDetails->getTable());
                return $this->sendResponse($user_data, __('messages.api.user.profile_setup_success'));              
            }
            else
            {                
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = $request->attributes->get('authenticated_user');
            $this->serviceLogError($service_name = 'GetUserProfile',$user_id = $auth_user->users_id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function changePassword(Request $request)
    {
        try
        {
            $auth_user = $request->attributes->get('authenticated_user');
            $uniqueId = $request->header('X-UniqueId');
            $token = $request->bearerToken();
            $user = MasterUser::where('id', $auth_user->id)->first();

            if($auth_user)
            {
                
                    $input = $request->all();
                
                    $credentials = $request->only('old_password', 'new_password', 'confirm_password');
                    // dd($credentials);
                    $validator = \Validator::make($credentials, [
                        'old_password' => ['required', 'string'],
                        'new_password' => ['required', 'string', Password::min(8)
                            ->mixedCase()
                            ->letters()
                            ->numbers()
                            ->symbols()],
                        'confirm_password' => ['required', 'string', 'same:new_password'],
                    ], [
                        'old_password.required' => 'The old password field is required.',
                        'new_password.required' => 'The new password field is required.',
                        'confirm_password.required' => 'The confirm password field is required.',
                        'confirm_password.same' => 'The confirm password must match the new password.',
                    ]);
                
                    if ($validator->fails()) {
                        return response()->json([
                            'success' => false,
                            'message' => __('messages.api.user.invalid'),
                            'errors' => $validator->errors()
                        ], 500);
                    }
                
                    // dd($auth_user);
                
                    $userDetails = new MasterUserDetails();
                    $userDetails->setTableForUniqueId($uniqueId);
                
                    try {
                        $existingUser = $userDetails->where(['user_id' => $uniqueId, 'api_token' => $token ])->first();

                        //  dd($existingUser);
                        if (!Hash::check($request->old_password, $existingUser->users_password)) {
                            return $this->sendError('The old password is incorrect.', [], 401);
                        }
                
                        if (!$existingUser ) {
                            return $this->sendError('User not found.', config('global.null_object'), 404);
                        }
                        
                        $updateData = [
                            'users_password' => Hash::make($request->new_password),
                        ];
                
                        $userDetails->where('id', $auth_user->id)->update($updateData);

                        // dd($existingUser);
                
                        $updatedUser  = $userDetails->where(['user_id' => $uniqueId, 'api_token' => $token ])->first();
                        
                        $country = Countries::where('id', $updatedUser->users_country)->first();
                        $state = States::where('id', $updatedUser->users_state)->first();
                        $city = Cities::where('id', $updatedUser->users_city)->first();
                        $plan = Plan::where('sp_id', $user->sp_id)->first();
                        
                        $updatedUser->country = $country ? $country->name : null; 
                        $updatedUser->state = $state ? $state->name : null; 
                        $updatedUser->city = $city ? $city->name : null; 
                        $updatedUser->sp_id = $user ? $user->sp_id : null; 
                        $updatedUser->plan = $plan ? $plan->sp_name : null; 
                        $updatedUser->sp_expiry_date = $user ? Carbon::parse($user->sp_expiry_date)->format('M d, Y') : null; 
                        $updatedUser->isActive = $user ? $user->isActive : null; 
                        $updatedUser->user_first_name = $user->user_first_name;
                        $updatedUser->token = $request->bearerToken();
                        
                        $user_data = $this->UserResponse($updatedUser);
                        return $this->sendResponse($user_data, __('messages.api.user.password_change_success'));

                    } catch (\Exception $e) {
                        return $this->sendError('Error updating password.', [], 500);
                    }
            
                
            }else
            {                
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'GetUserProfile',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }    
    }
    
    
}

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

class AuthController extends Controller
{
    //
    use ApiResponse;

    public function register(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
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
            'sp_id' => ['nullable', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.MasterUser::class],
            'user_password' => ['required', 'string', '', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);
                
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
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
            'user_clia_number' => $request->user_clia_number,
            'user_iata_number' => $request->user_iata_number,
            'user_address' => $request->user_address,
            'user_country' => $request->user_country,
            'user_state' => $request->user_state,
            'user_image' => '',
            'buss_unique_id' => '',
            'sp_id' => $request->sp_id,
            'user_password' => Hash::make($request->user_password),
            'sp_expiry_date' => $expiryDate,
            'user_city' => $request->user_city,
            'user_zip' => $request->user_zip,
            'isActive' => 1
        ]);
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $admin->id );
        $admin->buss_unique_id = strtolower($buss_unique_id);
        $admin->updated_at = now();
        //create own image floder 
        $userFolder = 'masteradmin/' .$buss_unique_id.'_'.$request->input('user_first_name');
        Storage::makeDirectory($userFolder, 0755, true);

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
            'users_clia_number' => $request->user_clia_number,
            'users_iata_number' => $request->user_iata_number,
            'users_address' => $request->user_address,
            'users_state' => $request->user_state,
            'users_country' => $request->user_country,
            'users_city' => $request->user_city,
            'users_zip' => $request->user_zip,
            'users_image' => '',
            'id' => $admin->id,
            'role_id' => 0,
            'users_password' => Hash::make($request->user_password),
            'user_id' => strtolower($buss_unique_id),
            'users_status' => '1',
            'users_id' => $users_id
        ]);

        $loginUrl = route('masteradmin.login');

        
            Mail::to($request->user_email)->send(new UserRegistered($buss_unique_id, $loginUrl, $request->user_email));
            // $success['token'] =  $admin->createToken('MyApp')->plainTextToken;
            $success['name'] =  $admin->user_first_name.' '.$admin->user_last_name;

            return $this->sendResponse($success, 'User register successfully.');

        

    }

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


    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|string|email',
            'user_password' => 'required|string|min:8',
            'user_id' => 'required|string',
        ]);
    
        // Fetch the user
        $user = MasterUser::where('buss_unique_id', $request->user_id)->first();
    
        if (!$user || !Hash::check($request->user_password, $user->user_password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Create a token
        $token = $user->createToken('loginUser')->plainTextToken;
    
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function GetUserProfile(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();
                $auth_user = Auth::guard('api')->user();
                // dd($auth_user);
                
                //dd($userDetails->getTable());

               
                $userDetails = new MasterUserDetails();
                $userDetails->setTableForUniqueId($auth_user->buss_unique_id);
                $user = $userDetails->where('id', $auth_user->id)->first();

                if ($user) {
                    // Manually fetch related country, state, and city based on the foreign keys
                    $country = Countries::where('id', $user->users_country)->first();
                    $state = States::where('id', $user->users_state)->first();
                    $city = Cities::where('id', $user->users_city)->first();
                    $plan = Plan::where('sp_id', $auth_user->sp_id)->first();
                    
                    $user->country = $country ? $country->name : null; 
                    $user->state = $state ? $state->name : null; 
                    $user->city = $city ? $city->name : null; 
                    $user->sp_id = $auth_user ? $auth_user->sp_id : null; 
                    $user->plan = $plan ? $plan->sp_name : null; 
                    $user->sp_expiry_date = $auth_user ? Carbon::parse($auth_user->sp_expiry_date)->format('M d, Y') : null; 
                    $user->isActive = $auth_user ? $auth_user->isActive : null; 
                }

                $token = $request->bearerToken();
                $user->token = $token;
               
                $user_data = $this->UserResponse($user);

              
                
                return $this->sendResponse($user_data, __('messages.api.user.user_get_profile_success'));              
            }
            else
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

    public function Logout(Request $request)
    {
        // echo 'in';die;
        try
        {
            if (Auth::guard('api')->check()) {
                
                Auth::guard('api')->user()->currentAccessToken()->delete();
          
                return $this->sendResponse(config('global.null_object'), __('messages.api.logout'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.user.user_not_found'), config('global.null_object'),404,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'Logout',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public  function getAllCountry(Request $request) 
    {
        try
        {
                $input = $request->all();         
                $page           = $input['page'];   
                $countries = Countries::orderBy('id', 'desc')->get();
                $result = $this->ResponseWithPagination($page,$countries);
                return $this->sendResponse($result, __('messages.api.state.state_get_success'));          
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'StateList',$user_id = 0,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }    
    }

}

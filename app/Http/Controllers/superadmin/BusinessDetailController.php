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


class BusinessDetailController extends Controller
{
    public function index(): View
    {
        $MasterUser = MasterUser::with('plan')->get();

        //dd($MasterUser);

        $MasterUser->each(function ($user) {

            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($user->buss_unique_id);
            $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();

            $tableName = $userDetails->getTable();

            $user->totalUserCount = $totalUserCount;


        });
        // dd($MasterUser);
        return view('superadmin.businessdetails.index')->with('MasterUser', $MasterUser);
    }

    public function show($id)
    {
        $user = MasterUser::findOrFail($id);

        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);

        $udetail = $userDetails->where('users_email', '!=', $user->user_email)->get();

        //dd($udetail);

        $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;

        $tableName = $user->buss_unique_id . '_tc_users_role';

        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();
            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';
        }

        return view('superadmin.businessdetails.view_business', compact('user', 'udetail'));
    }
    public function updateStatus($id)
    {
        // Fetch the user (business) by ID
        $business = MasterUser::findOrFail($id);

        // Toggle the business status (assuming the field is 'user_status')
        $business->user_status = $business->user_status == 1 ? 0 : 1;
        $business->save();

        // Return JSON response with the updated status
        return response()->json(['status' => 'success', 'new_status' => $business->user_status]);
    }


    public function edit($id) {

        $user = MasterUser::findOrFail($id);
    
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);
    
        $udetail = $userDetails->where('users_email', '!=', $user->user_email)->get();

        $country = Countries::all();
    
        $selectedCountryId = $user->user_country;

        $states = States::where('country_id', $selectedCountryId)->get();
    
        $selectedStateId = $user->user_state;
    
        $cities = Cities::where('state_id', $selectedStateId)->get();

    
        $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;
    
        $tableName = $user->buss_unique_id . '_tc_users_role';
        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();
            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';
        }
    
        return view('superadmin.businessdetails.edit', compact('user', 'udetail', 'states', 'country', 'cities'));
    }
         
public function update(Request $request, $id)
{
    $user = Auth::guard('masteradmins')->user();
    $user = MasterUser::findOrFail($id);


    $validatedData = $request->validate([
        'user_agencies_name' => 'required|string',
        'user_first_name' => 'required|string',
        'user_last_name' => 'required', 'string', 'max:255',
        'user_franchise_name' => 'nullable|string',
        'user_consortia_name' => 'nullable|string',
        'user_iata_clia_number' => 'required|string',
        'user_iata_number' => 'nullable|string',
        'user_clia_number' => 'nullable|string',
        'user_address' => 'nullable|string',
        'user_email' => [
            'required',
            'email',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'max:254'
        ],        
        'user_zip' => 'nullable', 'string', 'max:255',
        'user_country' => 'nullable|string',
        'user_state' => 'nullable|string',
        'user_city' => 'nullable|string',

        'user_image' => 'nullable',
        'user_image.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',

    ], [
        'user_agencies_name' => 'Agency name is required',
        'user_first_name' => 'First Name is required',
        'user_iata_clia_number' => 'IATA CLIA Number is required',

        'user_email.email' => 'Please enter a valid email address.',
        'user_email.regex' => 'Email contains invalid characters or format.',
        'user_email.max' => 'Email should not exceed 254 characters.',

        'user_image.image' => 'Uploaded file must be an image.',
        'user_image.mimes' => 'Image must be a file of type: jpeg, png, jpg, pdf.',
        'user_image.max' => 'Image size must not exceed 2MB.',
    ]);

    $validatedData['user_country'] = $validatedData['user_country'] ?? null;
    $validatedData['user_state'] = $validatedData['user_state'] ?? null;
    $validatedData['user_city'] = $validatedData['user_city'] ?? null;

     
        $uniqueId = $this->GenerateUniqueRandomString('buss_master_users', 'id', 6);  
    
        $buss_unique_id = $this->generateUniqueId(trim($request->user_franchise_name), $user->id );


        $user->buss_unique_id = strtolower($buss_unique_id);
        $user->updated_at = now(); 


      $userFolder = 'masteradmin/' .$buss_unique_id.'_'.$request->input(key: 'user_first_name');
      Storage::makeDirectory($userFolder, 0755, true);

      $users_image = '';

      if ($request->hasFile('user_image')) {

          $users_image =  $this->handleImageUpload($request, 'user_image', null, 'profile_image', $userFolder);
      }

      $user->user_image = $users_image;

    $user->where('id', $id)->update($validatedData);

    return redirect()->route('businessdetails.index')->with('success', 'Admin Agency Updated successfully.');
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


}

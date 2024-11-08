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

        $udetail = $userDetails->where('users_email', '!=', $user->user_email)->get();

        $userdetailss = $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->first();

        //dd($udetail);

        $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;

        $tableName = $user->buss_unique_id . '_tc_users_role';

        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();
            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';
        }

        return view('superadmin.businessdetails.view_business', compact('user', 'udetail','userdetailss'));
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
    $user = Auth::guard('masteradmins')->user();
    $user = MasterUser::findOrFail($id);

    $userDetails = new MasterUserDetails();
    $userDetails->setTableForUniqueId($user->buss_unique_id);

    $userdetails = $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->firstOrFail();

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
        'users_email' => [
            'required',
            'email',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'max:254'
        ],        
        'users_zip' => 'nullable', 'string', 'max:255',
        'users_country' => 'nullable',
        'users_state' => 'nullable',
        'users_city' => 'nullable',

        'users_image' => 'nullable',
        'users_image.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',

    ], [
        'users_agencies_name' => 'Agency name is required',
        'users_first_name' => 'First Name is required',
        'users_iata_clia_number' => 'IATA CLIA Number is required',

        'users_email.email' => 'Please enter a valid email address.',
        'users_email.regex' => 'Email contains invalid characters or format.',
        'users_email.max' => 'Email should not exceed 254 characters.',

        'users_image.image' => 'Uploaded file must be an image.',
        'users_image.mimes' => 'Image must be a file of type: jpeg, png, jpg, pdf.',
        'users_image.max' => 'Image size must not exceed 2MB.',
    ]);

    

    $userdetails->updated_at = now(); 


      $userFolder = 'masteradmin/' .$user->buss_unique_id.'_'.$user->user_first_name;
      Storage::makeDirectory($userFolder, 0755, true);

      $users_image = '';

        if ($request->hasFile('users_image')) {
            $users_image =  $this->handleImageUpload($request, 'users_image', null, 'profile_image', $userFolder);
            $validatedData['users_image'] = $users_image;
        }else{
            $validatedData['users_image'] = $userdetails->users_image ?? '';
        }

        $userDetails->where(['users_email' => $user->user_email, 'role_id' => 0])->update($validatedData);

        return redirect()->route('businessdetails.index')->with('success', 'Admin Agency Updated successfully.');
 }



}

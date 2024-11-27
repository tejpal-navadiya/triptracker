<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterUser;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Countries;
use App\Models\States;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MasterProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\BusinessDetails;
use App\Models\MasterUserDetails;
use Illuminate\Support\Facades\Storage;
use App\Models\UserCertification;

class ProfilesController extends Controller
{
    
    //
    public function __construct()
    {
        
        $this->middleware('auth_master');
    }
    
    
    public function edit(Request $request): View
    {
        // Retrieve the authenticated user
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $certification = UserCertification::where(['id' => $user->id])->get();
        
        //dd($cerifications);
       
        $states = States::get();
        // dd($user);
        return view('masteradmin.profile.edit', [
            'user' => $user,
            'states' => $states,
            'certification' => $certification
        ]);
    }

    public function fetchUser()
    {

         $user = Auth::guard('masteradmins')->user();
         //dd($user);
       // $userDetails = session('user_details');
       //dd($user);
       if ($user) {
            return response()->json(['users' => $user]);  // Return the entire user object for debugging
        } else {
            return response()->json(['status' => 404, 'message' => 'User not found']);
        }
    }

    public function edits($id)
    {
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->user_id);
    
        $existingUser = $userDetails->where('users_id', $id)->first();

        if($existingUser)
        {
            return response()->json([
                'status'=>200,
                'users'=> $existingUser,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No users Found.'
            ]);
        }

    }

    
    public function update(MasterProfileUpdateRequest $request)
    {
        // dd($request);
        $user = Auth::guard('masteradmins')->user();
       
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->user_id);
    
        $existingUser = $userDetails->where('users_id',$user->users_id)->first();
        //  dd($existingUser);
        if (!$existingUser) {
            return Redirect::route('masteradmin.profile.edit')->withErrors('User not found');
        }
    
        $data = $request->validated();
    
       
        // Update the record
        $existingUser->where('users_id',$existingUser->users_id)->update($data);

        $updatedUser = $userDetails->where('users_id',$user->users_id)->first();

        session(['user_details' => $updatedUser]);

        \MasterLogActivity::addToLog('Master Admin Profile is Edited.');

        return response()->json([
            'status'=>200,
            'message'=>'User Updated Successfully.'
        ]);
    }

    public function businessProfile(Request $request): View
    {
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $BusinessDetails = BusinessDetails::where('id', $user->id)->where('bus_status', 1)->first();
        // dd($BusinessDetails);
        $countries = Countries::all();
        $states = collect();
        $currency = null;
        if (isset($BusinessDetails->bus_currency)) {
            $currency = Countries::where('id', $BusinessDetails->bus_currency)->first();
        }

        if ($BusinessDetails && $BusinessDetails->country_id) {
            $states = States::where('country_id', $BusinessDetails->country_id)->get();
        }
        
        // dd($user);
        return view('masteradmin.profile.business-profile', [
            'BusinessDetails' => $BusinessDetails,
            'user' => $user,
            'countries' => $countries,
            'states' => $states,
            'currency' => $currency
        ]);
    }
    
    public function businessProfileUpdate(Request $request): RedirectResponse
    {
        $user = Auth::guard('masteradmins')->user();
        $BusinessDetails = BusinessDetails::where('id', $user->id)->where('bus_status', 1)->first();

        $validatedData = $request->validate([
            'bus_company_name' => 'required|string|max:255',
            'bus_address1' => 'required|string|max:255',
            'bus_address2' => 'required|string|max:255',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'bus_phone' => 'required|string|max:15',
            'bus_mobile' => 'nullable|string|max:15',
            'bus_website' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'bus_company_name.required' => 'The Company name field is required.',
            'bus_address1.required' => 'The address1 field is required.',
            'bus_address2.required' => 'The address2 field is required.',
            'country_id.required' => 'The country field is required',
            'state_id.required' => 'The state field is required',
            'city_name.required' => 'The city name field is required',
            'zipcode.required' => 'The zipcode field is required',
            'bus_phone.required' => 'The phone field is required.',
            'bus_mobile.string' => 'The mobile field is required.',
            'bus_website.string' => 'The website field is required.',
            'image.image' => 'The image field is required.',
        ]);

        $imageFilename = $this->handleImageUpload($request, $BusinessDetails->bus_image ?? null, 'masteradmin/business_profile');
        $currency_id = Countries::where('id', 233)->first();
        // dd($currency_id);

        if ($BusinessDetails) {
            // Update existing record
            $validatedData['bus_image'] = $imageFilename;
            $validatedData['bus_currency'] = $currency_id->id;
            $BusinessDetails->update($validatedData);
        } else {
            // Insert new record
            $validatedData['id'] = $user->id;
            $validatedData['bus_status'] = 1;
            $validatedData['bus_image'] = $imageFilename;
            $validatedData['bus_currency'] = $currency_id->id;
            BusinessDetails::create($validatedData);
        }
        \MasterLogActivity::addToLog('Master Admin Business Profile is Edited.');

        return redirect()->route('masteradmin.business.edit')->with('business-update', __('messages.masteradmin.business-profile.send_success'));
    }

    public function updateBusinessDetails(Request $request)
    {
        // dd($request);
        $user = Auth::guard('masteradmins')->user();
        $BusinessDetails = BusinessDetails::where('id', $user->id)->where('bus_status', 1)->first();

        if ($request->has('delete_image') && $request->delete_image == true) {
            if ($BusinessDetails->bus_image) {
                Storage::delete('masteradmin/business_profile/' . $BusinessDetails->bus_image);
                $BusinessDetails->bus_image = null;
                $BusinessDetails->save();
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Image removed successfully',
                'data' => $BusinessDetails->load('state', 'country') 
            ]);
        }

        $validatedData = $request->validate([
            'bus_company_name' => 'required|string|max:255',
            'bus_address1' => 'required|string|max:255',
            'bus_address2' => 'required|string|max:255',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'bus_phone' => 'required|string|max:15',
            'bus_mobile' => 'nullable|string|max:15',
            'bus_website' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'bus_company_name.required' => 'The Company name field is required.',
            'bus_address1.required' => 'The address1 field is required.',
            'bus_address2.required' => 'The address2 field is required.',
            'country_id.required' => 'The country field is required',
            'state_id.required' => 'The state field is required',
            'city_name.required' => 'The city name field is required',
            'zipcode.required' => 'The zipcode field is required',
            'bus_phone.required' => 'The phone field is required.',
            'bus_mobile.string' => 'The mobile field is required.',
            'bus_website.string' => 'The website field is required.',
            'image.image' => 'The image field is required.',
        ]);

        $imageFilename = $this->handleImageUpload($request, $BusinessDetails->bus_image ?? null, 'masteradmin/business_profile');
        $currency_id = Countries::where('id', 233)->first();

        if ($BusinessDetails) {
            // Update existing record
            $validatedData['bus_image'] = $imageFilename;
            $validatedData['bus_currency'] = $currency_id->id;
            $BusinessDetails->update($validatedData);
        } else {
            // Insert new record
            $validatedData['id'] = $user->id;
            $validatedData['bus_status'] = 1;
            $validatedData['bus_image'] = $imageFilename;
            $validatedData['bus_currency'] = $currency_id->id;
            BusinessDetails::create($validatedData);
        }

        $updatedBusinessDetails = BusinessDetails::where('id', $user->id)->first()->load('state', 'country');

        \MasterLogActivity::addToLog('Master Admin Business Profile is Edited.');
        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully',
            'data' => $updatedBusinessDetails
        ]);
      
    }


    public function logActivity()
    {
        $user = Auth::guard('masteradmins')->user();
     
        if ($user) {
           
            $uniqueId = $user->user_id; // Assuming this is your unique ID
            $adminUserModel = new MasterUserDetails();
            $adminUserModel->setTableForUniqueId($uniqueId);
            // dd($adminUserModel);
        
            $logs = \MasterLogActivity::logActivityLists()
                ->where('user_id', $user->users_id);        
          
            $userIds = $logs->pluck('user_id')->unique();
            $userDetails = $adminUserModel->whereIn('users_id', $userIds)->get()->keyBy('users_id');
            // dd($userDetails);
          
            $logs->transform(function ($log) use ($userDetails) {
                $userDetail = $userDetails->get($log->user_id);
                $log->users_first_name = $userDetail ? $userDetail->users_first_name : 'Unknown'; 
                $log->users_last_name = $userDetail ? $userDetail->users_last_name : 'Unknown'; 
                return $log;
            });
            // dd($logs);
            return view('masteradmin.logs.index')
                ->with('admin_user', $user) 
                ->with('logs', $logs);  
        }
      
    }

}

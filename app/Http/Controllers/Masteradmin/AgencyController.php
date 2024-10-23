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
    $phones_type = StaticAgentPhone::all();
    $users_role = UserRole::all();
    $country = Countries::all();
  
    return view('masteradmin.agency.create', compact('phones_type','users_role','country'));
}

    public function store(Request $request){

      $user = Auth::guard('masteradmins')->user();

      $dynamicId = $user->id;

      // dd($user);
     // dd($request->all());
    
      $validatedData = $request->validate([
        'users_first_name' => 'required|string|max:255',
        'users_last_name' => 'required|string|max:255',
        'users_email' => 'nullable|email|max:255',
        'users_address' => 'required|string|max:255',
        'users_zip' => 'required|string|max:10',
        'user_agency_numbers' => 'required|string|max:255',
        'user_qualification' => 'required|string|max:255',
        'user_work_email' => 'nullable|email|max:255',
        'user_dob' => 'required|date',
        'user_emergency_contact_person' => 'required|string|max:255',
        'user_emergency_phone_number' =>  'required|string|max:255',
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
        'users_address.required' => 'Address is required',
        'users_zip.required' => 'Zipcode is required',
        'user_agency_numbers.required' => 'ID Number is required',
        'user_qualification.required' => 'Qualification is required',
        'user_work_email.email' => 'Work email is required',
        'user_dob.required' => 'dob is required',
        'user_emergency_contact_person.required' => 'Emergency Contact is required',
        'user_emergency_phone_number.required' => 'Emergency phone is required',
        'user_emergency_email.email' => 'Emergency Email is required',
        'users_country.required' => 'Country is required',
        'users_state.required' => 'State is required',
        'users_city.required' => 'City is required',
        'role_id.required' => 'City is required',
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

        $agency->user_id = $user->user_id;   

      $agency->user_agency_numbers = $validatedData['user_agency_numbers'];
      $agency->user_qualification = $validatedData['user_qualification'];
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
      $agency->users_password = bcrypt($validatedData['users_password']);  
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

      return redirect()->route('agency.index')->with('success', 'Agecy User entry created successfully.');

      \MasterLogActivity::addToLog('Master Admin Users Certification Created.');

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

    //dd($request->all());

    $masteruser = new MasterUserDetails();
    $masteruser->setTableForUniqueId($user->user_id);
    $tableName = $masteruser->getTable();
  
    $userdetailu = $masteruser->where(['users_id' => $users_id,'id' => $user->id])->firstOrFail();

    //dd( $userdetailu);
    
    $validatedData = $request->validate([
      'user_agency_numbers' => 'required|string|max:255',
      'users_first_name' => 'required|string|max:255',
      'users_last_name' => 'required|string|max:255',
      'user_qualification' => 'required|string|max:255',
      'user_work_email' => 'nullable|email|max:255',
      'users_email' => 'nullable|email|max:255',
      'user_dob' => 'required|date_format:m/d/Y', // Assuming the date format is m/d/Y
      'role_id' => 'required|string|max:255',
      'users_password' => 'required|string|min:6', // Assuming min length for password
      'user_emergency_contact_person' => 'required|string|max:255',
      'user_emergency_email' => 'nullable|email|max:255',
      'users_address' => 'required|string|max:255',
      'users_country' => 'required|string|max:255',
      'users_state' => 'required|string|max:255',
      'users_city' => 'required|string|max:255',
      'users_zip' => 'required|string|max:10',
  
  ], [
      'user_agency_numbers.required' => 'User ID is required',
      'users_first_name.required' => 'First name is required',
      'users_last_name.required' => 'Last name is required',
      'user_qualification.required' => 'Qualification is required',
      'age_user_work_email.email' => 'Work email must be a valid email address',
      'users_email.email' => 'Personal email must be a valid email address',
      'user_dob.required' => 'Date of birth is required',
      'user_dob.date_format' => 'Date of birth must be in the format mm/dd/yyyy',
      'role_id.required' => 'User type is required',
      'users_password.required' => 'Password is required',
      'users_password.min' => 'Password must be at least 6 characters long',
      'user_emergency_contact_person.required' => 'Emergency contact is required',
      'user_emergency_email.email' => 'Emergency email must be a valid email address',
      'users_address.required' => 'Address is required',
      'users_country.required' => 'Country is required',
      'users_state.required' => 'State is required',
      'users_city.required' => 'City is required',
      'users_zip.required' => 'ZIP code is required',
      'users_zip.max' => 'ZIP code must not exceed 10 characters',
  ]);

$userdetailu->user_agency_numbers = $validatedData['user_agency_numbers'];

$userdetailu->users_first_name = $validatedData['users_first_name'];
$userdetailu->users_last_name = $validatedData['users_last_name'];
$userdetailu->user_qualification = $validatedData['user_qualification'];
$userdetailu->user_work_email  = $validatedData['user_work_email'];

$userdetailu->users_email  = $validatedData['users_email'];
$userdetailu->user_dob = $validatedData['user_dob'];
$userdetailu->role_id = $validatedData['role_id'];
$userdetailu->users_password = bcrypt($validatedData['users_password']); 

$userdetailu->user_emergency_contact_person	 = $validatedData['user_emergency_contact_person'];
$userdetailu->user_emergency_email  = $validatedData['user_emergency_email'];
$userdetailu->users_address = $validatedData['users_address'];
$userdetailu->users_city = $validatedData['users_city'];

$userdetailu->users_country = $validatedData['users_country'];
$userdetailu->users_state = $validatedData['users_state'];
$userdetailu->users_zip = $validatedData['users_zip'];


  $userdetailu->where('users_id', $users_id)->update($validatedData);

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
      \MasterLogActivity::addToLog('Master Admin Library Deleted.');

      return redirect()->route(route: 'agency.index')->with('success', 'Agency deleted successfully');
   }
  }
  public function getStates($countryId)
  {
      $states = States::where('country_id', $countryId)->get();
      return response()->json($states);
  }
  
  public function getCities($stateId)
  {
      $cities = Cities::where('state_id', $stateId)->get();  // Fetch cities by state_id
      return response()->json($cities);
  }

  public function view($id)
  {
    $user = Auth::guard('masteradmins')->user();

    $masteruser = new MasterUserDetails();
    $masteruser->setTableForUniqueId($user->user_id);
    $agency = $masteruser->where('users_id', $id)->firstOrFail();


    return view('masteradmin.agency.view', compact('agency'));
  }

  public function rolemodel($id){

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
}
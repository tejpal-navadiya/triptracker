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





class AgencyController extends Controller
{
    public function index(): View
    {
      $user = Auth::guard('masteradmins')->user();
      $agency = Agency::all();

      return view('masteradmin.agency.index',compact('agency'));
      
    }

    public function create(): View
{
    $phones_type = StaticAgentPhone::all();

    $users_role = UserRole::all();

    // dd($phones_type);
    return view('masteradmin.agency.create', compact('phones_type','users_role'));
}


    public function store(Request $request){

      $user = Auth::guard('masteradmins')->user();

      $dynamicId = $user->id;

      //dd($request->all());

      $validatedData = $request->validate([
        'age_user_id' => 'required|string|max:255',
        'age_user_first_name' => 'required|string|max:255',
        'age_user_last_name' => 'required|string|max:255',
        'age_user_qualification' => 'nullable|string|max:255',
        'age_user_work_email' => 'required|email|max:255',
        'age_user_personal_email' => 'nullable|email|max:255',
        'age_user_dob' => 'required|date',
        'age_user_type' => 'required|string',
        'age_user_password' => 'required|string|min:4', // Assumes password confirmation
        'age_user_emergency_contact' => 'required|string|min:4', // Assumes confirmation
        'age_user_phone_number' => 'required|string',
        'age_user_emergency_email' => 'nullable|email|max:255',
        'age_user_address' => 'required|string|max:255',
        'age_user_city' => 'required|string|max:255',
        'age_user_state_type' => 'required|string|max:255',
        'age_user_zip' => 'required|string|max:10',
    ], [
        'age_user_id' => 'ID Number is required',
        'age_user_first_name.required' => 'First name is required',
        'age_user_last_name.required' => 'Last name is required',
        'age_user_work_email.required' => 'Work email is required',
        'age_user_work_email.email' => 'Work email must be a valid email address',
        'age_user_password.required' => 'Password is required',
        'age_user_password.min' => 'Password must be at least 4 characters long',
        'age_user_emergency_contact.required' => 'Emergency Contact is required',
        'age_user_phone_number.required' => 'Phone number is required',
        'age_user_dobs.required' => 'Birthdate is required',
        'age_user_address.required' => 'Address is required',
        'age_user_city.required' => 'City is required',
        'age_user_state_type.required' => 'State type is required',
        'age_user_zip.required' => 'ZIP code is required',
    ]);
    

      $agency = new agency();

      $tableName = $agency->getTable();

      $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "age_id", $chars = 6);

      $agency->age_id = $uniqueId1;
      $agency->id = $dynamicId;

      $agency->age_user_id = $validatedData['age_user_id'];
      $agency->age_user_first_name = $validatedData['age_user_first_name'];
      $agency->age_user_last_name = $validatedData['age_user_last_name'];
      $agency->age_user_qualification = $validatedData['age_user_qualification'];
      $agency->age_user_work_email = $validatedData['age_user_work_email'];
      $agency->age_user_personal_email = $validatedData['age_user_personal_email'];
      $agency->age_user_dob = $validatedData['age_user_dob'];

      $agency->age_user_type = $validatedData['age_user_type'];

      $agency->age_user_password = bcrypt($validatedData['age_user_password']); 
      $agency->age_user_emergency_contact = $validatedData['age_user_emergency_contact'];
      $agency->age_user_phone_number = $validatedData['age_user_phone_number'];
      $agency->age_user_emergency_email = $validatedData['age_user_emergency_email'];
      $agency->age_user_address = $validatedData['age_user_address'];
      $agency->age_user_city = $validatedData['age_user_city'];
      $agency->age_user_state_type = $validatedData['age_user_state_type'];
      $agency->age_user_zip = $validatedData['age_user_zip'];
    
      $agency->save();


      
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
            $travelerItem->age_id = $uniqueId1;
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
    $agency = Agency::where('age_id', $id)->firstOrFail();
    
    $user = Agency::select('id', 'age_user_type')->get();
    $status = Agency::select('id', 'age_user_state_type')->get();


    //$agent = AgencyPhones::all();
    $agent = AgencyPhones::where('age_id', $agency->age_id)->get();

    $users_role = UserRole::all();

    $phones_type = StaticAgentPhone::all();

    //$phones= StaticAgentPhone::all();
    //dd($agent);
    
    return view('masteradmin.agency.edit', compact('agency', 'user', 'status','agent','phones_type','users_role'));
    } 

   public function update(Request $request, $id)
  {
    $user = Auth::guard('masteradmins')->user();

    // Fetch the existing library record
    $agency = Agency::where('age_id', $id)->firstOrFail();


    $validatedData = $request->validate([
      'age_user_id' => 'required|string|max:255',
      'age_user_first_name' => 'required|string|max:255',
      'age_user_last_name' => 'required|string|max:255',
      'age_user_qualification' => 'nullable|string|max:255',
      'age_user_work_email' => 'required|email|max:255',
      'age_user_personal_email' => 'nullable|email|max:255',
      'age_user_dob' => 'required|date',
      'age_user_type' => 'required|string',
      'age_user_password' => 'required|string|min:4', // Assumes password confirmation
      'age_user_emergency_contact' => 'required|string|min:4', // Assumes confirmation
      'age_user_phone_number' => 'required|string',
      'age_user_emergency_email' => 'nullable|email|max:255',
      'age_user_address' => 'required|string|max:255',
      'age_user_city' => 'required|string|max:255',
      'age_user_state_type' => 'required|string|max:255',
      'age_user_zip' => 'required|string|max:10',
  ], [
      'age_user_id' => 'ID Number is required',
      'age_user_first_name.required' => 'First name is required',
      'age_user_last_name.required' => 'Last name is required',
      'age_user_work_email.required' => 'Work email is required',
      'age_user_work_email.email' => 'Work email must be a valid email address',
      'age_user_password.required' => 'Password is required',
      'age_user_password.min' => 'Password must be at least 4 characters long',
      'age_user_emergency_contact.required' => 'Emergency Contact is required',
      'age_user_phone_number.required' => 'Phone number is required',
      'age_user_dob.required' => 'Birthdate is required',
      'age_user_address.required' => 'Address is required',
      'age_user_city.required' => 'City is required',
      'age_user_state_type.required' => 'State type is required',
      'age_user_zip.required' => 'ZIP code is required',
  ]);


  $agency->where(['age_id' => $id])->update($validatedData);

  AgencyPhones::where('age_id', $id)->delete();


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
      $travelerItem->age_id = $id;
      $travelerItem->id = $user->id;
      $travelerItem->age_user_phone_id = $ageid;


    $travelerItem->fill($item);


    $travelerItem->save();
}


  $agency->age_user_id = $validatedData['age_user_id'];
  $agency->age_user_first_name = $validatedData['age_user_first_name'];
  $agency->age_user_last_name = $validatedData['age_user_last_name'];
  $agency->age_user_qualification = $validatedData['age_user_qualification'];
  $agency->age_user_work_email = $validatedData['age_user_work_email'];
  $agency->age_user_personal_email = $validatedData['age_user_personal_email'];
  $agency->age_user_dob = $validatedData['age_user_dob'];
  $agency->age_user_type = $validatedData['age_user_type'];
  $agency->age_user_password = bcrypt($validatedData['age_user_password']); 
  $agency->age_user_emergency_contact = $validatedData['age_user_emergency_contact'];
  $agency->age_user_phone_number = $validatedData['age_user_phone_number'];
  $agency->age_user_emergency_email = $validatedData['age_user_emergency_email'];
  $agency->age_user_address = $validatedData['age_user_address'];
  $agency->age_user_city = $validatedData['age_user_city'];
  $agency->age_user_state_type = $validatedData['age_user_state_type'];
  $agency->age_user_zip = $validatedData['age_user_zip'];

  $agency->save();



    // Log the activity
    \MasterLogActivity::addToLog('Master Admin Agency Updated.');

    return redirect()->route('agency.index')->with('success', 'Agecy User Update created successfully.');
   }

   public function destroy($age_id)
  {
      // Get the authenticated master admin user
      $user = Auth::guard('masteradmins')->user();


      // Find the library by lib_id, checking for the user's ID if needed
      $agency = Agency::where('age_id', $age_id)->firstOrFail();


      if ($agency) {
        
        $agent_number = AgencyPhones::where('age_id', $age_id)->delete();
        $agency->where('age_id', $age_id)->delete();


      // Delete the library record
      //$agency->where('age_id', $age_id)->delete();


      // Log the deletion
      \MasterLogActivity::addToLog('Master Admin Library Deleted.');

      return redirect()->route(route: 'agency.index')->with('success', 'Agency deleted successfully');
   }
  }
}
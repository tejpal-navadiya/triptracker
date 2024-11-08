<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use App\Models\TripType;
use App\Models\TripTravelingMember;
use App\Models\TaskCategory;
use App\Models\DocumentType;
use App\Models\TypeOfTrip;
use App\Models\TripItineraryDetail;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\Support\Facades\DB;
use App\Models\TripStatus;
use App\Models\TaskStatus;
use App\Models\TripTask;
use App\Models\PredefineTask;
use App\Models\MasterUserDetails;
use Carbon\Carbon;



class TripController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();

        $startDate = $request->input('start_date'); 
        $endDate = $request->input('end_date');   
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
        $trip_status1 = $request->input('trip_status');   

        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
        $agency = $masterUserDetails->get();

        $trip_status = TripStatus::get();

        
        $trips = new Trip();
        
        $tripTable = $trips->getTable();


        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.id', $user->users_id)
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);

            
    $today = Carbon::today('Asia/Kolkata'); 
    
    //$tripQuery->orderByRaw("CASE WHEN tr_start_date = ? THEN 0 ELSE 1 END, tr_start_date ASC", [$today]);
    
    $tripQuery->orderByRaw("CASE WHEN tr_start_date = ? THEN 0 ELSE 1 END, tr_start_date DESC", [$today]);


    

        if ($startDate && !$endDate) {
            $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
        } elseif ($startDate && $endDate) {
            $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
                ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
        }

        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }

        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.tr_status', $trip_status1);
        }


        $trip = $tripQuery->get();
        


        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.filtered_results', compact('trip', 'agency', 'trip_status'))->render();
         }
        // dd($trip);

        return view('masteradmin.trip.index', compact('trip', 'agency', 'trip_status'));
    }


    public function create(): View
    {

        $user = Auth::guard('masteradmins')->user();
        //$dynamicId = $user->id;

        $triptype = TripType::all();

       $agency_users = new MasterUserDetails();
       $agency_users->setTableForUniqueId($user->user_id);
       $tableName = $agency_users->getTable();
       $agency_user = $agency_users->get(); 
       
    //    dd($aency_user);

        return view('masteradmin.trip.create', compact('triptype','agency_user'));
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id;
        $validatedData = $request->validate([
            
            'tr_name' => [
                'required',         
                'string',          
                'max:255',         
                ],

            'tr_agent_id' => 'required|string',

            'tr_traveler_name' => [
                'required',         
                'string',          
                'max:255',         
                ],
    
            'tr_dob' => 'nullable|string',
            'tr_age' => 'nullable|string',

            'tr_email' => [
            'required',
            'email',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'max:255'
            ],   

            'tr_phone' => [
            'nullable',                          
            'string',                          
            'regex:/^\+?[1-9]\d{1,14}$/',    
            'min:1',  
            'max:12',                          
            ],

            'tr_num_people' => 'nullable|string',
            'tr_number' => 'nullable|string',
            'tr_start_date' => 'required',
            'tr_end_date' => 'required|string',
            'tr_value_trip' => 'nullable|string',
            'tr_desc' => 'nullable|string',
            'tr_country' => 'nullable|string',
            'tr_state' => 'nullable|string',
            'tr_city' => 'nullable|string',
            'tr_address' => 'nullable|string',
            'tr_zip' => 'nullable|numeric|digits_between:1,6',
            'items.*.trtm_type' => 'required|string',
            'items.*.trtm_first_name' => 'required|string',
            'items.*.trtm_middle_name' => 'nullable|string',
            'items.*.trtm_last_name' => 'nullable|string',
            'items.*.trtm_nick_name' => 'nullable|string',
            'items.*.trtm_relationship' => 'nullable:items.*.trtm_type,1',
            'items.*.trtm_gender' => 'nullable:items.*.trtm_type,2',
            'items.*.trtm_dob' => 'required|string',
            'items.*.trtm_age' => 'nullable|string',
        ], [

            'tr_name.required' => 'Traveler name is required',
            'tr_agent_id.required' => 'Agent ID is required',
            'tr_dob' => 'DOB Is Required',
            'tr_age' => 'Age is Required',
            'tr_traveler_name.required' => 'Traveler name is required',
            'tr_email.email' => 'Invalid email address',
            'tr_phone.regex' => 'The phone number must be between 1 to 12 digits.',
            'tr_num_people' => 'Total Number Of People Required',
            'tr_number' => 'Number Is Required',
            'tr_start_date.required' => 'Start date is required',
            'tr_end_date.required' => 'End Date Is Required',
            'tr_value_trip' => 'Value Trip Is Required',
            'tr_desc' => 'Description Is Required',
            'tr_country.required' => 'Country is required',
            'tr_state.required' => 'State is required',
            'tr_city.required' => 'City is required',
            'tr_address.required' => 'Address is required',
            'tr_zip.required' => 'Zip is required',

            'items.*.trtm_type.required' => 'Traveling member type is required',
            'items.*.trtm_first_name.required' => 'First name is required',
            'items.*.trtm_last_name.required' => 'Last name is required',
            'items.*.trtm_gender.required' => 'Gender is required',
            'items.*.trtm_dob.required' => 'Birthdate is required',
            'items.*.trtm_age.required' => 'Age is required',
        ]);

        // dd('data');

        // Store data
        $traveler = new Trip();
        $tableName = $traveler->getTable();
        $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "tr_id", $chars = 6);
        $traveler->tr_id = $uniqueId;



        $existingtrip = $traveler->where('tr_email', $validatedData['tr_email'])->first();

        if ($existingtrip) {
            return redirect()->back()->withErrors(['tr_email' => 'The email address is already in use.'])->withInput();
        }


        $traveler->id = $user->users_id;
        $traveler->tr_name = $validatedData['tr_name'];
        $traveler->tr_agent_id = $validatedData['tr_agent_id'];
        $traveler->tr_traveler_name = $validatedData['tr_traveler_name'];
        $traveler->tr_dob = $validatedData['tr_dob'] ?? null; 
        $traveler->tr_age = $validatedData['tr_age'] ?? null;
        $traveler->tr_number = $validatedData['tr_number'] ?? null; 
        $traveler->tr_email = $validatedData['tr_email'] ?? null; 
        $traveler->tr_phone = $validatedData['tr_phone'] ?? null; 
        $traveler->tr_num_people = $validatedData['tr_num_people'] ?? null;
        $traveler->tr_start_date = $validatedData['tr_start_date'];
        $traveler->tr_end_date = $validatedData['tr_end_date'] ?? null;
        $traveler->tr_value_trip = $validatedData['tr_value_trip'] ?? null; 
        $traveler->tr_type_trip = json_encode($request->input('tr_type_trip'));
        $traveler->tr_desc = $validatedData['tr_desc'] ?? null; 

        $traveler->tr_country = $validatedData['tr_country'] ?? null;
        $traveler->tr_state = $validatedData['tr_state'] ?? null;
        $traveler->tr_city = $validatedData['tr_city'] ?? null;
        $traveler->tr_address = $validatedData['tr_address'] ?? null;
        $traveler->tr_zip = $validatedData['tr_zip'] ?? null;

        $traveler->status = 1;
        $traveler->tr_status = 1;
        $traveler->save();


        $rawItems = $request->input('items');
        if (isset($rawItems) && is_array($rawItems) && count($rawItems) > 0) {

            foreach ($rawItems as $item) {
                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);

                $travelerItem->fill($item);

                $travelerItem->tr_id = $traveler->tr_id;
                $travelerItem->id = $user->users_id;
                $travelerItem->trtm_status = 1;
                $travelerItem->trtm_id = $uniqueId1;

                $travelerItem->save();
            }
        }

        $tripTypes = $request->input('trip_types');
        if (isset($tripTypes) && is_array($tripTypes) && count($tripTypes) > 0) {
            if (!empty($tripTypes)) {
                foreach ($tripTypes as $tripTypeId => $tripTypeEntries) {
                    foreach ($tripTypeEntries as $entry) {
                        $tripTypeText = $entry['trip_type_text'];
                        $tripTypeConfirmation = $entry['trip_type_confirmation'];
                        $tripTypeName = $entry['trip_type_name'];

                        $typeOfTrip = new TypeOfTrip();
                        $tableNameType = $typeOfTrip->getTable();
                        $uniqueIdType = $this->GenerateUniqueRandomString($table = $tableNameType, $column = "trip_type_id", $chars = 6);
                        $typeOfTrip->trip_type_id =  $uniqueIdType;
                        $typeOfTrip->tr_id =  $traveler->tr_id;
                        $typeOfTrip->id = $user->users_id;
                        $typeOfTrip->trip_type_name = $tripTypeName;
                        $typeOfTrip->trip_type_text = $tripTypeText;
                        $typeOfTrip->trip_type_confirmation = $tripTypeConfirmation;
                        $typeOfTrip->trip_status = '1';
                        $typeOfTrip->save();
                    }
                }
            }
        }

        $rawItemsItinerary = $request->input('itinerary');
        if (isset($rawItemsItinerary) && is_array($rawItemsItinerary) && count($rawItemsItinerary) > 0) {

            foreach ($rawItemsItinerary as $item) {
                $itineraryItem = new TripItineraryDetail();
                $tableNameitineraryItem = $itineraryItem->getTable();
                $uniqueId1itinerary = $this->GenerateUniqueRandomString($table = $tableNameitineraryItem, $column = "trit_id", $chars = 6);

                $itineraryItem->fill($item);

                $itineraryItem->tr_id = $traveler->tr_id;
                $itineraryItem->id = $user->users_id;
                $itineraryItem->trit_status = 1;
                $itineraryItem->trit_id = $uniqueId1itinerary;

                $itineraryItem->save();
            }
        }

        $predefinedTasks = PredefineTask::select('pre_task_name')->where('pre_task_type' , '1')->get();

      foreach ($predefinedTasks as $task) {
        $tripTask = new TripTask();
        $tableName = $tripTask->getTable();
        $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvt_id", $chars = 6);
  
          TripTask::create([
              'id'=>$user->users_id,
              'trvt_id' =>$uniqueId1,
             'tr_id' => $traveler->tr_id, 
              'trvt_name' => $task->pre_task_name, 
          ]);
      }
    

        if ($request->travelers == "travelers") {
            \MasterLogActivity::addToLog('Master Admin Travelers Created.');

            return redirect()->route('masteradmin.travelers.travelersDetails')
                ->with('success', 'Travelers created successfully.');
        } else {
            \MasterLogActivity::addToLog('Master Admin Trip Created.');

            return redirect()->route('trip.index')
                ->with('success', 'Trip created successfully.');
        }
    }

    public function edit($id)
    {
        // dd($id);
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $tripmember = TripTravelingMember::where('tr_id', $trip->tr_id)->get();
        // dd($tripmember);

        $triptype = TripType::all();

        $tripstatus = TripStatus::all();


        $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
        $agency_users = $agency_users->get();

        //dd($tripstatus);

        $selectedStatus = $trip->status;
        //$status = Trip::where('status', $selectedStatus)->get();

        return view('masteradmin.trip.edit', compact('trip', 'triptype', 'tripmember', 'tripstatus', 'selectedStatus','agency_users'));

        //return view('masteradmin.trip.edit',compact('trip','triptype', 'tripmember','tripstatus','status'));

    }

    public function update(Request $request, $id): RedirectResponse
    {

        //dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $trip = Trip::where(['tr_id' => $id])->firstOrFail();

        $validatedData = $request->validate([

            'tr_name' => [
                'required',         
                'string',          
                'max:255',         
                ],

            'tr_agent_id' => 'required|string',

            'tr_traveler_name' => [
                'required',         
                'string',          
                'max:255',         
                ],
    
            'tr_dob' => 'nullable|string',
            'tr_age' => 'nullable|string',

            'tr_email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'max:255'
                ],   

            'tr_phone' => [
                'nullable',                          
                'string',                          
                'regex:/^\+?[1-9]\d{1,14}$/',    
                'min:1',  
                'max:12',                          
                ],

            'tr_num_people' => 'nullable|string',
            'tr_number' => 'nullable|string',
            'tr_start_date' => 'required',
            'tr_end_date' => 'nullable|string',
            'tr_value_trip' => 'nullable|string',
            'tr_desc' => 'nullable|string',
            'tr_country' => 'nullable|string',
            'tr_state' => 'nullable|string',
            'tr_city' => 'nullable|string',
            'tr_address' => 'nullable|string',
            'tr_zip' => 'nullable|string',
            'status' => 'nullable',


        ], [
            'tr_name.required' => 'Traveler name is required',
            'tr_agent_id.required' => 'Agent ID is required',
            'tr_traveler_name.required' => 'Traveler name is required',
            'tr_dob' => 'DOB Is Required',
            'tr_age' => 'Age is Required',
            'tr_email.email' => 'Invalid email address',
            'tr_phone' => 'Phone is Required',
            'tr_num_people' => 'Total Number Of People Required',
            'tr_number' => 'Number Is Required',
            'tr_start_date.required' => 'Start date is required',
            'tr_end_date' => 'End Date Is Required',
            'tr_value_trip' => 'Value Trip Is Required',
            'tr_desc' => 'Description Is Required',
            'tr_country.required' => 'Country is required',
            'tr_state.required' => 'State is required',
            'tr_city.required' => 'City is required',
            'tr_address.required' => 'Address is required',
            'tr_zip.required' => 'Zip is required',


        ]);



        $trip->where(['tr_id' => $id])->update($validatedData);

        // Update the status of TripTravelingMember without deleting it
        //TripTravelingMember::where('tr_id', $id)->update(['trtm_status' => 1]);


        TripTravelingMember::where('tr_id', $id)->delete();

        $rawItems = $request->input('items');

        if (isset($rawItems) && is_array($rawItems) && count($rawItems) > 0) {
            foreach ($rawItems as $item) {
                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);

                $travelerItem->fill($item);

                $travelerItem->tr_id = $id;
                $travelerItem->id = $user->users_id;
                $travelerItem->trtm_status = 1;
                $travelerItem->trtm_id = $uniqueId1;

                $travelerItem->save();
            }
        }

        if (isset($validatedData['status'])) {
            $predefinedTasks = PredefineTask::select('pre_task_name')->where('pre_task_type', $validatedData['status'])->get();
        
            $existingTasks = TripTask::where('tr_id', $id)->pluck('trvt_name')->toArray();
        
            foreach ($predefinedTasks as $task) {
                if (!in_array($task->pre_task_name, $existingTasks)) {
                    $tripTask = new TripTask();
                    $tableName = $tripTask->getTable();
                    $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvt_id", $chars = 6);
        
                    TripTask::create([
                        'id' => $user->users_id,
                        'trvt_id' => $uniqueId1,
                        'tr_id' => $id,
                        'trvt_name' => $task->pre_task_name,
                    ]);
                }
            }
        }
      
        if ($request->travelers == "travelers") {
            \MasterLogActivity::addToLog('Master Admin Travelers Updated.');

            return redirect()->route('masteradmin.travelers.travelersDetails')
                ->with('success', 'Travelers updated successfully.');
        } else {
            \MasterLogActivity::addToLog('Master Admin Trip Updated.');
            return redirect()->route('trip.index')

                ->with('success', 'Trip updated successfully');
        }
    }

    public function destroy($id): RedirectResponse
    {
        // dd($id);

        $trip = Trip::where('tr_id', $id)->first();

        if ($trip) {
            $tripmember = TripTravelingMember::where('tr_id', $id)->delete();
            $trip->where('tr_id', $id)->delete();

            \MasterLogActivity::addToLog('Master Admin Trip Deleted.');

            return redirect()->route('trip.index')

                ->with('success', 'Trip deleted successfully');
        }
    }

    public function view($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();
        
        $trip_details = Trip::where('tr_id', $id)->firstOrFail();
        $triptableName = $trip_details->getTable();


        
        $taskCategory = new TaskCategory();
        $taskCategory = $taskCategory->get();

        //dd($TaskCategory);

        $documentType = DocumentType::get();

         $trip_id=$id;
     
     
            $agency_users = new MasterUserDetails();
            $agency_users->setTableForUniqueId($user->user_id);
            $tableName = $agency_users->getTable();

            $agency_user = $agency_users->get(); 


            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id); 
            $masterUserTable = $masterUserDetails->getTable();
             $trips = new Trip();
             $tripTable = $trips->getTable();  
     
             $trip = Trip::where('tr_status', 1) 
                   ->from($tripTable)  
                   ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                   ->where($tripTable . '.id', $user->users_id)
                   ->where($tripTable . '.tr_id', $id)
                   ->select([
                       $tripTable . '.*',  
                       $masterUserTable . '.users_first_name', 
                       $masterUserTable . '.users_last_name'  
                   ])
                   ->firstOrFail();
            
            // dd($trip);


                   
            $taskstatus = TaskStatus::all();

            $tripTraveling = new TripTravelingMember();
            $tableName = $tripTraveling->getTable();

            $uniq_id = $user->user_id; 


            $tripTravelingMembers = DB::table($uniq_id . '_tc_trip_traveling_member')
                ->select('trtm_id', 'trtm_first_name', 'trtm_last_name')
                ->where('trtm_status', 1) 
                ->where($tableName . '.id', $user->users_id)
                ->where($tableName . '.tr_id', $id)
                ->get();



            $tripData = DB::table($uniq_id . '_tc_trip')
                ->select('tr_id', 'tr_traveler_name')
                ->where('tr_id', $id)
                ->get();



        //dd($tripTraveling);

        return view('masteradmin.trip.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripData', 'tripTravelingMembers','taskstatus','agency_user','trip_id','trip_details'));
    }

    public function travelersDetails(): View
    {
        $user = Auth::guard('masteradmins')->user();
        $trip = Trip::where(['tr_status' => 1, 'id' => $user->users_id])->get();

        
        return view('masteradmin.traveler.index', compact('trip'));
    }

    public function createTravelers(): View
    {
        $triptype = TripType::all();
        $country = Countries::all();

       $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
 
        $agency_user = $agency_users->get(); 

        return view('masteradmin.traveler.create', compact('triptype', 'country','agency_user'));
    }

    public function editDetails($id)
    {
        // dd($id);
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $tripmember = TripTravelingMember::where('tr_id', $trip->tr_id)->get();
        // dd($tripmember);
        $triptype = TripType::all();


        $country = Countries::all();

        $selectedCountryId = $trip->tr_country;

        $states = States::where('country_id', $selectedCountryId)->get();

        $selectedStateId = $trip->tr_state;

        $city = Cities::where('state_id', $selectedStateId)->get();


        $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
        $agency_users = $agency_users->get();



        return view('masteradmin.traveler.edit', compact('trip', 'triptype', 'tripmember', 'country', 'states', 'city','agency_users'));
    }

    public function viewDetails($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();

        $trip = Trip::with(['country','state','city'])
        ->where('tr_id', $id)
        ->firstOrFail();

        $triptableName = $trip->getTable();

        $tripTraveling = new TripTravelingMember();
        $tableName = $tripTraveling->getTable();

        $uniq_id = $user->user_id; // Ensure this is set based on your authentication logic



        $tripTravelingMembers = DB::table($uniq_id . '_tc_trip_traveling_member')
            ->select('trtm_id', 'trtm_first_name', 'trtm_last_name')
            ->where('trtm_status', 1) // Ensure you're filtering by status
            ->get();
            

          //  return trim($firstName . ' ' . $middleName . ' ' . $lastName) ?: $document->trip->tr_traveler_name;




            $tripData = DB::table($uniq_id . '_tc_trip')
            ->select('tr_id', 'tr_traveler_name')
            ->where('tr_id', $id)
            ->get();



        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
        //  $tripTraveling = TripTravelingMember::where(['trtm_status' => 1, 'id' => $user->id, 'tr_id' => $id])->get();
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
        $documentType = DocumentType::get();
        


        // dd($trip);
        return view('masteradmin.traveler.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripTravelingMembers', 'tripData'));
    }

    public function booked_after(Request $request): View
    {
        $user = Auth::guard('masteradmins')->user();

        $startDate = $request->input('start_date'); 
        $endDate = $request->input('end_date');   
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
        $trip_status1 = $request->input('trip_status');   

        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
        $agency = $masterUserDetails->get();

        $trip_status = TripStatus::get();

        $trips = new Trip();
        $tripTable = $trips->getTable();



        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.id', $user->users_id)
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);



        //filter do not touch

        if ($startDate && !$endDate) {
            $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
        } elseif ($startDate && $endDate) {
            $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
                ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
        }

        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }

        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.tr_status', $trip_status1);
        }

        $trip = $tripQuery->get();
        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.filtered_results', compact('trip', 'agency', 'trip_status'))->render();
         }
        // dd($trip);
    //end do not touch

      return view('masteradmin.trip.booked-after',compact('trip', 'agency', 'trip_status','tripQuery'));
    }
}

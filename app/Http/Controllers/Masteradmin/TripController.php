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
use App\Models\TravelerDocument;
use App\Models\PredefineTaskCategory;
use DataTables;


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

        if ($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
        
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
        }
       // $agency = $masterUserTable->get();
        $trip_status = TripStatus::get();

        
        $trips = new Trip();
        
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;
        if($user->users_id && $user->role_id ==0 ){
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }else{
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);

        }
            // Execute the query
            $tripResults = $tripQuery->get();

            // Group the results by 'tr_id'
            $tripsGrouped = $tripResults->groupBy('tr_id');

       

                
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
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }


        $trip = $tripQuery->get();
        

        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.filtered_results', compact('trip', 'agency', 'trip_status'))->render();
         }
        // dd($trip);

        return view('masteradmin.trip.index', compact('trip', 'agency', 'trip_status','tripsGrouped'));
    }

    public function create(): View
    {

        $user = Auth::guard('masteradmins')->user();
        //$dynamicId = $user->id;

        $triptype = TripType::all();

       $agency_users = new MasterUserDetails();
       $agency_users->setTableForUniqueId($user->user_id);
       $tableName = $agency_users->getTable();
       if ($user->users_id && $user->role_id == 0) {
            $agency_user = $agency_users->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency_user = $agency_users->where('users_id', $user->users_id)->get();
        }
    

       
       
    //    dd($aency_user);

        return view('masteradmin.trip.create', compact('triptype','agency_user'));
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id;
        if ($request->travelers == "travelers") {
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'tr_traveler_name' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'required|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
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
                'items.*.trtm_type' => 'nullable|string',
                'items.*.trtm_first_name' => 'nullable|string',
                'items.*.trtm_middle_name' => 'nullable|string',
                'items.*.trtm_last_name' => 'nullable|string',
                'items.*.trtm_nick_name' => 'nullable|string',
                'items.*.trtm_relationship' => 'nullable:items.*.trtm_type,1',
                'items.*.trtm_gender' => 'nullable:items.*.trtm_type,2',
                'items.*.trtm_dob' => 'nullable|string',
                'items.*.trtm_age' => 'nullable|string',
            ], [
    
                'tr_name.required' => 'Traveler name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'tr_traveler_name.required' => 'Traveler name is required',
                'tr_email.required' => 'Email is required',
                'tr_start_date.required' => 'Start date is required',
                'tr_end_date.required' => 'End Date Is Required',
      
            ]);
        }else{
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'tr_traveler_name' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'nullable|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
                'tr_num_people' => 'nullable|string',
                'tr_number' => 'nullable|string',
                'tr_start_date' => 'nullable',
                'tr_end_date' => 'nullable|string',
                'tr_value_trip' => 'nullable|string',
                'tr_desc' => 'nullable|string',
                'tr_country' => 'nullable|string',
                'tr_state' => 'nullable|string',
                'tr_city' => 'nullable|string',
                'tr_address' => 'nullable|string',
                'tr_zip' => 'nullable|numeric|digits_between:1,6',
                'items.*.trtm_type' => 'nullable|string',
                'items.*.trtm_first_name' => 'nullable|string',
                'items.*.trtm_middle_name' => 'nullable|string',
                'items.*.trtm_last_name' => 'nullable|string',
                'items.*.trtm_nick_name' => 'nullable|string',
                'items.*.trtm_relationship' => 'nullable:items.*.trtm_type,1',
                'items.*.trtm_gender' => 'nullable:items.*.trtm_type,2',
                'items.*.trtm_dob' => 'nullable|string',
                'items.*.trtm_age' => 'nullable|string',
            ], [
    
                'tr_name.required' => 'Traveler name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'tr_traveler_name.required' => 'Traveler name is required',
      
            ]);
    
        }
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
        $traveler->tr_dob = $validatedData['tr_dob'] ?? null; // Use null if not set
        $traveler->tr_age = $validatedData['tr_age'] ?? null; // Use null if not set
        $traveler->tr_number = $validatedData['tr_number'] ?? null; // Use null if not set
        $traveler->tr_email = $validatedData['tr_email'] ?? null; // Use null if not set
        $traveler->tr_phone = $validatedData['tr_phone'] ?? null; // Use null if not set
        $traveler->tr_num_people = $validatedData['tr_num_people'] ?? null; // Use null if not set
        $traveler->tr_start_date = $validatedData['tr_start_date'];
        $traveler->tr_end_date = $validatedData['tr_end_date'] ?? null; // Use null if not set
        $traveler->tr_value_trip = $validatedData['tr_value_trip'] ?? null; // Use null if not set
        $traveler->tr_type_trip = json_encode($request->input('tr_type_trip'));
        $traveler->tr_desc = $validatedData['tr_desc'] ?? null; // Use null if not set

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
              'trvt_agent_id' =>$traveler->tr_agent_id,
              'tr_id' => $traveler->tr_id, 
              'trvt_name' => $task->pre_task_name, 
              'trvt_status' => 1
          ]);
      }
    
      $predefinedTasksCategory = PredefineTaskCategory::select('task_cat_name')->get();
      foreach ($predefinedTasksCategory as $taskcate) {
          $exists = TaskCategory::where('task_cat_name', $taskcate->task_cat_name)
                      ->exists();
      
          if (!$exists) {
              $tripTask_category = new TaskCategory();
              $tableName = $tripTask_category->getTable();
              $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "task_cat_id", $chars = 6);
      
              TaskCategory::create([
                  'id' => $user->users_id,
                  'task_cat_id' => $uniqueId1,
                  'task_cat_name' => $taskcate->task_cat_name,
                  'task_cat_status' => 1
              ]);
          }
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
        $triptinerary = TripItineraryDetail::where('tr_id', $trip->tr_id)->get();
        $typeoftrip = TypeOfTrip::where('tr_id', $trip->tr_id)->orderBy('trip_type_name','asc')->get();
        // dd($typeoftrip);

        $triptype = TripType::all();

        $tripstatus = TripStatus::all();


        $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
        if ($user->users_id && $user->role_id == 0) {
            $agency_users = $agency_users->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency_users = $agency_users->where('users_id', $user->users_id)->get();
        }
    

        //dd($tripstatus);

        $selectedStatus = $trip->status;
        //$status = Trip::where('status', $selectedStatus)->get();

        return view('masteradmin.trip.edit', compact('trip', 'triptype', 'tripmember', 'tripstatus', 'selectedStatus','agency_users','triptinerary','typeoftrip'));

        //return view('masteradmin.trip.edit',compact('trip','triptype', 'tripmember','tripstatus','status'));

    }

    public function update(Request $request, $id): RedirectResponse
    {
        // $rawItems = $request->input('items');
        // dd($rawItems);
     //dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $trip = Trip::where(['tr_id' => $id])->firstOrFail();
        if ($request->travelers == "travelers") {
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'tr_traveler_name' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'required|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
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
                'items.*.trtm_type' => 'nullable|string',
                'items.*.trtm_first_name' => 'nullable|string',
                'items.*.trtm_middle_name' => 'nullable|string',
                'items.*.trtm_last_name' => 'nullable|string',
                'items.*.trtm_nick_name' => 'nullable|string',
                'items.*.trtm_relationship' => 'nullable:items.*.trtm_type,1',
                'items.*.trtm_gender' => 'nullable:items.*.trtm_type,2',
                'items.*.trtm_dob' => 'nullable|string',
                'items.*.trtm_age' => 'nullable|string',
            ], [
    
                'tr_name.required' => 'Traveler name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'tr_traveler_name.required' => 'Traveler name is required',
                'tr_email.email' => 'Invalid email address',
                'tr_start_date.required' => 'Start date is required',
                'tr_end_date.required' => 'End Date Is Required',
      
            ]);
        }else{
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'tr_traveler_name' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'nullable|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
                'tr_num_people' => 'nullable|string',
                'tr_number' => 'nullable|string',
                'tr_start_date' => 'nullable',
                'tr_end_date' => 'nullable|string',
                'tr_value_trip' => 'nullable|string',
                'tr_desc' => 'nullable|string',
                'tr_country' => 'nullable|string',
                'tr_state' => 'nullable|string',
                'tr_city' => 'nullable|string',
                'tr_address' => 'nullable|string',
                'status' => 'nullable|numeric',
                'tr_zip' => 'nullable|numeric|digits_between:1,6',
               
            ], [
    
                'tr_name.required' => 'Traveler name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'tr_traveler_name.required' => 'Traveler name is required',
      
            ]);
    
        }
       
        // Update Trip record

        $validatedData['tr_type_trip'] = json_encode($request->input('tr_type_trip'));

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

            $predefinedTasks = PredefineTask::select('pre_task_name')
                ->where('pre_task_type', $validatedData['status'])
                ->get();
        
            foreach ($predefinedTasks as $task) {
                $existingTask = TripTask::where('tr_id', $id)
                    ->where('trvt_name', $task->pre_task_name)
                    ->first();
                
                if (!$existingTask) {
                    $tripTask = new TripTask();
                    $tableName = $tripTask->getTable();
                    $uniqueId1 = $this->GenerateUniqueRandomString($tableName, 'trvt_id', 6);
        
                    TripTask::create([
                        'id' => $user->users_id,
                        'trvt_id' => $uniqueId1,
                        'trvt_agent_id' =>$trip->tr_agent_id,
                        'tr_id' => $id,
                        'trvt_name' => $task->pre_task_name,
                        'trvt_status' => 1
                    ]);
                }
            }
        }

        $typeoftripDelete = TypeOfTrip::where('tr_id', $id)->delete();

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
                        $typeOfTrip->tr_id = $id;
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

        $tripItineraryDelete = TripItineraryDetail::where('tr_id', $id)->delete();
        $rawItemsItinerary = $request->input('itinerary');
        if (isset($rawItemsItinerary) && is_array($rawItemsItinerary) && count($rawItemsItinerary) > 0) {

            foreach ($rawItemsItinerary as $item) {
                $itineraryItem = new TripItineraryDetail();
                $tableNameitineraryItem = $itineraryItem->getTable();
                $uniqueId1itinerary = $this->GenerateUniqueRandomString($table = $tableNameitineraryItem, $column = "trit_id", $chars = 6);

                $itineraryItem->fill($item);

                $itineraryItem->tr_id = $id;
                $itineraryItem->id = $user->users_id;
                $itineraryItem->trit_status = 1;
                $itineraryItem->trit_id = $uniqueId1itinerary;

                $itineraryItem->save();
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
            $triptask = TripTask::where('tr_id', $id)->delete();
            $tripdocument = TravelerDocument::where('tr_id', $id)->delete();
            $trip->where('tr_id', $id)->delete();

            \MasterLogActivity::addToLog('Master Admin Trip Deleted.');

            return redirect()->route('trip.index')

                ->with('success', 'Trip deleted successfully');
        }
    }

    public function view($id): View
    {
    //    dd($id);
        $user = Auth::guard('masteradmins')->user();
        $trip1 = Trip::where('tr_id', $id)->firstOrFail();
        $triptableName = $trip1->getTable();

        
        $taskCategory = new TaskCategory();
        if($user->users_id && $user->role_id ==0 ){
            $taskCategory = $taskCategory->where(['task_cat_status' => 1])->get();
        }else{
            $taskCategory = $taskCategory->where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
        }
        //dd($TaskCategory);

        $documentType = DocumentType::get();

         $trip_id=$id;
        // dd($trip_id);
     
            $agency_users = new MasterUserDetails();
            $agency_users->setTableForUniqueId($user->user_id);
            $tableName = $agency_users->getTable();
            if($user->users_id && $user->role_id ==0 ){
                $agency_user = $agency_users->where('users_id', '!=', $user->users_id)->get(); 
            }else{
                $agency_user = $agency_users->where('users_id' , $user->users_id)->get(); 
            }
            //dd($user->user_id);
            
            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id); 
            $masterUserTable = $masterUserDetails->getTable();
            //dd($masterUserTable);
             $trips = new Trip();
             $tripTable = $trips->getTable();  

            // \DB::enableQueryLog();
            if($user->users_id && $user->role_id ==0 ){
                $trip = Trip::where($tripTable . '.tr_status', 1) 
                ->from($tripTable)  
                ->leftJoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                ->leftJoin('ta_countries', 'ta_countries.id', '=', $masterUserTable . '.users_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $masterUserTable . '.users_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $masterUserTable . '.users_state') 
                ->where($tripTable . '.tr_id', $id)
                ->select([
                    $tripTable . '.*',  
                    $masterUserTable . '.users_first_name', 
                    $masterUserTable . '.users_last_name',
                    $masterUserTable . '.users_email',
                    $masterUserTable . '.users_zip',
                    $masterUserTable . '.user_emergency_phone_number',
                    'ta_countries.name as country_name', 
                    'ta_cities.name as city_name', 
                    'ta_states.name as state_name'     
                ])
                ->firstOrFail();
            }else{
                $specificId = $user->users_id;
                $trip = Trip::where('tr_status', 1) 
                ->from($tripTable)  
                ->leftJoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                ->leftJoin('ta_countries', 'ta_countries.id', '=', $masterUserTable . '.users_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $masterUserTable . '.users_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $masterUserTable . '.users_state') 
                ->where(function($query) use ($tripTable, $user, $specificId) {
                    $query->where($tripTable . '.tr_agent_id', $user->users_id)
                        ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
                }) 
                ->where($tripTable . '.tr_id', $id)
                ->select([
                    $tripTable . '.*',  
                    $masterUserTable . '.users_first_name', 
                    $masterUserTable . '.users_last_name',
                    $masterUserTable . '.users_email',
                    $masterUserTable . '.users_zip',
                    $masterUserTable . '.users_email',
                    'ta_countries.name as country_name', 
                    'ta_cities.name as city_name', 
                    'ta_states.name as state_name'     
                ])
                ->firstOrFail();

            }
                //dd($trip);
                  // dd(\DB::getQueryLog()); 

            $taskstatus = TaskStatus::all();

            $tripTraveling = new TripTravelingMember();
            $tableName = $tripTraveling->getTable();

            $uniq_id = $user->user_id; // Ensure this is set based on your authentication logic

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

        //last code
        $member = TripTravelingMember::where(['tr_id' => $id])->get();
        $task = TripTask::where(['tr_id' => $id])->with(['taskstatus','tripCategory'])->latest()->get();
        $document = TravelerDocument::where(['tr_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
        
        // dd($task);            
        //dd($tripTraveling);

        return view('masteradmin.trip.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripData', 'tripTravelingMembers','taskstatus','agency_user','trip_id','member','task','document'));
    }

    public function travelersDetails(): View
    {
        $user = Auth::guard('masteradmins')->user();
        
        // $trip = Trip::where(['tr_status' => 1, 'id' => $user->users_id])->get();
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();

        if ($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
        }

        $trips = new Trip();
        
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;

        if($user->users_id && $user->role_id ==0 ){

            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->leftjoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.tr_country') 
            ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.tr_city') 
            ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.tr_state') 
            ->select([
                $tripTable . '.*',
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name',
                'ta_countries.name as country_name', 
                'ta_cities.name as city_name', 
                'ta_states.name as state_name' 
            ]);
        
            
        }else{
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->leftjoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.tr_country') 
            ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.tr_city') 
            ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.tr_state') 
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name',
                'ta_countries.name as country_name', 
                'ta_cities.name as city_name', 
                'ta_states.name as state_name'  
            ]);
        }

        $trip = $tripQuery->get();

        
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
        
        // $agency_user = $agency_users->get(); 

        if ($user->users_id && $user->role_id == 0) {
            $agency_user = $agency_users->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency_user = $agency_users->where('users_id', $user->users_id)->get();
        }

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
        // $agency_users = $agency_users->get();
        if ($user->users_id && $user->role_id == 0) {
            $agency_users = $agency_users->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency_users = $agency_users->where('users_id', $user->users_id)->get();
        }



        return view('masteradmin.traveler.edit', compact('trip', 'triptype', 'tripmember', 'country', 'states', 'city','agency_users'));
    }

    public function viewDetails($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();
        $trips = new Trip();
        $tripTable = $trips->getTable();  

        $trip = Trip::where('tr_id', $id)
          ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.tr_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.tr_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.tr_state')
                ->select([
                    $tripTable . '.*',  
                    'ta_countries.name as country_name', 
                    'ta_cities.name as city_name', 
                    'ta_states.name as state_name'     
                ])
                ->firstOrFail();
         // dd($trip);      
        $triptableName = $trip->getTable();

        $tripTraveling = new TripTravelingMember();
        $tableName = $tripTraveling->getTable();

        $uniq_id = $user->user_id; // Ensure this is set based on your authentication logic



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

                
            if($user->users_id && $user->role_id ==0 ){
                $taskCategory = TaskCategory::where(['task_cat_status' => 1])->get();
            }else{
                $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
            }

       
        //  $tripTraveling = TripTravelingMember::where(['trtm_status' => 1, 'id' => $user->id, 'tr_id' => $id])->get();
        $documentType = DocumentType::get();

          //last code
          $member = TripTravelingMember::where(['tr_id' => $id])->get();
          $document = TravelerDocument::where(['tr_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
          
          $trip_id=$id;

            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id); 
            $masterUserTable = $masterUserDetails->getTable();
            //dd($masterUserTable);
             $trips = new Trip();
             $tripTable = $trips->getTable();  

             $trip_status = TripStatus::get();

           $trip_history = Trip::where($tripTable . '.tr_status', 1) 
                ->from($tripTable)  
                ->leftJoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                ->where($tripTable . '.tr_id', $id)
                ->select([
                    $tripTable . '.*',  
                    $masterUserTable . '.users_first_name', 
                    $masterUserTable . '.users_last_name',
                    $masterUserTable . '.users_email',
                    $masterUserTable . '.users_zip',
                    $masterUserTable . '.user_emergency_phone_number'
                ])
                ->with(['trip_status'])
                ->get();

        // dd($trip_history);
        return view('masteradmin.traveler.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripTravelingMembers', 'tripData','member','document','trip_id','trip_history'));
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
        if($user->users_id && $user->role_id ==0 ){
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get(); 
        }else{
            $agency = $masterUserDetails->where('users_id' , $user->users_id)->get(); 
        }

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

    public function follow_up_after(Request $request)
    {
        $access = view()->shared('access');
    
        $user = Auth::guard('masteradmins')->user();
    
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
    
        if($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get(); 
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
        }
    
        $trip_status = TripStatus::get();
    
        $trips = new Trip();
        $tripTable = $trips->getTable();

        $traveller = Trip::where('id',$user->users_id)->get();
        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.status', 9) // Pending trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.status', 9) // Pending trips
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }
        // Apply filters if available
        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }
    
        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
        }
    
        // If the request is an AJAX request, return the filtered data
        if ($request->ajax()) {
            return Datatables::of($tripQuery)
                ->addIndexColumn()
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return trim(($document->users_first_name ?? '') . ' ' . ($document->users_last_name ?? ''));
                })
                ->addColumn('traveler_name', function ($document) {
                    return optional($document->trip)->tr_traveler_name ?? '';
                })
                ->addColumn('task_status_name', function ($document) {
                    $statusName = optional($document->trip_status)->tr_status_name ?? '';

                    if ($statusName == 'In Process') {
                        $buttonColor = '#F6A96D';
                    } else{
                        $buttonColor = '';
                    }
    
                    // Return the button HTML
                    return '<button type="button" class="btn text-white" style="background-color: ' . $buttonColor . ';">' . $statusName . '</button>';
                })
                ->addColumn('tr_start_date', function ($document) {
                    return optional($document->tr_start_date) ? Carbon::parse($document->tr_start_date)->format('M d, Y') : '';
                })
                ->addColumn('due_date', function ($document) {
                    return optional($document->tr_start_date) ? Carbon::parse($document->tr_start_date)->format('M d, Y') : '';
                })
                ->rawColumns(['task_status_name'])
                ->toJson();
        }
    
        // For the initial page load, fetch the trips
        $trip = $tripQuery->get();
        // dd($trip);
        // If the request is AJAX, return the filtered results
      
    
        // Return the main page view
        return view('masteradmin.follow_up.index', compact('trip', 'agency', 'trip_status','traveller'));
    }
    

    public function follow_up_after_complete(Request $request)
    {
        $access = view()->shared('access');
    
        $user = Auth::guard('masteradmins')->user();
    
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
    
        if($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get(); 
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
        }
    
        $trip_status = TripStatus::get();
    
        $trips = new Trip();
        $tripTable = $trips->getTable();

        $traveller = Trip::where('id',$user->users_id)->get();
        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.id', $user->users_id)
            ->where($tripTable . '.status', 7) // complete trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where($tripTable . '.id', $user->users_id)
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->where($tripTable . '.status', 7) // complete trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);

        }
        // Apply filters if available
        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }
    
        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
        }
    
        // If the request is an AJAX request, return the filtered data
        if ($request->ajax()) {
            return Datatables::of($tripQuery)
                ->addIndexColumn()
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return trim(($document->users_first_name ?? '') . ' ' . ($document->users_last_name ?? ''));
                })
                ->addColumn('traveler_name', function ($document) {
                    return optional($document->trip)->tr_traveler_name ?? '';
                })
                ->addColumn('task_status_name', function ($document) {
                    $statusName = optional($document->trip_status)->tr_status_name ?? '';

                    if ($statusName == 'Trip Completed') {
                        $buttonColor = '#C5A070';
                    } else{
                        $buttonColor = '';
                    }
    
                    // Return the button HTML
                    return '<button type="button" class="btn text-white" style="background-color: ' . $buttonColor . ';">' . $statusName . '</button>';
                })
                ->addColumn('trip_date', function ($document) {
                    $startDate = optional($document->tr_start_date) ? Carbon::parse($document->tr_start_date)->format('M d, Y') : '';
                    $endDate = optional($document->tr_end_date) ? Carbon::parse($document->tr_end_date)->format('M d, Y') : '';
                    
                    return $startDate && $endDate ? "$startDate - $endDate" : ($startDate ?: $endDate);
                })
                ->addColumn('complete_days', function ($document) {
                    $currentDate = Carbon::now()->startOfDay(); 
                    $endDate = $document->tr_end_date;
                
                    // Check if end date exists
                    if (!$endDate) {
                        return '';
                    }
                
                    // Parse the end date and ensure it's in the correct format
                    try {
                        $endDateParsed = Carbon::createFromFormat('m/d/Y', $endDate)->startOfDay();
                    } catch (\Exception $e) {
                        return ''; // Return empty if the date format is invalid
                    }
                
                    // Calculate days since completion (positive if in the past, 0 if today, negative if future)
                    $daysSinceCompletion = $endDateParsed->lt($currentDate) 
                        ? $endDateParsed->diffInDays($currentDate) 
                        : 0;
                
                    return $daysSinceCompletion.' days';
                })
                ->rawColumns(['task_status_name'])
                ->toJson();
        }
    
        // For the initial page load, fetch the trips
        $trip = $tripQuery->get();
        // dd($trip);
        // If the request is AJAX, return the filtered results
       
        // Return the main page view
        return view('masteradmin.follow_up.index', compact('trip', 'agency', 'trip_status','traveller'));
    }
    
  
    public function listView(Request $request)
    {
        // dd('hello');
        $user = Auth::guard('masteradmins')->user();

        $startDate = $request->input('start_date'); 
        $endDate = $request->input('end_date');   
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
        $trip_status1 = $request->input('trip_status');   

        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();

        if ($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
        
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
        }
       // $agency = $masterUserTable->get();
        $trip_status = TripStatus::get();

        
        $trips = new Trip();
        
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;
        if($user->users_id && $user->role_id ==0 ){
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }else{
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
        }

       

                
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
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }


        $trip = $tripQuery->get();
        

        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.list_view', compact('trip', 'agency', 'trip_status'))->render();
         }
        // dd($trip);

        return view('masteradmin.trip.list_view', compact('trip', 'agency', 'trip_status'));
 }
 
    
//  public function gridView(Request $request)
//  {
//      $user = Auth::guard('masteradmins')->user();
 
//      $startDate = $request->input('start_date'); 
//      $endDate = $request->input('end_date');   
//      $trip_agent = $request->input('trip_agent');   
//      $trip_traveler = $request->input('trip_traveler');   
//      $trip_status1 = $request->input('trip_status');   
 
//      $masterUserDetails = new MasterUserDetails();
//      $masterUserDetails->setTableForUniqueId($user->user_id); 
//      $masterUserTable = $masterUserDetails->getTable();
 
//      if ($user->users_id && $user->role_id == 0) {
//          $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
//      } else {
//          $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
//      }
 
//      $trip_status = TripStatus::get();
// //  dd(     $trip_status);
//      $trips = new Trip();
//      $tripTable = $trips->getTable();
//      $specificId = $user->users_id;
 
//      // Define the query based on user's role
//      if ($user->users_id && $user->role_id == 0) {
//          $tripQuery = Trip::where('tr_status', 1)
//              ->from($tripTable)
//              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
//              ->select([
//                  $tripTable . '.*', 
//                  $masterUserTable . '.users_first_name', 
//                  $masterUserTable . '.users_last_name' 
//              ]);
//      } else {
//          $tripQuery = Trip::where('tr_status', 1)
//              ->from($tripTable)
//              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
//              ->where(function($query) use ($tripTable, $user, $specificId) {
//                  $query->where($tripTable . '.tr_agent_id', $user->users_id)
//                        ->orWhere($tripTable . '.id', $specificId);  
//              })
//              ->select([
//                  $tripTable . '.*', 
//                  $masterUserTable . '.users_first_name', 
//                  $masterUserTable . '.users_last_name' 
//              ]);
//      }
 
//      $today = Carbon::today('Asia/Kolkata'); 
 
//      $tripQuery->orderByRaw("CASE WHEN tr_start_date = ? THEN 0 ELSE 1 END, tr_start_date DESC", [$today]);
 
//      // Apply filters based on date, agent, traveler, and status
//      if ($startDate && !$endDate) {
//          $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
//      } elseif ($startDate && $endDate) {
//          $tripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
//                    ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
//      }
 
//      if ($trip_agent) {
//          $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
//      }
 
//      if ($trip_traveler) {
//          $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
//      }
 
//      if ($trip_status1) {
//          $tripQuery->where($tripTable . '.status', $trip_status1);
//      }
 
//      // Execute the query and group results by 'status' or 'tr_status' (adjust field name if necessary)
//      $tripResults = $tripQuery->get();
//      $tripsGrouped = $tripResults->groupBy('status'); // Adjust 'status' based on actual field in your table
 
//      // Return the view with the grouped data
//      if ($request->ajax()) {
//          return view('masteradmin.trip.workflow', compact('tripResults', 'agency', 'trip_status', 'tripsGrouped'))->render();
//      }
 
//      return view('masteradmin.trip.workflow', compact('tripResults', 'agency', 'trip_status', 'tripsGrouped'));
//  }
 
    
public function gridView(Request $request)
{
    $user = Auth::guard('masteradmins')->user();
    
    // Input data from request
    $startDate = $request->input('start_date'); 
    $endDate = $request->input('end_date');   
    $trip_agent = $request->input('trip_agent');   
    $trip_traveler = $request->input('trip_traveler');   
    $trip_status1 = $request->input('trip_status');   

    // Initialize the MasterUserDetails model with dynamic table
    $masterUserDetails = new MasterUserDetails();
    $masterUserDetails->setTableForUniqueId($user->user_id); 
    $masterUserTable = $masterUserDetails->getTable();

    // Fetch TripStatus records
    $trip_status = TripStatus::get();
    
    $trips = new Trip();
    $tripTable = $trips->getTable();
    $specificId = $user->users_id;
    
    // Define the query based on user's role
    if ($user->users_id && $user->role_id == 0) {
        // Admin or superuser role query
        $tripQuery = Trip::from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->join('trip_status', 'trip_status.tr_status_id', '=', $tripTable . '.status')  // Adjust join condition
            ->select([
                $tripTable . '.*',
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name', 
                'trip_status.tr_status_name'  // Fetch status name
            ]); // Default status filter for active records
    } else {
        // Other user role logic
        $tripQuery = Trip::from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->join('trip_status', 'trip_status.tr_status_id', '=', $tripTable . '.status')  // Adjust join condition
            ->where(function ($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId for filtering
            })
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name', 
                'trip_status.tr_status_name'  // Fetch status name
            ]);  // Default status filter for active records
    }

    // Apply filters for date range, agent, traveler, and status
    if ($startDate && !$endDate) {
        $tripQuery->whereDate($tripTable . '.tr_start_date', '=', Carbon::parse($startDate)->format('Y-m-d'));
    } elseif ($startDate && $endDate) {
        $tripQuery->whereBetween($tripTable . '.tr_start_date', [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d')
        ]);
    }

    if ($trip_agent) {
        $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
        $tripQuery->where($tripTable . '.tr_traveler_name', $trip_traveler);
    }

    if ($trip_status1) {
        $tripQuery->where($tripTable . '.status', $trip_status1);
    }

    // Execute the query
    $tripResults = $tripQuery->get();
    
    // Group the trips by status id (status)
    $tripsGrouped = $tripResults->groupBy('status'); // Grouping by status ID
    $totalsByStatus = $tripResults->groupBy('status')->map(function ($group) {
        return $group->sum('tr_value_trip'); // Sum the 'tr_value_trip' for each group
    });
// dd($tripsGrouped);
    // Return the view with grouped results
    if ($request->ajax()) {
        return view('masteradmin.trip.workflow', compact('tripResults', 'trip_status', 'tripsGrouped','totalsByStatus'))->render();
    }

    return view('masteradmin.trip.workflow', compact('tripResults', 'trip_status', 'tripsGrouped','totalsByStatus'));
}

public function updateStatus(Request $request)
{
  
    $tripId = $request->input('trip_id');
    $newStatus = $request->input('status');

    if(!empty( $newStatus))
    {
        $newStatus = $request->input('status'); 
    }else{
        $newStatus = '0';
    }
    // dd($newStatus);
    // Initialize the Trip model
    $trip = new Trip();

    // Set the table dynamically
    $user = Auth::guard('masteradmins')->user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $trip->setTableForUniqueId($user->user_id);

    // Log the table name
    \Log::info('Using table: ' . $trip->getTable());

    // Fetch the trip record
    $tripRecord = $trip->where('tr_id', $tripId);

    if (!$tripRecord->exists()) {
        return response()->json(['error' => 'Trip not found'], 404);
    }
    
    // Update the status
    $updated = $tripRecord->where('tr_id', $tripId)->update(['status' => $newStatus]);
    
    if ($updated) {
        return response()->json(['success' => 'Trip status updated successfully']);
    } else {
        return response()->json(['error' => 'Failed to update trip status'], 500);
    }
}





   
}

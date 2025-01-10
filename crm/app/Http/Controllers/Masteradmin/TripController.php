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
use App\Models\TravelingRelationship;
use App\Models\TripPreference;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\TripDocument;
use Illuminate\Validation\ValidationException;


class TripController extends Controller
{
    
    public function index(Request $request)
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $startDate = $request->input('start_date'); 
        $endDate = $request->input('end_date');   
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
        $trip_status1 = $request->input('trip_status');   

        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
        $trips = new Trip();
       

        $tripTable = $trips->getTable();
        if ($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->get();
        
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
        }
       // $agency = $masterUserTable->get();
        $trip_status = TripStatus::get();
        $specificId = $user->users_id;

        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();

        if($user->users_id && $user->role_id ==0 ){
            $trips_traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
           // dd($trips_traveller);
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $trips_traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->where(function($query) use ($travelerTable, $user, $specificId) {
                $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                    ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
        }
     
       
        if($user->users_id && $user->role_id ==0 ){
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }else{
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);

        }
            // Execute the query
            $tripResults = $tripQuery->get();

            // dD($tripResults);
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
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }


        $trip = $tripQuery->get();
        

        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.filtered_results', compact('trip', 'agency', 'trip_status','trips_traveller'))->render();
         }
        // dd($trip);

        return view('masteradmin.trip.index', compact('trip', 'agency', 'trip_status','tripsGrouped','trips_traveller'));
    }

    public function create(): View
    {

        $user = Auth::guard('masteradmins')->user();
        //$dynamicId = $user->id;

        $triptype = TripType::orderBy('ty_name', 'ASC')->get();

       $agency_users = new MasterUserDetails();
       $agency_users->setTableForUniqueId($user->user_id);
       $tableName = $agency_users->getTable();
       if ($user->users_id && $user->role_id == 0) {
            $agency_user = $agency_users->get();
        } else {
            $agency_user = $agency_users->where('users_id', $user->users_id)->get();
        }
        
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
    
        $tripstatus = TripStatus::all();
        $travelingrelationship = TravelingRelationship::where(['rel_status' => 1])->get();
         $trips = new Trip();
      
        $tripTable = $trips->getTable();
            $specificId = $user->users_id;
            
            $traveler = new TripTravelingMember();
            $travelerTable = $traveler->getTable();
    
          if($user->users_id && $user->role_id ==0 ){
            $trips_traveller = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id')
            ->select([
                $tripTable . '.*', 
                $tripTable . '.tr_traveler_id' ,
                
            ])->get();
            // $trips_traveller =Trip::get();
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $trips_traveller = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $tripTable . '.*', 
                $tripTable . '.tr_traveler_id' 
            ])->get();

            
        }
    //  dd($trips_traveller);
       
    //    dd($aency_user);
    
     $country = Countries::all();

        return view('masteradmin.trip.create', compact('triptype','agency_user','tripstatus','travelingrelationship','user','trips_traveller','country'));
    }

    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id;
        
        
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'traveler_id' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'nullable|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
                'tr_num_people' => 'nullable|string',
                'tr_number' => 'nullable|string',
                'tr_start_date' => 'nullable',
                'tr_end_date' => 'nullable|string',
                'tr_final_payment_date' => 'nullable|string',
                'tr_value_trip' => 'nullable|string',
                'tr_final_amount' => 'nullable|string',
                'tr_desc' => 'nullable|string',
                'tr_country' => 'nullable|string',
                'tr_state' => 'nullable|string',
                'tr_city' => 'nullable|string',
                'tr_address' => 'nullable|string',
                'tr_zip' => 'nullable|numeric|digits_between:1,6',
                'status' => 'nullable|numeric',
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
    
                'tr_name.required' => 'Trip Name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'traveler_id.required' => 'Lead Traveler is required',
                'tr_email.regex' => 'Please enter a valid email address.',
      
            ]);
    
        
        // dd('data');

        // Store data
        $traveler = new Trip();
        $tableName = $traveler->getTable();
        $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "tr_id", $chars = 6);
        $traveler->tr_id = $uniqueId;

        

        $existingtrip = $traveler->where('tr_email', $validatedData['tr_email'])->first();


        // if (!empty($validatedData['traveler_id'])) {
        //     $traveler->tr_num_people = ($validatedData['tr_num_people'] ?? 0) + 1;
        // }
        $documentimg = '';
        if ($request->hasFile('trp_document')) {

            if (is_array($request->file('trp_document'))) {
                
                $userFolder = session('userFolder');
                $documents_images =  $this->handleImageUpload($request, 'trp_document', null, 'trip_document', $userFolder);
                //dd($documents_images);
                $documentimg = json_encode($documents_images);

            }
        } 

        $traveler->id = $user->users_id;
        $traveler->tr_name = $validatedData['tr_name'] ?? null;
        $traveler->tr_agent_id = $validatedData['tr_agent_id'];
        $traveler->tr_traveler_id = $validatedData['traveler_id'];
        $traveler->tr_dob = $validatedData['tr_dob'] ?? null; // Use null if not set
        $traveler->tr_age = $validatedData['tr_age'] ?? null; // Use null if not set
        $traveler->tr_number = $validatedData['tr_number'] ?? null; // Use null if not set
        $traveler->tr_email = $validatedData['tr_email'] ?? null; // Use null if not set
        $traveler->tr_phone = $validatedData['tr_phone'] ?? null; // Use null if not set
        $traveler->tr_num_people = $validatedData['tr_num_people'] ?? null; // Use null if not set
        $traveler->tr_start_date = $validatedData['tr_start_date'] ?? null;
        $traveler->tr_end_date = $validatedData['tr_end_date'] ?? null; // Use null if not set
        $traveler->tr_final_payment_date = $validatedData['tr_final_payment_date'] ?? null; 
        $traveler->tr_value_trip = $validatedData['tr_value_trip'] ?? null; // Use null if not set
        $traveler->tr_final_amount = $validatedData['tr_final_amount'] ?? null; 
        $traveler->tr_type_trip = json_encode($request->input('tr_type_trip')) ?? [];
        $traveler->tr_desc = $validatedData['tr_desc'] ?? null; // Use null if not set

        $traveler->tr_country = $validatedData['tr_country'] ?? null;
        $traveler->tr_state = $validatedData['tr_state'] ?? null;
        $traveler->tr_city = $validatedData['tr_city'] ?? null;
        $traveler->tr_address = $validatedData['tr_address'] ?? null;
        $traveler->tr_zip = $validatedData['tr_zip'] ?? null;
        // $traveler->trp_document = $documentimg ?? '';
        // $traveler->trp_name = $request->input('trp_name') ?? '';

        $traveler->status = $validatedData['status'] ?? '1';
        if (array_key_exists('status', $validatedData) && $validatedData['status'] == '5') {
            $traveler->tr_booking_date = now()->toDateString();
        }

        $traveler->tr_status = 1;
        $traveler->save();

        TripTravelingMember::
        where('lead_id', $request->input('traveler_id'))
        ->where('lead_status',2)
        ->delete();
        
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
                $travelerItem->lead_id = $validatedData['traveler_id'];
                $travelerItem->lead_status = 2;
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


      if (isset($validatedData['status'])) {

        $predefinedTasks = PredefineTask::select('pre_task_name','pre_priority')
            ->where('pre_task_type', $validatedData['status'])
            ->get();
    
            foreach ($predefinedTasks as $task) {
                $tripTask = new TripTask();
                $tableName = $tripTask->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvt_id", $chars = 6);
          
                  TripTask::create([
                      'id'=>$user->users_id,
                      'trvt_id' =>$uniqueId1,
                      'trvt_agent_id' =>$traveler->tr_agent_id,
                      'trvt_category' => '0',
                      'tr_id' => $traveler->tr_id, 
                      'trvt_name' => $task->pre_task_name, 
                      'trvt_priority' => $task->pre_priority, 
                      'trvt_status' => 1,
                      'status' => 1
                  ]);
              }
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

    $trp_name = $request->input('trp_name') ?? '';
    if ($request->hasFile('trp_document')) {

        if (is_array($request->file('trp_document'))) {
            
            $userFolder = session('userFolder');
            $documents_images =  $this->handleImageUpload($request, 'trp_document', null, 'trip_document', $userFolder);
            //dd($documents_images);
            $documentimg = json_encode($documents_images);

        }
    } 


      TripDocument::create([
        'id' => $user->users_id,
        'tr_id' =>  $traveler->tr_id,
        'trp_name' =>  $trp_name ?? '',
        'trp_document' =>  $documentimg ?? '',
        'trp_status' =>  1,
      ]);
            \MasterLogActivity::addToLog('Master Admin Trip Created.');

            return redirect()->route('trip.index')
                ->with('success', 'Trip created successfully.');
        
    }

    public function edit($id)
    {
        // dd($id);
        $trip = Trip::where('tr_id', $id)->with( 'lead_traveler_name')->firstOrFail();
        
        $tripmember = TripTravelingMember::where('lead_id', $trip->tr_traveler_id)
        // ->orWhere('trtm_id', $trip->tr_traveler_id)
        ->get();
        // dd($tripmember);
                // dd($trip->tr_traveler_id);
        $triptinerary = TripItineraryDetail::where('tr_id', $trip->tr_id)->get();
        $typeoftrip = TypeOfTrip::where('tr_id', $trip->tr_id)->orderBy('trip_type_name','asc')->get();
        // dd($trip);

        $triptype = TripType::orderBy('ty_name', 'ASC')->get();

        $tripstatus = TripStatus::all();


        $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
        if ($user->users_id && $user->role_id == 0) {
            $agency_users = $agency_users->get();
        } else {
            $agency_users = $agency_users->where('users_id', $user->users_id)->get();
        }
    
        $travelingrelationship = TravelingRelationship::where(['rel_status' => 1])->get();
        //dd($tripstatus);

        $selectedStatus = $trip->status;
        $country = Countries::all();
     
      

            $membersCount = TripTravelingMember::with(['travelingrelationship'])
            ->where('lead_id', $trip->tr_traveler_id)
            ->where('lead_status', '!=', 1) // Exclude lead status 1
            ->count();
    
            // Fetch count of records from the custom table
            $uniq_id = $user->user_id; 
            $tripDataCount = \DB::table($uniq_id . '_tc_trip_traveling_member')
                ->where('trtm_id', $trip->tr_traveler_id)
                ->where('lead_status', 1) // Only the main lead (lead_status = 1)
                ->count();
                $memberTotalCount = $membersCount + $tripDataCount;
      
     
        $tripdocument = TripDocument::where('tr_id', $id)->get();
    //    dd($tripdocument);
    
        // $tripstatus = TripStatus::get();
        $triptask = PredefineTask::get();
        // dd($triptask);
        //$status = Trip::where('status', $selectedStatus)->get();
        $taskCategory = new TaskCategory();
        if($user->users_id && $user->role_id ==0 ){
            $taskCategory = $taskCategory->where(['task_cat_status' => 1])->get();
        }else{
            $taskCategory = $taskCategory->where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
        }
        $taskstatus = TaskStatus::all();


        return view('masteradmin.trip.edit', compact('trip', 'triptype', 'tripmember', 'tripstatus', 'selectedStatus','agency_users','triptinerary','typeoftrip','travelingrelationship','country','user','memberTotalCount','tripdocument','triptask','taskCategory','taskstatus'));

        //return view('masteradmin.trip.edit',compact('trip','triptype', 'tripmember','tripstatus','status'));

    }

    public function update(Request $request, $id): RedirectResponse
    {
        // $rawItems = $request->input('items');
        // dd($rawItems);

     //dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $trip = Trip::where(['tr_id' => $id])->firstOrFail();
      
            $validatedData = $request->validate([
                'tr_name' => 'required|string',
                'tr_agent_id' => 'required|string',
                'traveler_id' => 'required|string',
                'tr_dob' => 'nullable|string',
                'tr_age' => 'nullable|string',
                'tr_email' => 'nullable|email',
                'tr_phone' => 'nullable|string|regex:/^[0-9]{1,12}$/',
                'tr_num_people' => 'nullable|string',
                'tr_number' => 'nullable|string',
                'tr_start_date' => 'nullable',
                'tr_end_date' => 'nullable|string',
                'tr_final_payment_date' => 'nullable|string',
                'tr_value_trip' => 'nullable|string',
                'tr_final_amount' => 'nullable|string',
                'tr_desc' => 'nullable|string',
                'tr_country' => 'nullable|string',
                'tr_state' => 'nullable|string',
                'tr_city' => 'nullable|string',
                'tr_address' => 'nullable|string',
                'status' => 'nullable|numeric',
                'trp_name' => 'nullable|string',
                'tr_zip' => 'nullable|numeric|digits_between:1,6',
               
            ], [
    
                'tr_name.required' => 'Trip name is required',
                'tr_agent_id.required' => 'Agent ID is required',
                'traveler_id.required' => 'Lead Traveler is required',
                'tr_email.regex' => 'Please enter a valid email address.',
            
            ]);
           // dd($request->all());
           $document_images = [];

        // Check if existing images are present
        if ($trip->trp_document) {
            $existingImages = json_decode($trip->trp_document, true);
            if (is_array($existingImages)) {
                $document_images = $existingImages;
            }
        }

        $userFolder = session('userFolder');

        // $newImages = []; // Ensure $newImages is initialized as an array
        // if (is_array($request->file('trp_document'))) {
        //     // Upload multiple images
        //     $newImages = $this->handleImageUpload($request, 'trp_document', null, 'trip_document', $userFolder);
        //     // Ensure $newImages is an array
        //     if (is_array($newImages)) {
        //         $document_images = array_merge($document_images, $newImages);
        //     }
        // }
            // $validatedData['trp_document'] = $document_images;
           

            $data = [
                'tr_name' => $request->input('tr_name'),
                'tr_agent_id' => $request->input('tr_agent_id'),
                'tr_traveler_id' => $request->input('traveler_id'),  // Map traveler_id to tr_traveler_id
                'tr_dob' => $request->input('tr_dob'),
                'tr_age' => $request->input('tr_age'),
                'tr_email' => $request->input('tr_email'),
                'tr_phone' => $request->input('tr_phone'),
                'tr_num_people' => $request->input('tr_num_people'),
                'tr_number' => $request->input('tr_number'),
                'tr_start_date' => $request->input('tr_start_date'),
                'tr_end_date' => $request->input('tr_end_date'),
                'tr_final_payment_date' => $request->input('tr_final_payment_date'),
                'tr_value_trip' => $request->input('tr_value_trip'),
                'tr_final_amount' => $request->input('tr_final_amount'),
                'tr_desc' => $request->input('tr_desc'),
                'tr_country' => $request->input('tr_country'),
                'tr_state' => $request->input('tr_state'),
                'tr_city' => $request->input('tr_city'),
                'tr_address' => $request->input('tr_address'),
                'status' => $request->input('status'),
                'tr_zip' => $request->input('tr_zip'),
                // 'trp_name' => $request->input('trp_name'),
                // 'trp_document' => $document_images,
                'tr_type_trip' => json_encode($request->input('tr_type_trip', []))
            ];

            if (array_key_exists('status', $validatedData) && $validatedData['status'] == '5') {
                $data['tr_booking_date'] = now()->toDateString();  
            }
        

            $trip->where(['tr_id' => $id])->update($data);

        // Update the status of TripTravelingMember without deleting it
        //TripTravelingMember::where('tr_id', $id)->update(['trtm_status' => 1]);


        TripTravelingMember::
        where('lead_id', $request->input('traveler_id'))
        ->where('lead_status',2)
        ->delete();

        $rawItems = $request->input('items');
            //dd($rawItems);
        if (isset($rawItems) && is_array($rawItems) && count($rawItems) > 0) {
            foreach ($rawItems as $item) {
                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);

                $travelerItem->fill($item);

                $travelerItem->tr_id = $id;
                $travelerItem->id = $user->users_id;
                $travelerItem->trtm_status = 1;
                $travelerItem->lead_id = $validatedData['traveler_id'];
                $travelerItem->lead_status = 2;
                $travelerItem->trtm_id = $uniqueId1;

                $travelerItem->save();
            }
        }

        if (isset($validatedData['status'])) {

            $predefinedTasks = PredefineTask::select('pre_task_name','pre_priority')
                ->where('pre_task_type', $validatedData['status'])
                ->get();
        // dD($predefinedTasks);
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
                        'trvt_category' => '0',
                        'tr_id' => $id,
                        'trvt_name' => $task->pre_task_name,
                        'trvt_priority' => $task->pre_priority, 
                        'trvt_status' => 1,
                        'status' => 1
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

        $trp_name = $request->input('trp_name') ?? '';
    if ($request->hasFile('trp_document')) {

        if (is_array($request->file('trp_document'))) {
            
            $userFolder = session('userFolder');
            $documents_images =  $this->handleImageUpload($request, 'trp_document', null, 'trip_document', $userFolder);
            //dd($documents_images);
            $documentimg = json_encode($documents_images);

        }
    } 


      TripDocument::create([
        'id' => $user->users_id,
        'tr_id' =>  $id,
        'trp_name' =>  $trp_name ?? '',
        'trp_document' =>  $documentimg ?? '',
        'trp_status' =>  1,
      ]);
 

            \MasterLogActivity::addToLog('Master Admin Trip Updated.');
            return redirect()->route('trip.index')

                ->with('success', 'Trip updated successfully');
        
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
        $user_id = $user->users_id;
        $trip1 = Trip::where('tr_id', $id)->firstOrFail();
        $triptableName = $trip1->getTable();
        $traveler_id = $trip1->tr_traveler_id;
        
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
                $agency_user = $agency_users->get(); 
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

            $traveler = new TripTravelingMember();
            $travelerTable = $traveler->getTable();
            
            if($user->users_id && $user->role_id ==0 ){
                $trip = Trip::where($tripTable . '.tr_status', 1) 
                ->from($tripTable)  
                ->leftJoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                ->leftJoin('ta_countries', 'ta_countries.id', '=', $masterUserTable . '.users_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $masterUserTable . '.users_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $masterUserTable . '.users_state') 
                ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
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
                    'ta_states.name as state_name',
                    $travelerTable . '.trtm_first_name',  
                    $travelerTable . '.trtm_last_name'      
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
                ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
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
                    'ta_states.name as state_name',
                    $travelerTable . '.trtm_first_name',  
                    $travelerTable . '.trtm_last_name'      
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
            ->where('lead_id', $traveler_id)->where('lead_status','!=',1)
            ->get();

            $tripData = \DB::table($uniq_id . '_tc_trip_traveling_member')
          ->select( 'trtm_first_name', 'trtm_age','trtm_id')
          ->where('trtm_id', $traveler_id)
          ->where('lead_status', 1)//main lead
          ->get();

        //last code
        $member = TripTravelingMember::with(['travelingrelationship'])->where(['tr_id' => $id])->get();
        // dd($member);
        $task = TripTask::where(['tr_id' => $id])->with(['taskstatus','tripCategory'])->latest()->get();
        $document = TravelerDocument::where(['tr_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
        
        $travelingrelationship = TravelingRelationship::where(['rel_status' => 1])->get();

        $tripTypes = TripType::whereIn('ty_name', ['Air', 'Cruise', 'Hotel', 'Resort', 'Guided Tours', 'Car Rental', 'Theme Park'])
        ->where('ty_status', '1')
        ->orderByRaw("FIELD(ty_name, 'Air', 'Cruise', 'Hotel', 'Resort', 'Guided Tours', 'Car Rental', 'Theme Park')")
        ->get(['ty_id', 'ty_name']);

        $preference = TripPreference::where('id', $user->users_id)->where('tr_id', $id)
        ->first();

        $membersCount = TripTravelingMember::with(['travelingrelationship'])
        ->where('lead_id', $traveler_id)
        ->where('lead_status', '!=', 1) // Exclude lead status 1
        ->count();

        // Fetch count of records from the custom table
        $uniq_id = $user->user_id; 
        $tripDataCount = \DB::table($uniq_id . '_tc_trip_traveling_member')
            ->where('trtm_id', $traveler_id)
            ->where('lead_status', 1) // Only the main lead (lead_status = 1)
            ->count();
            $memberTotalCount = $membersCount + $tripDataCount;

        // dd($totalCount);            
        //dd($tripTraveling);
        $tripdocument = TripDocument::where('tr_id', $id)->get();


        return view('masteradmin.trip.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripData', 'tripTravelingMembers','taskstatus','agency_user','trip_id','member','task','document','travelingrelationship','user_id','uniq_id','traveler_id','tripTypes','preference','memberTotalCount','tripdocument'));
    }

    public function travelersDetails(): View
    {
        $user = Auth::guard('masteradmins')->user();
        
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();

        if ($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
        }

        $trips = new TripTravelingMember();
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;

        if ($user->users_id && $user->role_id == 0) {
            $tripQuery = TripTravelingMember::where('trtm_status', 1)
                ->where('lead_status', 1) 
                ->from($tripTable)
                ->leftjoin($masterUserTable, $tripTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
                ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.trtm_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.trtm_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.trtm_state') 
                ->select([
                    $tripTable . '.*',
                    $masterUserTable . '.users_first_name', 
                    $masterUserTable . '.users_last_name',
                    'ta_countries.name as country_name', 
                    'ta_cities.name as city_name', 
                    'ta_states.name as state_name' 
                ]);
        } else {
            // Non-admin query to fetch trips where the user is either the agent or the traveler
            $tripQuery = TripTravelingMember::where('trtm_status', 1)
                ->where('lead_status', 1)  // Condition for lead_status = 1
                ->from($tripTable)
                ->leftjoin($masterUserTable, $tripTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
                ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.trtm_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.trtm_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.trtm_state') 
                ->where(function($query) use ($tripTable, $user, $specificId) {
                    $query->where($tripTable . '.trtm_agent_id', $user->users_id)
                        ->orWhere($tripTable . '.id', $specificId);  // User is the agent or traveler
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

        // Fetch the results
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
            $agency_user = $agency_users->get();
        } else {
            $agency_user = $agency_users->where('users_id', $user->users_id)->get();
        }

        return view('masteradmin.traveler.create', compact('triptype', 'country','agency_user','user'));
    }

    public function editDetails($id)
    {
        // dd($id);
        $trip = TripTravelingMember::where('trtm_id', $id)->firstOrFail();
        $tripmember = TripTravelingMember::where('trtm_id', $id)->get();
        // dd($tripmember);
        $triptype = TripType::all();


        $country = Countries::all();

        $selectedCountryId = $trip->trtm_country;

        $states = States::where('country_id', $selectedCountryId)->get();

        $selectedStateId = $trip->trtm_state;

        $city = Cities::where('state_id', $selectedStateId)->get();


        $user = Auth::guard('masteradmins')->user();
        $agency_users = new MasterUserDetails();
        $agency_users->setTableForUniqueId($user->user_id);
        $tableName = $agency_users->getTable();
        // $agency_users = $agency_users->get();
        if ($user->users_id && $user->role_id == 0) {
            $agency_users = $agency_users->get();
        } else {
            $agency_users = $agency_users->where('users_id', $user->users_id)->get();
        }



        return view('masteradmin.traveler.edit', compact('trip', 'triptype', 'tripmember', 'country', 'states', 'city','agency_users'));
    }

    public function viewDetails($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();
        $trips = new TripTravelingMember();
        $tripTable = $trips->getTable();  

        $trip = TripTravelingMember::where($tripTable.'.trtm_id', $id)
          ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.trtm_country') 
                ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.trtm_city') 
                ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.trtm_state')
                ->select([
                    $tripTable . '.*',  
                    'ta_countries.name as country_name', 
                    'ta_cities.name as city_name', 
                    'ta_states.name as state_name'     
                ])
                ->firstOrFail();
        //  dd($trip);      
        $triptableName = $trip->getTable();

        $tripTraveling = new TripTravelingMember();
        $tableName = $tripTraveling->getTable();

        $uniq_id = $user->user_id; // Ensure this is set based on your authentication logic



        $tripTravelingMembers = DB::table($uniq_id . '_tc_trip_traveling_member')
                ->select('trtm_id', 'trtm_first_name', 'trtm_last_name')
                ->where('lead_id', $id)->where('lead_status','!=',1)
                ->get();
            
             
                
            if($user->users_id && $user->role_id ==0 ){
                $taskCategory = TaskCategory::where(['task_cat_status' => 1])->get();
            }else{
                $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->users_id])->get();
            }

       
            //  $tripTraveling = TripTravelingMember::where(['trtm_status' => 1, 'id' => $user->id, 'tr_id' => $id])->get();
            $documentType = DocumentType::get();

          //last code
          $member = TripTravelingMember::with(['travelingrelationship'])->where('lead_id', $id)->where('lead_status','!=',1)->latest()->get();
            // dd($member);
          $tripData = \DB::table($uniq_id . '_tc_trip_traveling_member')
          ->select( 'trtm_first_name', 'trtm_age','trtm_id')
          ->where('trtm_id', $id)
          ->where('lead_status', 1)//main lead
          ->get();
        //   dd($tripData);
          
          $document = TravelerDocument::where(['trvm_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
          $travelingrelationship = TravelingRelationship::where(['rel_status' => 1])->get();

          $trip_id=$id;

            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id); 
            $masterUserTable = $masterUserDetails->getTable();
            //dd($masterUserTable);
             $trips = new Trip();
             $tripTable = $trips->getTable();  

             $trip_status = TripStatus::get();

             $all_trip = Trip::where('tr_traveler_id', $id)
            ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.tr_country') 
            ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.tr_city') 
            ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.tr_state')
            ->select([
                $tripTable . '.*',  
                'ta_countries.name as country_name', 
                'ta_cities.name as city_name', 
                'ta_states.name as state_name',
                $tripTable . '.tr_traveler_id'  // Add traveler name to the select statement
            ])
            ->get();
            //dD($all_trip);

            $travelerNames = $all_trip->pluck('tr_traveler_id')->unique(); // Extract traveler names


           $trip_history = Trip::where($tripTable . '.tr_status', 1) 
                ->from($tripTable)  
                ->leftJoin($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')  
                ->whereIn($tripTable . '.tr_traveler_id', $travelerNames) 
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
            
        //dd($trip_history);
        return view('masteradmin.traveler.view', compact('trip', 'taskCategory', 'tripTraveling', 'documentType', 'tripTravelingMembers', 'tripData','member','document','trip_id','trip_history','travelingrelationship'));
    }

    public function viewDetails1($id): View
{
    $user = Auth::guard('masteradmins')->user();
    $trips = new TripTravelingMember();
    $tripTable = $trips->getTable();  

    $trip = TripTravelingMember::where('trtm_id', $id)
        ->leftJoin('ta_countries', 'ta_countries.id', '=', $tripTable . '.trtm_country')
        ->leftJoin('ta_cities', 'ta_cities.id', '=', $tripTable . '.trtm_city')
        ->leftJoin('ta_states', 'ta_states.id', '=', $tripTable . '.trtm_state')
        ->select(columns: [
             $tripTable . '.*',  
            'ta_countries.name as country_name',
            'ta_cities.name as city_name',
            'ta_states.name as state_name',
            $tripTable.'.lead_status' // Ensure lead_status is included in the selection
        ])
        ->firstOrFail();

    $taskCategory = TaskCategory::where('task_cat_status', 1)
        ->when($user->role_id != 0, function ($query) use ($user) {
            return $query->where('id', $user->users_id);
        })
        ->get();

    $documentType = DocumentType::all();

    $member = TripTravelingMember::where('trtm_status', 1)

        ->get();

    $document = TravelerDocument::where('tr_id', $id)
        ->with(['traveler', 'documenttype', 'trip'])
        ->latest()
        ->get();

    $travelingRelationship = TravelingRelationship::where('rel_status', 1)->get();
        // dD($travelingRelationship);
    $tripStatus = TripStatus::all();

    // $allTrips = Trip::where('tr_id', $id)
    //     ->leftJoin('ta_countries', 'ta_countries.id', '=', 'trips.tr_country')
    //     ->leftJoin('ta_cities', 'ta_cities.id', '=', 'trips.tr_city')
    //     ->leftJoin('ta_states', 'ta_states.id', '=', 'trips.tr_state')
    //     ->select([
    //         'trips.*',
    //         'ta_countries.name as country_name',
    //         'ta_cities.name as city_name',
    //         'ta_states.name as state_name',
    //         'trips.tr_traveler_id'
    //     ])
    //     ->get();

    // $travelerNames = $allTrips->pluck('tr_traveler_id')->unique();

    // $tripHistory = Trip::where('tr_status', 1)
    //     ->leftJoin('master_user_details', 'master_user_details.users_id', '=', 'trips.tr_agent_id')
    //     ->whereIn('tr_traveler_id', $travelerNames)
    //     ->select([
    //         'trips.*',
    //         'master_user_details.users_first_name',
    //         'master_user_details.users_last_name',
    //         'master_user_details.users_email',
    //         'master_user_details.users_zip',
    //         'master_user_details.user_emergency_phone_number'
    //     ])
    //     ->with(['trip_status'])
    //     ->get();
        $tripHistory = [];
    return view('masteradmin.traveler.view', compact(
        'trip',
        'taskCategory',
        'documentType',
        'member',
        'document',
        'travelingRelationship',
        'tripStatus',
        'tripHistory'
    ));
}


    public function booked_after(Request $request)
    {
        // dd($request->all());
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
            $agency = $masterUserDetails->get(); 
        }else{
            $agency = $masterUserDetails->where('users_id' , $user->users_id)->get(); 
        }

        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();

        $trip_status = TripStatus::get();
        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();

        $trips = new Trip();
        
       
        $specificId = $user->users_id;
        $tripTable = $trips->getTable();
        if($user->users_id && $user->role_id ==0 ){
            $trips_traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
           // dd($trips_traveller);
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $trips_traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->where(function($query) use ($travelerTable, $user, $specificId) {
                $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                    ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
        }

        $trips = new Trip();
        $tripTable = $trips->getTable();



        $currentDate = Carbon::now()->format('m/d/Y');

        // \DB::enableQueryLog();
        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.tr_start_date', '>=', $currentDate)
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);

        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->where($tripTable . '.tr_start_date', '>=', $currentDate)
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }



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
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }

        

        $trip = $tripQuery->orderBy($tripTable . '.tr_start_date', 'ASC')->get();
        // dd($trip);
        if ($request->ajax()) {
            // dd(\DB::getQueryLog()); 
             // dd($allEstimates);
             return view('masteradmin.trip.filtered_results', compact('trip', 'agency', 'trip_status','trips_traveller'))->render();
         }
        // dd($trip);
    //end do not touch

      return view('masteradmin.trip.booked-after',compact('trip', 'agency', 'trip_status','tripQuery','trips_traveller'));
    }

    public function follow_up_details(Request $request)
    {
        $access = view()->shared('access');
    
        $user = Auth::guard('masteradmins')->user();
    
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
    
        if($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->get(); 
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
        }
    
        $trip_status = TripStatus::get();
    
        $trips = new Trip();
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;

        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();
        if($user->users_id && $user->role_id ==0 ){
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
           // dd($trips_traveller);
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->where(function($query) use ($travelerTable, $user, $specificId) {
                $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                    ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
        }

        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.status', 9) // Pending trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name',
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name'  
            ]);
        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.status', 9) // Pending trips
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }
        // Apply filters if available
        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }
    
        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }
    
        // For the initial page load, fetch the trips
        $trip = $tripQuery->get();
        // dd($trip);
        // If the request is AJAX, return the filtered results

      
      
            if($user->users_id && $user->role_id ==0 ){
                $tripQueryCompleted = Trip::where('tr_status', 1)
                    ->from($tripTable)
                    ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
                    ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
                    ->where($tripTable . '.status', 7) // complete trips
                    ->with('trip_status')
                    ->select([
                        $tripTable . '.*', 
                        $masterUserTable . '.users_first_name', 
                        $masterUserTable . '.users_last_name' ,
                        $travelerTable . '.trtm_first_name',  
                        $travelerTable . '.trtm_last_name' 
                    ]);
                }else{
                    $specificId = $user->users_id;
                    $tripQueryCompleted = Trip::where('tr_status', 1)
                    ->from($tripTable)
                    ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
                    ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
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
                        $masterUserTable . '.users_last_name',
                        $travelerTable . '.trtm_first_name',  
                        $travelerTable . '.trtm_last_name'  
                    ]);
        
                }

                $tripCompleted = $tripQueryCompleted->get();
                // dd($tripCompleted);
    
        // Return the main page view
        return view('masteradmin.follow_up.index', compact('trip', 'agency', 'trip_status','traveller','tripCompleted'));
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
            $agency = $masterUserDetails->get(); 
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
        }
    
        $trip_status = TripStatus::get();
    
        $trips = new Trip();
        $tripTable = $trips->getTable();
        $specificId = $user->users_id;

        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();
        if($user->users_id && $user->role_id ==0 ){
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
           // dd($trips_traveller);
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->where(function($query) use ($travelerTable, $user, $specificId) {
                $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                    ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
        }


        // $traveller = Trip::where('id',$user->users_id)->get();
        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.status', 9) // Pending trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.status', 9) // Pending trips
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }
        // dd($tripQuery);
        // Apply filters if available
        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }
    
        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }
    
        $trip = $tripQuery->get();
        // If the request is an AJAX request, return the filtered data
        if ($request->ajax()) {
            return Datatables::of($trip)
                ->addIndexColumn()
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return trim(($document->users_first_name ?? '') . ' ' . ($document->users_last_name ?? ''));
                })
                // ->addColumn('traveler_name', function ($document) {
                //     return optional($document->trtm_first_name ?? '');
                // })
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
                ->addColumn('action', function ($document) use ($access) {

                    $viewUrl = isset($access['details_trip']) && $access['details_trip'] 
                    ? route('trip.view', $document->tr_id) 
                    : null;
                
                $editUrl = isset($access['edit_trip']) && $access['edit_trip'] 
                    ? route('trip.edit', $document->tr_id) 
                    : null;
                
                $deleteModalId = isset($access['delete_trip']) && $access['delete_trip'] 
                    ? "delete-product-modal-{$document->tr_id}" 
                    : null;
                
                $deleteActionUrl = isset($access['delete_trip']) && $access['delete_trip'] 
                    ? route('trip.destroy', $document->tr_id) 
                    : null;
                
                // Return the HTML dynamically
                return '
                    ' . ($viewUrl ? '<a href="' . $viewUrl . '"><i class="fas fa-eye edit_icon_grid"></i></a>' : '') . '
                    ' . ($editUrl ? '<a href="' . $editUrl . '"><i class="fas fa-pen edit_icon_grid"></i></a>' : '') . '
                    ' . ($deleteModalId ? '
                        <a data-toggle="modal" data-target="#' . $deleteModalId . '">
                            <i class="fas fa-trash delete_icon_grid"></i>
                        </a>
                        <div class="modal fade" id="' . $deleteModalId . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form id="delete-plan-form" action="' . $deleteActionUrl . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <div class="modal-body  pad-1 text-center">
                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                            <p class="company_business_name px-10"> <b>Delete Trip</b></p>
                                            <p class="company_details_text">Are you sure you want to delete this trip?</p>
                                            <button type="button" class="add_btn px-15"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit"
                                                class="delete_btn px-15">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>' : '') . '
                ';
                })
                ->rawColumns(['task_status_name','action'])
                ->toJson();
        }
    
        // For the initial page load, fetch the trips
      
        // dd($trip);
        // If the request is AJAX, return the filtered results
      
    
        // Return the main page view
        return view('masteradmin.follow_up.index', compact('trip', 'agency', 'trip_status','traveller'));
    }
    

    public function follow_up_after_complete(Request $request)
    {
        // dd($request->all());
        $access = view()->shared('access');
    
        $user = Auth::guard('masteradmins')->user();
    
        $trip_agent = $request->input('trip_agent');   
        $trip_traveler = $request->input('trip_traveler');   
    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
    
        if($user->users_id && $user->role_id == 0) {
            $agency = $masterUserDetails->get(); 
        } else {
            $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
        }
    
        $trip_status = TripStatus::get();
    
        $trips = new Trip();
        $tripTable = $trips->getTable();

        $specificId = $user->users_id;

        $traveler = new TripTravelingMember();
        $travelerTable = $traveler->getTable();
        if($user->users_id && $user->role_id ==0 ){
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
           // dd($trips_traveller);
        }else{
            // $trips_traveller =Trip::where('id', $user->users_id)->get();  
            
            $traveller = TripTravelingMember::where('trtm_status', 1)
            ->from($travelerTable)
            // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
            ->where('lead_status',1)
            ->where(function($query) use ($travelerTable, $user, $specificId) {
                $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                    ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
            })
            ->select([
                $travelerTable . '.*', 
                $travelerTable . '.trtm_first_name' 
            ])->get();
        }

        // $traveller = Trip::where('id',$user->users_id)->get();
        if($user->users_id && $user->role_id ==0 ){
        $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.id', $user->users_id)
            ->where($tripTable . '.status', 7) // complete trips
            ->with('trip_status')
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name'
            ]);
        }else{
            $specificId = $user->users_id;
            $tripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
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
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name'
            ]);

        }
        // Apply filters if available
        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }
    
        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }

        $trip = $tripQuery->get();
        // dd($trip);
        // If the request is an AJAX request, return the filtered data
        if ($request->ajax()) {
            return Datatables::of($trip)
                ->addIndexColumn()
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return trim(($document->users_first_name ?? '') . ' ' . ($document->users_last_name ?? ''));
                })
                // ->addColumn('traveler_name', function ($document) {
                //     return optional($document->trtm_first_name) ?? '';
                // })
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
                    $endDate = $document->tr_end_date ?? '0';
                
                    // Initialize $endDateParsed with a default value
                    $endDateParsed = null;
                
                    // Check if end date exists
                    if ($endDate && $endDate !== '0') {
                        // Parse the end date and ensure it's in the correct format
                        try {
                            $endDateParsed = Carbon::createFromFormat('m/d/Y', $endDate)->startOfDay();
                        } catch (\Exception $e) {
                            $endDateParsed = null; // Ensure $endDateParsed is always defined
                        }
                    }
                
                    // Check if $endDateParsed is not null before proceeding
                    if ($endDateParsed && $endDateParsed->lt($currentDate)) {
                        $daysSinceCompletion = $endDateParsed->diffInDays($currentDate);
                    } else {
                        $daysSinceCompletion = 0; // Default to 0 days if no valid end date
                    }
                
                    return $daysSinceCompletion . ' days';
                })    
                ->addColumn('action', function ($document)use ($access) {

                    $viewUrl = isset($access['details_trip']) && $access['details_trip'] 
                    ? route('trip.view', $document->tr_id) 
                    : null;
                
                $editUrl = isset($access['edit_trip']) && $access['edit_trip'] 
                    ? route('trip.edit', $document->tr_id) 
                    : null;
                
                $deleteModalId = isset($access['delete_trip']) && $access['delete_trip'] 
                    ? "delete-product-modal-{$document->tr_id}" 
                    : null;
                
                $deleteActionUrl = isset($access['delete_trip']) && $access['delete_trip'] 
                    ? route('trip.destroy', $document->tr_id) 
                    : null;
                
                // Return the HTML dynamically
                return '
                    ' . ($viewUrl ? '<a href="' . $viewUrl . '"><i class="fas fa-eye edit_icon_grid"></i></a>' : '') . '
                    ' . ($editUrl ? '<a href="' . $editUrl . '"><i class="fas fa-pen edit_icon_grid"></i></a>' : '') . '
                    ' . ($deleteModalId ? '
                        <a data-toggle="modal" data-target="#' . $deleteModalId . '">
                            <i class="fas fa-trash delete_icon_grid"></i>
                        </a>
                        <div class="modal fade" id="' . $deleteModalId . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form id="delete-plan-form" action="' . $deleteActionUrl . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <div class="modal-body  pad-1 text-center">
                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                            <p class="company_business_name px-10"> <b>Delete Trip</b></p>
                                            <p class="company_details_text">Are you sure you want to delete this trip?</p>
                                            <button type="button" class="add_btn px-15"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit"
                                                class="delete_btn px-15">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>' : '') . '
                ';
                })          
                ->rawColumns(['task_status_name','action'])
                ->toJson();
        }
    
        // For the initial page load, fetch the trips
     
        // dd($trip);
        // If the request is AJAX, return the filtered results
       
        // Return the main page view
        return view('masteradmin.follow_up.index', compact('trip', 'agency', 'trip_status','traveller'));
    }
    
  
    public function listView(Request $request)
    {
          // dd($request->all());
          $user = Auth::guard('masteradmins')->user();

          $startDate = $request->input('start_date'); 
          $endDate = $request->input('end_date');   
          $trip_agent = $request->input('trip_agent');   
          $trip_traveler = $request->input('trip_traveler');   
          $trip_status1 = $request->input('trip_status');   
  
          $masterUserDetails = new MasterUserDetails();
          $masterUserDetails->setTableForUniqueId($user->user_id); 
          $masterUserTable = $masterUserDetails->getTable();
          $trips = new Trip();
         
  
          $tripTable = $trips->getTable();
          if ($user->users_id && $user->role_id == 0) {
              $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
          
          } else {
              $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
          }
         // $agency = $masterUserTable->get();
          $trip_status = TripStatus::get();
          $specificId = $user->users_id;
  
          $traveler = new TripTravelingMember();
          $travelerTable = $traveler->getTable();
  
          if($user->users_id && $user->role_id ==0 ){
              $trips_traveller = TripTravelingMember::where('trtm_status', 1)
              ->from($travelerTable)
              // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
              ->where('lead_status',1)
              ->select([
                  $travelerTable . '.*', 
                  $travelerTable . '.trtm_first_name' 
              ])->get();
             // dd($trips_traveller);
          }else{
              // $trips_traveller =Trip::where('id', $user->users_id)->get();  
              
              $trips_traveller = TripTravelingMember::where('trtm_status', 1)
              ->from($travelerTable)
              // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
              ->where('lead_status',1)
              ->where(function($query) use ($travelerTable, $user, $specificId) {
                  $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                      ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
              })
              ->select([
                  $travelerTable . '.*', 
                  $travelerTable . '.trtm_first_name' 
              ])->get();
          }
       
         
          if($user->users_id && $user->role_id ==0 ){
              $tripQuery = Trip::where('tr_status', 1)
              ->from($tripTable)
              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
              ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
              ->select([
                  $tripTable . '.*', 
                  $masterUserTable . '.users_first_name', 
                  $masterUserTable . '.users_last_name' ,
                  $travelerTable . '.trtm_first_name',  
                  $travelerTable . '.trtm_last_name' 
              ]);
          }else{
              $tripQuery = Trip::where('tr_status', 1)
              ->from($tripTable)
              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
              ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
              ->where(function($query) use ($tripTable, $user, $specificId) {
                  $query->where($tripTable . '.tr_agent_id', $user->users_id)
                      ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
              })
              ->select([
                  $tripTable . '.*', 
                  $masterUserTable . '.users_first_name', 
                  $masterUserTable . '.users_last_name' ,
                  $travelerTable . '.trtm_first_name',  
                  $travelerTable . '.trtm_last_name' 
              ]);
  
          }
              // Execute the query
              $tripResults = $tripQuery->get();
  
              // dD($tripResults);
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
              $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
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

          $startDate = $request->input('start_date'); 
          $endDate = $request->input('end_date');   
          $trip_agent = $request->input('trip_agent');   
          $trip_traveler = $request->input('trip_traveler');   
          $trip_status1 = $request->input('trip_status');   
  
          $masterUserDetails = new MasterUserDetails();
          $masterUserDetails->setTableForUniqueId($user->user_id); 
          $masterUserTable = $masterUserDetails->getTable();
          $trips = new Trip();
         
  
          $tripTable = $trips->getTable();
          if ($user->users_id && $user->role_id == 0) {
              $agency = $masterUserDetails->where('users_id', '!=', $user->users_id)->get();
          
          } else {
              $agency = $masterUserDetails->where('users_id', $user->users_id)->get();
          }
         // $agency = $masterUserTable->get();
          $trip_status = TripStatus::get();
          $specificId = $user->users_id;
  
          $traveler = new TripTravelingMember();
          $travelerTable = $traveler->getTable();
  
          if($user->users_id && $user->role_id ==0 ){
              $trips_traveller = TripTravelingMember::where('trtm_status', 1)
              ->from($travelerTable)
              // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
              ->where('lead_status',1)
              ->select([
                  $travelerTable . '.*', 
                  $travelerTable . '.trtm_first_name' 
              ])->get();
             // dd($trips_traveller);
          }else{
              // $trips_traveller =Trip::where('id', $user->users_id)->get();  
              
              $trips_traveller = TripTravelingMember::where('trtm_status', 1)
              ->from($travelerTable)
              // ->join($masterUserTable, $travelerTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
              ->where('lead_status',1)
              ->where(function($query) use ($travelerTable, $user, $specificId) {
                  $query->where($travelerTable . '.trtm_agent_id', $user->users_id)
                      ->orWhere($travelerTable . '.id', $specificId);  // Use $specificId here
              })
              ->select([
                  $travelerTable . '.*', 
                  $travelerTable . '.trtm_first_name' 
              ])->get();
          }
       
         
          if($user->users_id && $user->role_id ==0 ){
              $tripQuery = Trip::where('tr_status', 1)
              ->from($tripTable)
              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
              ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
              ->select([
                  $tripTable . '.*', 
                  $masterUserTable . '.users_first_name', 
                  $masterUserTable . '.users_last_name' ,
                  $travelerTable . '.trtm_first_name',  
                  $travelerTable . '.trtm_last_name' 
              ]);
          }else{
              $tripQuery = Trip::where('tr_status', 1)
              ->from($tripTable)
              ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
              ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') // Replace tr_traveling_member_id with the actual foreign key
              ->where(function($query) use ($tripTable, $user, $specificId) {
                  $query->where($tripTable . '.tr_agent_id', $user->users_id)
                      ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
              })
              ->select([
                  $tripTable . '.*', 
                  $masterUserTable . '.users_first_name', 
                  $masterUserTable . '.users_last_name' ,
                  $travelerTable . '.trtm_first_name',  
                  $travelerTable . '.trtm_last_name' 
              ]);
  
          }
            

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
            $tripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }


    // Execute the query
    $tripResults = $tripQuery->get();

    // Count the number of tasks for each priority (low, medium, high) for each trip
    $taskCountByTrip = TripTask::whereIn('tr_id', $tripResults->pluck('tr_id'))
        ->groupBy('tr_id', 'trvt_priority')
        ->selectRaw('tr_id, trvt_priority, count(*) as count')
        ->get();

        $taskCountByTripGrouped = [];

        // Loop through each task count and group by trip ID and valid priority
        foreach ($taskCountByTrip as $taskCount) {
            $validPriorities = ['Low', 'Medium', 'High'];  // Valid priorities
        
            // Ensure that the priority is valid, otherwise skip the task
            if (in_array($taskCount->trvt_priority, $validPriorities)) {
                if (!isset($taskCountByTripGrouped[$taskCount->tr_id])) {
                    $taskCountByTripGrouped[$taskCount->tr_id] = [
                        'Low' => 0,
                        'Medium' => 0,
                        'High' => 0,
                    ];
                }
        
                // Set the count for the valid priority
                $taskCountByTripGrouped[$taskCount->tr_id][$taskCount->trvt_priority] = $taskCount->count;
            }
        }
        
        // Add task counts to each trip in the $tripResults collection
        foreach ($tripResults as $trip) {
            $trip->task_counts = $taskCountByTripGrouped[$trip->tr_id] ?? [
                'Low' => 0,
                'Medium' => 0,
                'High' => 0
            ];
        }

    // dd($tripResults);
    $tripsGrouped = $tripResults->groupBy('status'); 

    $totalsByStatus = $tripResults->groupBy('status')->map(function ($group) {
        return $group->sum('tr_value_trip'); // Sum the 'tr_value_trip' for each group
    });

    if ($request->ajax()) {
        return view('masteradmin.trip.workflow', compact('tripResults', 'trip_status', 'tripsGrouped', 'totalsByStatus', 'taskCountByTripGrouped'))->render();
    }

    return view('masteradmin.trip.workflow', compact('tripResults', 'trip_status', 'tripsGrouped', 'totalsByStatus', 'taskCountByTripGrouped'));
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

public function bookgridView1(Request $request)
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


    $trips = new Trip();
    $tripTable = $trips->getTable();
    $specificId = $user->users_id;

    $currentDate = Carbon::now()->format('m/d/Y');
    if($user->users_id && $user->role_id ==0 ){
        $bookedTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->where($tripTable . '.status', '=', 4)  // Trip Booked
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
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->where($tripTable . '.status', '=', 4)  // Trip Booked
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' 
            ]);
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
        $tripQuery->where($tripTable . '.status', $trip_status1);
    }

   
    $tripResults = $tripQuery->orderBy($tripTable . '.tr_start_date', 'ASC')->get();


    // $sixtyDaysTripResults = $sixtyDaysTripQuery;
    // $thirtyDaysTripResults = $thirtyDaysTripQuery;
    // $twoDaysTripResults = $twoDaysTripQuery;
    $bookedTripResults = $bookedTripQuery;
    // $completedTripResults = $completedTripQuery;

// Combine or process them as needed
$allTripsResults = collect([
    // 'sixtyDays' => $sixtyDaysTripResults,
    // 'thirtyDays' => $thirtyDaysTripResults,
    // 'twoDays' => $twoDaysTripResults,
    'booked' => $bookedTripResults,
    // 'completed' => $completedTripResults,
]);

    $tripsGroupedByStatus = $allTripsResults->groupBy(function ($queryResult) {
        return $queryResult->status;  // or any other status identifier
    });

    $totalsByStatus = $tripsGroupedByStatus->map(function ($group) {
        return $group->sum('tr_value_trip');
    });

    // dd($trip);
    if ($request->ajax()) {
        // dd(\DB::getQueryLog()); 
         // dd($allEstimates);
         return view('masteradmin.trip.booked-workflow', compact('tripResults', 'trip_status', 'tripsGrouped', 'totalsByStatus', 'allowed_status_ids'))->render();
        }
     return view('masteradmin.trip.booked-workflow', compact('tripResults', 'trip_status', 'tripsGrouped', 'totalsByStatus', 'allowed_status_ids'));

}

public function bookgridView(Request $request)
{
    $user = Auth::guard('masteradmins')->user();
    $currentDate = Carbon::now();
    $currentDateFormatted = $currentDate->format('m/d/Y');

    $startDate = $request->input('start_date'); 
    $endDate = $request->input('end_date');   
    $trip_agent = $request->input('trip_agent');   
    $trip_traveler = $request->input('trip_traveler');   
    $trip_status1 = $request->input('trip_status');   

    $masterUserDetails = new MasterUserDetails();
    $masterUserDetails->setTableForUniqueId($user->user_id); 
    $masterUserTable = $masterUserDetails->getTable();

    // Get agencies based on user role
    if($user->users_id && $user->role_id == 0){
        $agency = $masterUserDetails->get(); 
    } else {
        $agency = $masterUserDetails->where('users_id', $user->users_id)->get(); 
    }

    // Get trip data
    $trips = new Trip();
    $tripTable = $trips->getTable();
    $specificId = $user->users_id;

    $traveler = new TripTravelingMember();
    $travelerTable = $traveler->getTable();
    
    if($user->users_id && $user->role_id ==0 ){
    // Base Query for Trip Booked
      $bookedTripQuery = Trip::where('tr_status', 1)
      ->from($tripTable)
      ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
      ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
      ->where($tripTable . '.status', '=', 4)  // Trip Booked
      ->select([
          $tripTable . '.*',
          $masterUserTable . '.users_first_name',
          $masterUserTable . '.users_last_name',
          $travelerTable . '.trtm_first_name',  
          $travelerTable . '.trtm_last_name' 
      ]);

        }else{
            $specificId = $user->users_id;
            $bookedTripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->where($tripTable . '.status', '=', 4)  // Trip Booked
            ->select([
                $tripTable . '.*', 
                $masterUserTable . '.users_first_name', 
                $masterUserTable . '.users_last_name' ,
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);
        }

      

    // Apply filters for Trip Booked query
    if ($startDate && !$endDate) {
        $bookedTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
    } elseif ($startDate && $endDate) {
        $bookedTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
            ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
    }

    if ($trip_agent) {
        $bookedTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
        $bookedTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
    }

    if ($trip_status1) {
        $bookedTripQuery->where($tripTable . '.status', $trip_status1);
    }

    // Execute the query
    $bookedTripQuery = $bookedTripQuery->get();
    
    // 60 Days Trip Review Query
    $sixtyDaysFromNow = $currentDate->copy()->addDays(60)->format('m/d/Y');
    $thirtyDaysFromNow = $currentDate->copy()->addDays(30)->format('m/d/Y');
    $twoDaysFromNow = $currentDate->copy()->addDays(7)->format('m/d/Y');
   // dd($twoDaysFromNow);
    if($user->users_id && $user->role_id ==0 ){

        $sixtyDaysTripQuery = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
            ->where($tripTable . '.tr_start_date', '>', $thirtyDaysFromNow) 
            ->where($tripTable . '.tr_start_date', '<=', $sixtyDaysFromNow) 
            ->select([
                $tripTable . '.*',
                $masterUserTable . '.users_first_name',
                $masterUserTable . '.users_last_name',
                $travelerTable . '.trtm_first_name',  
                $travelerTable . '.trtm_last_name' 
            ]);

    }else{

        $sixtyDaysTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
        ->where(function($query) use ($tripTable, $user, $specificId) {
            $query->where($tripTable . '.tr_agent_id', $user->users_id)
                ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
        })
        ->where($tripTable . '.tr_start_date', '>', $thirtyDaysFromNow) 
        ->where($tripTable . '.tr_start_date', '<=', $sixtyDaysFromNow) 
        ->select([
            $tripTable . '.*',
            $masterUserTable . '.users_first_name',
            $masterUserTable . '.users_last_name',
            $travelerTable . '.trtm_first_name',  
            $travelerTable . '.trtm_last_name' 
        ]);

    }

    // Apply filters for 60 Days Trip Review query
    if ($startDate && !$endDate) {
        $sixtyDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
    } elseif ($startDate && $endDate) {
        $sixtyDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
            ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
    }

    if ($trip_agent) {
        $sixtyDaysTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
        $sixtyDaysTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
    }

    if ($trip_status1) {
        $sixtyDaysTripQuery->where($tripTable . '.status', $trip_status1);
    }

    // Execute the query
    $sixtyDaysTripQuery = $sixtyDaysTripQuery->orderBy($tripTable . '.tr_start_date', 'ASC')->get();


     // 30 Days Trip Review Query

    //  \DB::enableQueryLog();
    if($user->users_id && $user->role_id ==0 ){
     $thirtyDaysTripQuery = Trip::where('tr_status', 1)
         ->from($tripTable)
         ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
         ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
          ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') > STR_TO_DATE(?, '%m/%d/%Y')", [$twoDaysFromNow]) 
          ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$thirtyDaysFromNow]) 
         ->select([
             $tripTable . '.*',
             $masterUserTable . '.users_first_name',
             $masterUserTable . '.users_last_name',
             $travelerTable . '.trtm_first_name',  
             $travelerTable . '.trtm_last_name' 
         ]);
        //  dd(\DB::getQueryLog()); 
        }else{
            $thirtyDaysTripQuery = Trip::where('tr_status', 1)
         ->from($tripTable)
         ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
         ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
         ->where(function($query) use ($tripTable, $user, $specificId) {
            $query->where($tripTable . '.tr_agent_id', $user->users_id)
                ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
          ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') > STR_TO_DATE(?, '%m/%d/%Y')", [$twoDaysFromNow]) 
          ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$thirtyDaysFromNow]) 
         ->select([
             $tripTable . '.*',
             $masterUserTable . '.users_first_name',
             $masterUserTable . '.users_last_name',
             $travelerTable . '.trtm_first_name',  
             $travelerTable . '.trtm_last_name' 
         ]);
        }
     // Apply filters for 30 Days Trip Review query
     if ($startDate && !$endDate) {
         $thirtyDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
     } elseif ($startDate && $endDate) {
         $thirtyDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
             ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
     }
 
     if ($trip_agent) {
         $thirtyDaysTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
     }
 
     if ($trip_traveler) {
         $thirtyDaysTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
     }
 
     if ($trip_status1) {
         $thirtyDaysTripQuery->where($tripTable . '.status', $trip_status1);
     }
 
     // Execute the query
     $thirtyDaysTripQuery = $thirtyDaysTripQuery->orderBy($tripTable . '.tr_start_date', 'ASC')->get();
 

    // 2 Days Trip Bon Voyage Query
  
    if($user->users_id && $user->role_id ==0 ){
        
        // \DB::enableQueryLog();

    $twoDaysTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
        ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') > STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
        ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$twoDaysFromNow]);

        // ->where($tripTable . '.tr_start_date', '>', $currentDate->format('m/d/Y'))
        // ->where($tripTable . '.tr_start_date', '<=', $twoDaysFromNow);
    }else{
        $twoDaysTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
        ->where(function($query) use ($tripTable, $user, $specificId) {
            $query->where($tripTable . '.tr_agent_id', $user->users_id)
                ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') > STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
            ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$twoDaysFromNow]);
    
    }
    // dd(\DB::getQueryLog()); 

    // Apply filters for 2 Days Trip Bon Voyage Query
    if ($startDate && !$endDate) {
        $twoDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
    } elseif ($startDate && $endDate) {
        $twoDaysTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
            ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
    }

    if ($trip_agent) {
        $twoDaysTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
        $twoDaysTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
    }

    if ($trip_status1) {
        $twoDaysTripQuery->where($tripTable . '.status', $trip_status1);
    }

    $twoDaysTripQuery = $twoDaysTripQuery->select([
            $tripTable . '.*',
            $masterUserTable . '.users_first_name',
            $masterUserTable . '.users_last_name',
            $travelerTable . '.trtm_first_name',  
            $travelerTable . '.trtm_last_name' 
        ])
        ->orderBy($tripTable . '.tr_start_date', 'ASC')
        ->get();

      
    if($user->users_id && $user->role_id == 0 ){
        // Completed Trip Query
        // \DB::enableQueryLog();

        $completedTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
        // ->where($tripTable . '.tr_end_date', '>', $currentDate->format('m/d/Y'))
        ->whereRaw("STR_TO_DATE({$tripTable}.tr_end_date, '%m/%d/%Y') < STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')]);

        // ->where($tripTable . '.status', '=', 7); // Trip Completed
     

    }else{
        $completedTripQuery = Trip::where('tr_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
        ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
        ->where(function($query) use ($tripTable, $user, $specificId) {
            $query->where($tripTable . '.tr_agent_id', $user->users_id)
                ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->whereRaw("STR_TO_DATE({$tripTable}.tr_end_date, '%m/%d/%Y') < STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')]);

        // ->where($tripTable . '.status', '=', 7); // Trip Completed
    }

      Trip::where('tr_status', 1)
        ->where('status', '=', 7) // Avoid updating trips already marked as completed
        ->whereRaw("STR_TO_DATE({$tripTable}.tr_end_date, '%m/%d/%Y') < STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
        ->update(['status' => 7]);

    // Apply filters for Completed Trip Query
    if ($startDate && !$endDate) {
    $completedTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
    } elseif ($startDate && $endDate) {
    $completedTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
        ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
    }

    if ($trip_agent) {
    $completedTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
    $completedTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
    }

    if ($trip_status1) {
    $completedTripQuery->where($tripTable . '.status', $trip_status1);
    }

    $completedTripQuery = $completedTripQuery->select([
        $tripTable . '.*',
        $masterUserTable . '.users_first_name',
        $masterUserTable . '.users_last_name',
        $travelerTable . '.trtm_first_name',  
        $travelerTable . '.trtm_last_name' 
    ])
    ->orderBy($tripTable . '.tr_start_date', 'ASC')
    ->get();
    // dd(\DB::getQueryLog()); 
    // Travelling Trip Query

    $travellingTripQuery = Trip::where('tr_status', 1)
    ->from($tripTable)
    ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
    ->leftJoin($travelerTable, $travelerTable . '.trtm_id', '=', $tripTable . '.tr_traveler_id') 
    ->whereRaw("STR_TO_DATE($tripTable.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDateFormatted])
    ->whereRaw("STR_TO_DATE($tripTable.tr_end_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDateFormatted]);

    // Apply filters for Travelling Trip Query
    if ($startDate && !$endDate) {
    $travellingTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", [$startDate]);
    } elseif ($startDate && $endDate) {
    $travellingTripQuery->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$startDate])
        ->whereRaw("STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$endDate]);
    }

    if ($trip_agent) {
    $travellingTripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
    }

    if ($trip_traveler) {
    $travellingTripQuery->where($tripTable . '.tr_traveler_id', $trip_traveler);
    }

    if ($trip_status1) {
    $travellingTripQuery->where($tripTable . '.status', $trip_status1);
    }

    $travellingTripQuery = $travellingTripQuery->select([
        $tripTable . '.*',
        $masterUserTable . '.users_first_name',
        $masterUserTable . '.users_last_name',
        $travelerTable . '.trtm_first_name',  
        $travelerTable . '.trtm_last_name' 
    ])
    ->orderBy($tripTable . '.tr_start_date', 'ASC')
    ->get();

    // Combine all trip queries into one collection
    $allTripsResults = collect([
        'Trip Booked' => $bookedTripQuery,
        '60 Trip Review' => $sixtyDaysTripQuery,
        '30 Trip Review' => $thirtyDaysTripQuery,
        'Trip Bon Voyage' => $twoDaysTripQuery,
        'Trip Traveling' => $travellingTripQuery,
        'Trip Completed' => $completedTripQuery,
    ]);

    // Flatten all trips to get their IDs for the task count query
    $allTripIds = $allTripsResults->flatten()->pluck('tr_id')->unique();

    // Count the number of tasks for each priority (low, medium, high) for each trip
    $taskCountByTrip = TripTask::whereIn('tr_id', $allTripIds)
        ->groupBy('tr_id', 'trvt_priority')
        ->selectRaw('tr_id, trvt_priority, count(*) as count')
        ->get();

    $taskCountByTripGrouped = [];

    // Loop through each task count and group by trip ID and valid priority
    foreach ($taskCountByTrip as $taskCount) {
        $validPriorities = ['Low', 'Medium', 'High'];  // Valid priorities

        // Ensure that the priority is valid, otherwise skip the task
        if (in_array($taskCount->trvt_priority, $validPriorities)) {
            if (!isset($taskCountByTripGrouped[$taskCount->tr_id])) {
                $taskCountByTripGrouped[$taskCount->tr_id] = [
                    'Low' => 0,
                    'Medium' => 0,
                    'High' => 0,
                ];
            }

            // Set the count for the valid priority
            $taskCountByTripGrouped[$taskCount->tr_id][$taskCount->trvt_priority] = $taskCount->count;
        }
    }

    // Add task counts to each trip in the allTripsResults collection
    $allTripsResults = $allTripsResults->map(function ($group) use ($taskCountByTripGrouped) {
        return $group->map(function ($trip) use ($taskCountByTripGrouped) {
            $trip->task_counts = $taskCountByTripGrouped[$trip->tr_id] ?? [
                'Low' => 0,
                'Medium' => 0,
                'High' => 0
            ];
            return $trip;
        });
    });

    // Sum the total trip values for each group
    $totalsByStatus = $allTripsResults->map(function ($group) {
        return $group->sum('tr_value_trip');
    });

    // Return view
    if ($request->ajax()) {
        return view('masteradmin.trip.booked-workflow', compact('allTripsResults', 'totalsByStatus'))->render();
    }

    return view('masteradmin.trip.booked-workflow', compact('allTripsResults', 'totalsByStatus'));
}


public function getTravelerNames(Request $request)
{
     try {
    $user = Auth::guard('masteradmins')->user(); // Authenticated user
    $specificId = $user->users_id;

    $agency_users = new MasterUserDetails();
    $agency_users->setTableForUniqueId($user->user_id);
    $masterUserTable = $agency_users->getTable();

    $trips = new TripTravelingMember();
    $tripTable = $trips->getTable();

    $search = $request->get('query', ''); // Get the input query

    $query = TripTravelingMember::where('trtm_status', 1)
        ->where('lead_status', 1)
        ->from($tripTable)
        ->join($masterUserTable, $tripTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id')
        ->select($tripTable . '.trtm_first_name', $tripTable . '.trtm_email', $tripTable . '.trtm_number', $tripTable . '.trtm_id');

    
    if ($user->role_id != 0) {
        $query->where(function ($q) use ($tripTable, $user, $specificId) {
            $q->where($tripTable . '.trtm_agent_id', $user->users_id)
              ->orWhere($tripTable . '.id', $specificId);
        });
    }

    // Apply search filter if query is not empty
    if (!empty($search)) {
        $query->where($tripTable . '.trtm_first_name', 'like', '%' . $search . '%');
    }

    // dd($query);

    $travelerNames = $query->get();
    // dd($travelerNames);
    return response()->json($travelerNames); // Return as JSON
    
     } catch (ValidationException $e) {
    return response()->json(['errors' => $e->errors()], 422); 
}
}

public function travelerStore(Request $request)
{
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id;
        
            $validatedData = $request->validate([
                'trtm_agent_id' => 'required|string',
                'trtm_first_name' => 'required|string',
                'trtm_dob' => 'nullable|string',
                'trtm_age' => 'nullable|string',
                'trtm_email' => 'required|email',
                'trtm_number' => 'nullable|string',
                'trtm_notes' => 'nullable|string',
                'trtm_country' => 'nullable|string',
                'trtm_state' => 'nullable|string',
                'trtm_city' => 'nullable|string',
                'trtm_address' => 'nullable|string',
                'trtm_zip' => 'nullable|numeric|digits_between:1,6',
                    
            ], [

                'trtm_first_name.required' => 'Traveler name is required',
                'trtm_agent_id.required' => 'Agent ID is required',
                'trtm_email.required' => 'Email is required',
                'trtm_email.regex' => 'Please enter a valid email address.',
            ]);
        
        // dd('data');


        // Check for duplicate entries in TripTravelingMember
        $duplicateTraveler = TripTravelingMember::where('trtm_first_name', $validatedData['trtm_first_name'])
        ->where('trtm_number', $validatedData['trtm_number'])
        ->where('trtm_email', $validatedData['trtm_email'])
        ->first();

    if ($duplicateTraveler) {
        // Handle the duplicate scenario
        return back()->withErrors([
            'trtm_first_name' => 'Traveler with the same name, email, and phone number already exists.',
        ])->withInput();
    }
        
      // Get the logged-in user
        $user = Auth::guard('masteradmins')->user();

        // Create a new TripTravelingMember record
        $travelerMember = new TripTravelingMember();

        // Generate a unique ID for the traveler
        $tableName = $travelerMember->getTable();
        $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);
        $travelerMember->trtm_id = $uniqueId;

        // Assign validated data to the TripTravelingMember model
        $travelerMember->id = $user->users_id;
        $travelerMember->tr_id = 0;
        $travelerMember->trtm_agent_id = $validatedData['trtm_agent_id'];
        $travelerMember->trtm_first_name = $validatedData['trtm_first_name'];
        $travelerMember->trtm_dob = $validatedData['trtm_dob'] ?? null;
        $travelerMember->trtm_age = $validatedData['trtm_age'] ?? null;
        $travelerMember->trtm_email = $validatedData['trtm_email'];
        $travelerMember->trtm_number = $validatedData['trtm_number'] ?? null;
        $travelerMember->trtm_notes = $validatedData['trtm_notes'] ?? null;
        $travelerMember->trtm_country = $validatedData['trtm_country'] ?? null;
        $travelerMember->trtm_state = $validatedData['trtm_state'] ?? null;
        $travelerMember->trtm_city = $validatedData['trtm_city'] ?? null;
        $travelerMember->trtm_address = $validatedData['trtm_address'] ?? null;
        $travelerMember->trtm_zip = $validatedData['trtm_zip'] ?? null;
        $travelerMember->lead_status = 1;
        $travelerMember->trtm_status = 1;

        // Save the new record
        $travelerMember->save();
        
        \MasterLogActivity::addToLog('Master Admin Travelers Created from Trip.');

        return response()->json([
            'success' => true,
            'traveler_name' => $travelerMember->trtm_first_name, 
            'traveler_email' => $travelerMember->trtm_email, 
            'traveler_phone' => $travelerMember->trtm_number,
            'traveler_id' => $travelerMember->trtm_id,
        ]);
        
}


public function travelerstoreData(Request $request): RedirectResponse
{
    // dd($request->all());
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->users_id;

        $validatedData = $request->validate([
            'trtm_agent_id' => 'required|string',
            'trtm_first_name' => 'required|string',
            'trtm_dob' => 'nullable|string',
            'trtm_age' => 'nullable|string',
            'trtm_email' => 'required|email',
            'trtm_number' => 'nullable|string',
            'trtm_notes' => 'nullable|string',
            'trtm_country' => 'nullable|string',
            'trtm_state' => 'nullable|string',
            'trtm_city' => 'nullable|string',
            'trtm_address' => 'nullable|string',
            'trtm_zip' => 'nullable|numeric|digits_between:1,6',
                
        ], [

            'trtm_first_name.required' => 'Traveler name is required',
            'trtm_agent_id.required' => 'Agent ID is required',
            'trtm_email.required' => 'Email is required',
            'trtm_email.regex' => 'Please enter a valid email address.',
        ]);
    
    // dd('data');


    // Check for duplicate entries in TripTravelingMember
            $duplicateTraveler = TripTravelingMember::where('trtm_first_name', $validatedData['trtm_first_name'])
            ->where('trtm_number', $validatedData['trtm_number'])
            ->where('trtm_email', $validatedData['trtm_email'])
            ->first();

        if ($duplicateTraveler) {
            // Handle the duplicate scenario
            return back()->withErrors([
                'trtm_first_name' => 'Traveler with the same name, email, and phone number already exists.',
            ])->withInput();
        }

        // Get the logged-in user
        $user = Auth::guard('masteradmins')->user();

        // Create a new TripTravelingMember record
        $travelerMember = new TripTravelingMember();

        // Generate a unique ID for the traveler
        $tableName = $travelerMember->getTable();
        $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);
        $travelerMember->trtm_id = $uniqueId;

        // Assign validated data to the TripTravelingMember model
        $travelerMember->id = $user->users_id;
        $travelerMember->tr_id = 0;
        $travelerMember->trtm_agent_id = $validatedData['trtm_agent_id'];
        $travelerMember->trtm_first_name = $validatedData['trtm_first_name'];
        $travelerMember->trtm_dob = $validatedData['trtm_dob'] ?? null;
        $travelerMember->trtm_age = $validatedData['trtm_age'] ?? null;
        $travelerMember->trtm_email = $validatedData['trtm_email'];
        $travelerMember->trtm_number = $validatedData['trtm_number'] ?? null;
        $travelerMember->trtm_notes = $validatedData['trtm_notes'] ?? null;
        $travelerMember->trtm_country = $validatedData['trtm_country'] ?? null;
        $travelerMember->trtm_state = $validatedData['trtm_state'] ?? null;
        $travelerMember->trtm_city = $validatedData['trtm_city'] ?? null;
        $travelerMember->trtm_address = $validatedData['trtm_address'] ?? null;
        $travelerMember->trtm_zip = $validatedData['trtm_zip'] ?? null;
        $travelerMember->lead_status = 1;
        $travelerMember->trtm_status = 1;

        // Save the new record
        $travelerMember->save();

        // Log activity
        \MasterLogActivity::addToLog('Master Admin Traveler created.');

        // Redirect with success message
        return redirect()->route('masteradmin.travelers.travelersDetails')
            ->with('success', 'Traveler created successfully.');
        
    }

public function travelerUpdate(Request $request, $id): RedirectResponse
{
    // dD($id);
    // Get the logged-in user
    $user = Auth::guard('masteradmins')->user();
    
    // Validate the incoming request data
    $validatedData = $request->validate([
        'trtm_agent_id' => 'required|string',
        'trtm_first_name' => 'required|string',
        'trtm_dob' => 'nullable|string',
        'trtm_age' => 'nullable|string',
        'trtm_email' => 'required|email',
        'trtm_number' => 'nullable|string',
        'trtm_notes' => 'nullable|string',
        'trtm_country' => 'nullable|string',
        'trtm_state' => 'nullable|string',
        'trtm_city' => 'nullable|string',
        'trtm_address' => 'nullable|string',
        'trtm_zip' => 'nullable|numeric|digits_between:1,6',
    ], [
        'trtm_first_name.required' => 'Traveler name is required',
        'trtm_agent_id.required' => 'Agent ID is required',
        'trtm_email.required' => 'Email is required',
        'trtm_email.regex' => 'Please enter a valid email address.',
    ]);

    // Check for duplicate entries in TripTravelingMember, excluding the current record
    $duplicateTraveler = TripTravelingMember::where('trtm_first_name', $validatedData['trtm_first_name'])
        ->where('trtm_number', $validatedData['trtm_number'])
        ->where('trtm_email', $validatedData['trtm_email'])
        ->where('trtm_id', '!=', $id) // Ignore the current record
        ->first();

    if ($duplicateTraveler) {
        // Handle the duplicate scenario
        return back()->withErrors([
            'trtm_first_name' => 'Traveler with the same name, email, and phone number already exists.',
        ])->withInput();
    }

    // Find the existing traveler record using the provided traveler ID
    $travelerMember = TripTravelingMember::where('trtm_id',$id); // Assuming 'trtm_id' is passed in the request

    // Check if traveler exists
    if (!$travelerMember) {
        return back()->withErrors([
            'error' => 'Traveler not found.'
        ]);
    }

    // Update the traveler record with the validated data
    $travelerMember->trtm_agent_id = $validatedData['trtm_agent_id'];
    $travelerMember->trtm_first_name = $validatedData['trtm_first_name'];
    $travelerMember->trtm_dob = $validatedData['trtm_dob'] ?? null;
    $travelerMember->trtm_age = $validatedData['trtm_age'] ?? null;
    $travelerMember->trtm_email = $validatedData['trtm_email'];
    $travelerMember->trtm_number = $validatedData['trtm_number'] ?? null;
    $travelerMember->trtm_notes = $validatedData['trtm_notes'] ?? null;
    $travelerMember->trtm_country = $validatedData['trtm_country'] ?? null;
    $travelerMember->trtm_state = $validatedData['trtm_state'] ?? null;
    $travelerMember->trtm_city = $validatedData['trtm_city'] ?? null;
    $travelerMember->trtm_address = $validatedData['trtm_address'] ?? null;
    $travelerMember->trtm_zip = $validatedData['trtm_zip'] ?? null;
    $travelerMember->lead_status = 1;
    $travelerMember->trtm_status = 1;

    // Save the updated record
    $travelerMember->where(['trtm_id' => $id])->update($validatedData);
    

    // Log activity
    \MasterLogActivity::addToLog('Master Admin Traveler updated.');

    // Redirect with success message
    return redirect()->route('masteradmin.travelers.travelersDetails')
        ->with('success', 'Traveler updated successfully.');
}

public function destroyTraveler($id): RedirectResponse
{
    // Find the traveler by ID
    $traveler = TripTravelingMember::where('trtm_id',$id);

    // Check if the traveler exists
    if (!$traveler) {
        return back()->withErrors([
            'error' => 'Traveler not found.'
        ]);
    }

    // Delete the traveler
    $traveler->where('trtm_id',$id)->delete();

    // Log the deletion activity
    \MasterLogActivity::addToLog('Master Admin Traveler deleted.');

    // Redirect with success message
    return redirect()->route('masteradmin.travelers.travelersDetails')
        ->with('success', 'Traveler deleted successfully.');
}

public function preferencesStore(Request $request,$id)
{
    // dd($id);
    // dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $validator = Validator::make($request->all(), [
            'traveler_id' => 'required|string|max:255',
            'perferred_airport' => 'nullable|string|max:255',
            'secondary_airport' => 'nullable|string|max:255',
            'perferred_airline' => 'nullable|string|max:255',
            'secondary_airline' => 'nullable|string|max:255',
            'perferred_class' => 'nullable|string|max:255',
            'perferred_seat' => 'nullable|string|max:255',
            'air_notes' => 'nullable|string',
            'preferred_embarkation_port' => 'nullable|string|max:255',
            'secondary_embarkation_port' => 'nullable|string|max:255',
            'favoriate_curuise_line' => 'nullable|string|max:255',
            'twond_favoriate_curuise_line' => 'nullable|string|max:255',
            'cabine_preference' => 'nullable|string|max:255',
            'preferred_deck_location' => 'nullable|string|max:255',
            'curuise_note' => 'nullable|string',
            'favorite_hotel_brand' => 'nullable|string|max:255',
            'preferred_hotel_type' => 'nullable|string|max:255',
            'bed_preference' => 'nullable|string|max:255',
            'hotel_notes' => 'nullable|string',
            'favorite_resort' => 'nullable|string|max:255',
            'secoundary_resort' => 'nullable|string|max:255',
            'preferred_room_type' => 'nullable|string|max:255',
            'secoundary_room_type' => 'nullable|string|max:255',
            'preferred_meal_plan' => 'nullable|string|max:255',
            'preferred_atmosphere' => 'nullable|string|max:255',
            'preferred_resort_type' => 'nullable|string|max:255',
            'resort_notes' => 'nullable|string',
            'favorite_toure_company' => 'nullable|string|max:255',
            'secoundary_favorite_toure_company' => 'nullable|string|max:255',
            'guided_tours_preferred_room_type' => 'nullable|string|max:255',
            'guided_tours_notes' => 'nullable|string',
            'favorite_car_rental_company' => 'nullable|string|max:255',
            'secoundary_favrioute_car_reantal_company' => 'nullable|string|max:255',
            'preferred_car_type' => 'nullable|string|max:255',
            'car_rental_notes' => 'nullable|string',
            'favorite_theme_park' => 'nullable|string|max:255',
            'secoundary_favorite_theme_park' => 'nullable|string|max:255',
            'oneside_offside_hotel' => 'nullable|string|max:255',
            'theme_park_notes' => 'nullable|string',
            'preference_status' => 'nullable|string|max:255',
        ], [
            'traveler_id.required' => 'Please select a traveler',
        ]);

        if ($validator->fails()) {
            session()->flash('activeTab', 'preferenceinfo');
            return redirect()->back()->withErrors($validator);
        }

        $preference = TripPreference::where('id', $user->users_id)->where('tr_id', $id)->where('traveler_id', $request->traveler_id)->first();
        $data = $request->only([
            'traveler_id',
            'perferred_airport',
            'secondary_airport',
            'perferred_airline',
            'secondary_airline',
            'perferred_class',
            'perferred_seat',
            'air_notes',
            'preferred_embarkation_port',
            'secondary_embarkation_port',
            'favoriate_curuise_line',
            'twond_favoriate_curuise_line',
            'cabine_preference',
            'preferred_deck_location',
            'curuise_note',
            'favorite_hotel_brand',
            'preferred_hotel_type',
            'bed_preference',
            'hotel_notes',
            'favorite_resort',
            'secoundary_resort',
            'preferred_room_type',
            'secoundary_room_type',
            'preferred_meal_plan',
            'preferred_atmosphere',
            'preferred_resort_type',
            'resort_notes',
            'favorite_toure_company',
            'secoundary_favorite_toure_company',
            'guided_tours_preferred_room_type',
            'guided_tours_notes',
            'favorite_car_rental_company',
            'secoundary_favrioute_car_reantal_company',
            'preferred_car_type',
            'car_rental_notes',
            'favorite_theme_park',
            'secoundary_favorite_theme_park',
            'oneside_offside_hotel',
            'theme_park_notes',
            'preference_status',
            
        ]);
        if ($preference) {
            $data['updated_at'] = now();
            $preference->where('id', $user->users_id)->where('tr_id', $id)->where('traveler_id', $request->traveler_id)->update($data);
        } else {
            // 'preference_id',
            // 'id',
            $data['id'] = $user->users_id;
            $data['tr_id'] = $id;
            $data['created_at'] = now();
            $data['preference_status'] = 1;
            // Create a new record if none exists
            TripPreference::create($data);
        }
        session()->flash('activeTab',  'preferenceinfo');

        // return redirect()->route('trip.view',$id)->with('success', 'Trip preferences saved successfully!');
        return redirect()->back()->with('success', 'Trip preferences saved successfully!');
        
    }
  
public function getFamilyMembers($id)
{
    $familyMembers = TripTravelingMember::where('lead_id', $id)
    // ->orWhere('trtm_id', $trip->tr_traveler_id)
    ->get();
    $count = $familyMembers->count();
    // dd($count);
    return response()->json([
        'success' => true,
        'family_members' => $familyMembers,
        'count' => $count
    ]);
}

public function getTripPreferences($trvm_id)
{
    //dd($trvm_id);
    // Fetch the traveler's preferences from the TripPreference model
    $preference = TripPreference::where('traveler_id', $trvm_id)->first();
    //dD($preference);
    // Return the data as JSON
    return response()->json([
        'success' => true,
        'preference' => $preference,
    ]);
}

public function deleteImage(Request $request, $tripid, $documentid, $image)
{
    // dd($tripid);
    $library = TripDocument::where('trp_id', $documentid)->firstOrFail();
    // dd($library);
    $images = json_decode($library->trp_document, true);

    if (($key = array_search($image, $images)) !== false) {
        $userFolder = session('userFolder');

        unset($images[$key]);

        $data_image = json_encode(array_values($images)); // Re-index the array and encode it back to JSON

        $library->where('trp_id', $documentid)->update(['trp_document' => $data_image]); 

        $userFolder = storage_path('app/' . session('userFolder'). '/trip_document/');
        $filePath = $userFolder . $image; 

        // Log::info('Attempting to delete file at path: ' . $filePath);

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            // Log::info('File deleted successfully: ' . $filePath);
        } else {
            // Log::warning('File does not exist: ' . $filePath);
        }

        if (empty($library->trp_document) || !$images) {
            // If trp_document is empty or null, delete the record
            $library->where('trp_id', $documentid)->delete();
            // return; // Exit the function to avoid further processing
        }

        // return redirect()->route('trip.edit', $id)->with('success', 'Image deleted successfully.');
        session()->flash('success', 'Image deleted successfully.');

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'redirect_url' => route('trip.edit', $tripid) // URL to redirect after successful delete
        ]);
    }
    \MasterLogActivity::addToLog('Master Admin Library is Deleted.');

    // return redirect()->route('trip.edit', $id)->with('error', 'Image not found.');

    session()->flash('error', 'Image not found.');

    return response()->json([
        'error' => false,
        'redirect_url' => route('trip.edit', $tripid) // URL to redirect if image is not found
    ]);
    // return redirect()->back()->with('error', 'Image not found.');
}

public function updateDocument(Request $request, $id): RedirectResponse
{
    // $rawItems = $request->input('items');
    // dd($rawItems);

 //dd($request->all());
    $user = Auth::guard('masteradmins')->user();

    // $trip = TripDocument::where(['trp_id' => $id])->firstOrFail();
  
        $validatedData = $request->validate([
           
            'trp_name' => 'nullable|string',
           
        ],);
 
    $trp_name = $request->input('trp_name') ?? '';
    if ($request->hasFile('trp_document')) {

        if (is_array($request->file('trp_document'))) {
            
            $userFolder = session('userFolder');
            $documents_images =  $this->handleImageUpload($request, 'trp_document', null, 'trip_document', $userFolder);
            //dd($documents_images);
            $documentimg = json_encode($documents_images);

        }
    } 


      TripDocument::create([
        'id' => $user->users_id,
        'tr_id' =>  $id,
        'trp_name' =>  $trp_name ?? '',
        'trp_document' =>  $documentimg,
        'trp_status' =>  1,
      ]);
 
    

        session()->flash('activeTab',  'TripDocumentinfo');

        \MasterLogActivity::addToLog('Master Admin Trip Updated.');
        return redirect()->back()->with('document-success', 'Document Updated successfully!');

    
}

public function editPreferences($trvm_id,$lead_id)
{
    //
    $id = $trvm_id;
    $main_lead = $lead_id;
    $preference = TripPreference::where('traveler_id', $trvm_id)->first();
    
    $user = Auth::guard('masteradmins')->user();
    $uniq_id = $user->user_id;
    $tripTravelingMembers = DB::table($uniq_id . '_tc_trip_traveling_member')
    ->select('trtm_id', 'trtm_first_name', 'trtm_last_name')
    ->where('lead_id', $lead_id)->where('lead_status','!=',1)
    ->get();
// dd($tripTravelingMembers);
        $tripData = \DB::table($uniq_id . '_tc_trip_traveling_member')
    ->select( 'trtm_first_name', 'trtm_age','trtm_id')
    ->where('trtm_id', $lead_id)
    ->where('lead_status', 1)//main lead
    ->get();
    // dd($tripData);

    $tripTypes = TripType::whereIn('ty_name', ['Air', 'Cruise', 'Hotel', 'Resort', 'Guided Tours', 'Car Rental', 'Theme Park'])
    ->where('ty_status', '1')
    ->orderByRaw("FIELD(ty_name, 'Air', 'Cruise', 'Hotel', 'Resort', 'Guided Tours', 'Car Rental', 'Theme Park')")
    ->get(['ty_id', 'ty_name']);

    // dd($preference);
    return view('masteradmin.traveler.member-preferences',compact('preference','id','tripTravelingMembers','tripData','tripTypes','main_lead'));

}

public function getTripPreferencesShow($trvm_id)
{
    //dd($trvm_id);
    // Fetch the traveler's preferences from the TripPreference model
    $preference = TripPreference::where('traveler_id', $trvm_id)->first();
    //dD($preference);
    // Return the data as JSON
    return response()->json([
        'success' => true,
        'preference' => $preference,
    ]);
}

public function memberPreferencesStore(Request $request,$id)
{
    // dd($id);
    // dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $validator = Validator::make($request->all(), [
            'traveler_id' => 'required|string|max:255',
            'perferred_airport' => 'nullable|string|max:255',
            'secondary_airport' => 'nullable|string|max:255',
            'perferred_airline' => 'nullable|string|max:255',
            'secondary_airline' => 'nullable|string|max:255',
            'perferred_class' => 'nullable|string|max:255',
            'perferred_seat' => 'nullable|string|max:255',
            'air_notes' => 'nullable|string',
            'preferred_embarkation_port' => 'nullable|string|max:255',
            'secondary_embarkation_port' => 'nullable|string|max:255',
            'favoriate_curuise_line' => 'nullable|string|max:255',
            'twond_favoriate_curuise_line' => 'nullable|string|max:255',
            'cabine_preference' => 'nullable|string|max:255',
            'preferred_deck_location' => 'nullable|string|max:255',
            'curuise_note' => 'nullable|string',
            'favorite_hotel_brand' => 'nullable|string|max:255',
            'preferred_hotel_type' => 'nullable|string|max:255',
            'bed_preference' => 'nullable|string|max:255',
            'hotel_notes' => 'nullable|string',
            'favorite_resort' => 'nullable|string|max:255',
            'secoundary_resort' => 'nullable|string|max:255',
            'preferred_room_type' => 'nullable|string|max:255',
            'secoundary_room_type' => 'nullable|string|max:255',
            'preferred_meal_plan' => 'nullable|string|max:255',
            'preferred_atmosphere' => 'nullable|string|max:255',
            'preferred_resort_type' => 'nullable|string|max:255',
            'resort_notes' => 'nullable|string',
            'favorite_toure_company' => 'nullable|string|max:255',
            'secoundary_favorite_toure_company' => 'nullable|string|max:255',
            'guided_tours_preferred_room_type' => 'nullable|string|max:255',
            'guided_tours_notes' => 'nullable|string',
            'favorite_car_rental_company' => 'nullable|string|max:255',
            'secoundary_favrioute_car_reantal_company' => 'nullable|string|max:255',
            'preferred_car_type' => 'nullable|string|max:255',
            'car_rental_notes' => 'nullable|string',
            'favorite_theme_park' => 'nullable|string|max:255',
            'secoundary_favorite_theme_park' => 'nullable|string|max:255',
            'oneside_offside_hotel' => 'nullable|string|max:255',
            'theme_park_notes' => 'nullable|string',
            'preference_status' => 'nullable|string|max:255',
        ], [
            'traveler_id.required' => 'Please select a traveler',
        ]);

        if ($validator->fails()) {
            session()->flash('activeTab', 'preferenceinfo');
            return redirect()->back()->withErrors($validator);
        }

        $preference = TripPreference::where('id', $user->users_id)->where('traveler_id', $request->traveler_id)->first();
        // dd($preference);
        $data = $request->only([
            'traveler_id',
            'perferred_airport',
            'secondary_airport',
            'perferred_airline',
            'secondary_airline',
            'perferred_class',
            'perferred_seat',
            'air_notes',
            'preferred_embarkation_port',
            'secondary_embarkation_port',
            'favoriate_curuise_line',
            'twond_favoriate_curuise_line',
            'cabine_preference',
            'preferred_deck_location',
            'curuise_note',
            'favorite_hotel_brand',
            'preferred_hotel_type',
            'bed_preference',
            'hotel_notes',
            'favorite_resort',
            'secoundary_resort',
            'preferred_room_type',
            'secoundary_room_type',
            'preferred_meal_plan',
            'preferred_atmosphere',
            'preferred_resort_type',
            'resort_notes',
            'favorite_toure_company',
            'secoundary_favorite_toure_company',
            'guided_tours_preferred_room_type',
            'guided_tours_notes',
            'favorite_car_rental_company',
            'secoundary_favrioute_car_reantal_company',
            'preferred_car_type',
            'car_rental_notes',
            'favorite_theme_park',
            'secoundary_favorite_theme_park',
            'oneside_offside_hotel',
            'theme_park_notes',
            'preference_status',
            
        ]);
        if ($preference) {
            // dd($data);
            $data['updated_at'] = now();
            $preference->where('id', $user->users_id)->where('traveler_id', $request->traveler_id)->update($data);
        } else {
            // 'preference_id',
            // 'id',
            $data['id'] = $user->users_id;
            $data['tr_id'] = $id;
            $data['created_at'] = now();
            $data['preference_status'] = 1;
            // Create a new record if none exists
            TripPreference::create($data);
        }
        session()->flash('activeTab',  'preferenceinfo');

        // return redirect()->route('trip.view',$id)->with('success', 'Trip preferences saved successfully!');
        return redirect()->back()->with('success', 'Trip preferences saved successfully!');
        
    }

    public function viewDeleteImage(Request $request, $tripid, $documentid, $image)
    {
    
        
        $library = TripDocument::where('trp_id', $documentid)->firstOrFail();
        // dd($library);
        $images = json_decode($library->trp_document, true);

        
    
        if (($key = array_search($image, $images)) !== false) {
            $userFolder = session('userFolder');
    
            unset($images[$key]);
    
            $data_image = json_encode(array_values($images)); // Re-index the array and encode it back to JSON
    
            $library->where('trp_id', $documentid)->update(['trp_document' => $data_image]); 
    
            $userFolder = storage_path('app/' . session('userFolder'). '/trip_document/');
            $filePath = $userFolder . $image; 
    
            // Log::info('Attempting to delete file at path: ' . $filePath);
    
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
                // Log::info('File deleted successfully: ' . $filePath);
            } else {
                // Log::warning('File does not exist: ' . $filePath);
            }

            if (empty($library->trp_document) || !$images) {
                // If trp_document is empty or null, delete the record
                $library->where('trp_id', $documentid)->delete();
                // return; // Exit the function to avoid further processing
            }
    
            // return redirect()->route('trip.edit', $id)->with('success', 'Image deleted successfully.');
            session()->flash('document-success', 'Image deleted successfully.');
            session()->flash('activeTab',  'TripDocumentinfo');
            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'redirect_url' => url()->previous() // URL to redirect after successful delete
            ]);
        }
        \MasterLogActivity::addToLog('Master Admin Library is Deleted.');
    
        // return redirect()->route('trip.edit', $id)->with('error', 'Image not found.');
    
        session()->flash('error', 'Image not found.');
        session()->flash('activeTab',  'TripDocumentinfo');
        return response()->json([
            'error' => false,
            'redirect_url' => url()->previous()// URL to redirect if image is not found
        ]);
        // return redirect()->back()->with('error', 'Image not found.');
    }
    
    public function storeTask1(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'trvt_name' => 'nullable|string|max:255',
            'trvt_agent_id' => 'nullable|string',
            'trvt_category' => 'required|string',
            'trvt_priority' => 'nullable|string',
            'trvt_date' => 'nullable|date',
            'trvt_due_date' => 'nullable|date',
            'trvt_note' => 'nullable|string',
            'trvt_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    
        $tripTask = new TripTask();
        $tableName = $tripTask->getTable();
        $uniqueId1 = $this->GenerateUniqueRandomString($tableName, 'trvt_id', 6);
    
        if ($request->hasFile('trvt_document')) {
            $userFolder = session('userFolder');
    
            $users_cert_document = $this->handleImageUpload($request, 'trvt_document', null, 'task_image', $userFolder);
    
            $validated['trvt_document'] = $users_cert_document;
        } else {
            $validated['trvt_document'] = $request->file('trvt_document') ? $request->file('trvt_document')->store('documents') : null;
        }
    
        $task = TripTask::updateOrCreate(
            [
                'tr_id' => $id,
                'trvt_name' => $request->input('trvt_name'),
                'trvt_id' => $request->input('trvt_id')
            ],
            [
                'trvt_agent_id' => $request->input('trvt_agent_id'),
                'trvt_category' => $request->input('trvt_category'),
                'trvt_priority' => $request->input('trvt_priority'),
                'trvt_date' => $request->input('trvt_date'),
                'trvt_due_date' => $request->input('trvt_due_date'),
                'trvt_note' => $request->input('trvt_note'),
                'trvt_document' => $validated['trvt_document'],
            ]
        );
    
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function storeTask(Request $request, $id)
    {
    //    dd($request->all());

        // Validate the request
        $validated = $request->validate([
            'trvt_name' => 'nullable|string|max:255',
            'trvt_agent_id' => 'nullable|string',
            'trvt_category' => 'required|string',
            'trvt_priority' => 'nullable|string',
            'trvt_date' => 'nullable|date',
            'trvt_due_date' => 'nullable|date',
            'trvt_note' => 'nullable|string',
            'trvt_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ],[
           
            'trvt_category.required' => 'Category is required',
            
        ]);
    
       
    
        // Check if the task exists
        $task = TripTask::where('tr_id', $id)
                         ->where('trvt_name', $request->input('trvt_name'))
                         ->where('trvt_id', $request->input('trvt_id'))
                         ->first();
    
            if ($request->hasFile('trvt_document')) {
            $userFolder = session('userFolder');

            // Handle the image upload
            $users_cert_document = $this->handleImageUpload($request, 'trvt_document', null, 'task_image', $userFolder);
            $validated['trvt_document'] = $users_cert_document;
        } else {
           $validated['trvt_document'] = $task->trvt_document;
        }
        // If task doesn't exist, assign a new trvt_id
        if (!$task) {
            $tripTask = new TripTask();
            $tableName = $tripTask->getTable();
            $validated['trvt_id'] = $this->GenerateUniqueRandomString($tableName, 'trvt_id', 6);
        }
    
        // Update or create the task
        $task = TripTask::updateOrCreate(
            [
                'tr_id' => $id,
                'trvt_name' => $request->input('trvt_name'),
                'trvt_id' => $request->input('trvt_id') ?? $validated['trvt_id']
            ],
            [
                'trvt_agent_id' => $request->input('trvt_agent_id'),
                'trvt_category' => $request->input('trvt_category'),
                'trvt_priority' => $request->input('trvt_priority'),
                'trvt_date' => $request->input('trvt_date'),
                'trvt_due_date' => $request->input('trvt_due_date'),
                'trvt_note' => $request->input('trvt_note'),
                'trvt_document' => $validated['trvt_document'],
                'status' => 1,
            ]
        );

        // dd($task);
        return response()->json(['success' => true, 'task' => $task]);
    }
    
    

    public function getTripTask($trip_id, $task_name)
    {
        // dD($trip_id,$task_name);
        $tripTask = TripTask::where('tr_id', $trip_id)
                            ->where('trvt_name', $task_name)
                            ->first();
        //dD($tripTask);
        if ($tripTask) {
            return response()->json(['exists' => true, 'data' => $tripTask]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    
}

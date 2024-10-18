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
class TripController extends Controller
{
    //
    public function index(): View
    {
        $user = Auth::guard('masteradmins')->user();
        $trip = Trip::where(['tr_status' => 1, 'id' => $user->id])->get();
        return view('masteradmin.trip.index', compact('trip'));
    }

    public function create(): View
    {
        $triptype = TripType::all();
        return view('masteradmin.trip.create',compact('triptype'));

    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id; 
        $validatedData = $request->validate([
            'tr_name' => 'required|string',
            'tr_agent_id' => 'required|string',
            'tr_traveler_name' => 'required|string',
            'tr_dob' => 'nullable|string',
            'tr_age' => 'nullable|string',
            'tr_email' => 'nullable|email',
            'tr_phone' => 'nullable|string',
            'tr_num_people' => 'nullable|string',
            'tr_number' => 'nullable|string',
            'tr_start_date' => 'required',
            'tr_end_date' => 'nullable|string',
            'tr_value_trip' => 'nullable|string',
            'tr_desc' => 'nullable|string',
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
            'tr_traveler_name.required' => 'Traveler name is required',
            'tr_email.email' => 'Invalid email address',
            'tr_start_date.required' => 'Start date is required',
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
            $traveler->id = $dynamicId;
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
            $traveler->status = 'Trip Request';
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
                $travelerItem->id = $dynamicId;
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
                    $typeOfTrip->id =  $dynamicId; 
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
                $itineraryItem->id = $dynamicId;
                $itineraryItem->trit_status = 1;
                $itineraryItem->trit_id = $uniqueId1itinerary;

                $itineraryItem->save();
            }
        }
    
        

        if($request->travelers == "travelers")
        {
            \MasterLogActivity::addToLog('Master Admin Travelers Created.');

            return redirect()->route('masteradmin.travelers.travelersDetails')
            ->with('success','Travelers created successfully.');
        }else{
            \MasterLogActivity::addToLog('Master Admin Trip Created.');

            return redirect()->route('trip.index')
            ->with('success','Trip created successfully.');
        }


    }

    public function edit($id)
    {
        // dd($id);
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $tripmember = TripTravelingMember::where('tr_id', $trip->tr_id)->get();
        // dd($tripmember);
        $triptype = TripType::all();
        return view('masteradmin.trip.edit',compact('trip','triptype', 'tripmember'));

    }

    public function update(Request $request, $id): RedirectResponse
    {

        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $trip = Trip::where(['tr_id' => $id])->firstOrFail();

        $validatedData = $request->validate([
            'tr_name' => 'required|string',
            'tr_agent_id' => 'required|string',
            'tr_traveler_name' => 'required|string',
            'tr_dob' => 'nullable|string',
            'tr_age' => 'nullable|string',
            'tr_email' => 'nullable|email', 
            'tr_phone' => 'nullable|string', 
            'tr_num_people' => 'nullable|string',
            'tr_number' => 'nullable|string',
            'tr_start_date' => 'required', 
            'tr_end_date' => 'nullable|string',
            'tr_value_trip' => 'nullable|string',
            'tr_desc' => 'nullable|string',
        ], [
            'tr_name.required' => 'Traveler name is required',
            'tr_agent_id.required' => 'Agent ID is required',
            'tr_traveler_name.required' => 'Traveler name is required',
            'tr_email.email' => 'Invalid email address',
            'tr_start_date.required' => 'Start date is required',
        ]);

    
        $trip->where(['tr_id' => $id])->update($validatedData);

        TripTravelingMember::where('tr_id', $id)->delete();

        $rawItems = $request->input('items');    
        
        if (isset($rawItems) && is_array($rawItems) && count($rawItems) > 0) {
            foreach ($rawItems as $item) {
                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);
                
                $travelerItem->fill($item);

                $travelerItem->tr_id = $id;
                $travelerItem->id = $user->id;
                $travelerItem->trtm_status = 1;
                $travelerItem->trtm_id = $uniqueId1;

                $travelerItem->save();
            }
        }
        if($request->travelers == "travelers")
        {
            \MasterLogActivity::addToLog('Master Admin Travelers Updated.');

            return redirect()->route('masteradmin.travelers.travelersDetails')
            ->with('success','Travelers updated successfully.');
        }else{
            \MasterLogActivity::addToLog('Master Admin Trip Updated.');
            return redirect()->route('trip.index')
    
            ->with('success','Trip updated successfully');
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

            ->with('success','Trip deleted successfully');

        }

    }

    public function view($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->id])->get();
        $tripTraveling = TripTravelingMember::where(['trtm_status' => 1, 'id' => $user->id, 'tr_id' => $id])->get();
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->id])->get();
        $documentType = DocumentType::get();

        // dd($trip);
        return view('masteradmin.trip.view',compact('trip','taskCategory','tripTraveling','documentType'));
    }

    public function travelersDetails(): View
    {
        $user = Auth::guard('masteradmins')->user();
        $trip = Trip::where(['tr_status' => 1, 'id' => $user->id])->get();
        return view('masteradmin.traveler.index', compact('trip'));
    }

    public function createTravelers(): View
    {
        $triptype = TripType::all();
        return view('masteradmin.traveler.create',compact('triptype'));
    }

    public function editDetails($id)
    {
        // dd($id);
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $tripmember = TripTravelingMember::where('tr_id', $trip->tr_id)->get();
        // dd($tripmember);
        $triptype = TripType::all();
        return view('masteradmin.traveler.edit',compact('trip','triptype', 'tripmember'));

    }
    
    public function viewDetails($id): View
    {
        // dd()
        $user = Auth::guard('masteradmins')->user();
        $trip = Trip::where('tr_id', $id)->firstOrFail();
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->id])->get();
        $tripTraveling = TripTravelingMember::where(['trtm_status' => 1, 'id' => $user->id, 'tr_id' => $id])->get();
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => $user->id])->get();
        $documentType = DocumentType::get();

        // dd($trip);
        return view('masteradmin.traveler.view',compact('trip','taskCategory','tripTraveling','documentType'));
    }
    

}
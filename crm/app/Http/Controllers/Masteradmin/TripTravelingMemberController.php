<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\TripTravelingMember;
use App\Models\Trip;

class TripTravelingMemberController extends Controller
{
    //
    public function index1(Request $request,$id)
    {
        // dd($id);
      
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();
        $member = TripTravelingMember::with(['travelingrelationship'])->where([ 'tr_id' => $id])->latest()->get();
        // dd($roles);
        $uniq_id = $user->user_id; 
        $tripData = \DB::table($uniq_id . '_tc_trip')
        ->select('tr_id', 'tr_traveler_name','tr_age')
        ->where('tr_id', $id)
        ->get();

        // Combine member and tripData
        $combinedData = $member->map(function($m) use ($tripData) {
        $tripInfo = $tripData->firstWhere('tr_id', $m->tr_id); // Assuming tr_id is the key to match
        return [
            'trtm_id' => $m->trtm_id ?? '',
            'trtm_relationship' => $m->travelingrelationship->rel_name ?? '',
            'tr_traveler_name' => $tripInfo->tr_traveler_name ?? '',
            'tr_age' => $tripInfo->tr_age ?? '',
            // Add other member fields as needed
        ];
    })->toArray(); 

    // dd($combinedData);

        if ($request->ajax()) {
            // $member = TripTravelingMember::with(['travelingrelationship'])->where(['tr_id' => $id])->latest()->get();
            //  dd($access);
            return Datatables::of($combinedData)
                    ->addIndexColumn()
                    // ->addColumn('trtm_relationship', function($status) {
                    //     return $status->travelingrelationship->rel_name ?? '';
                    // })
                    ->addColumn('action', function($members) use ($access){
                        $btn = '';
                        
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-id="'.$members->trtm_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editMember"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$members->trtm_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$members->trtm_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete Traveling Member </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Traveling Member ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteMemberbtn" data-id='.$members->trtm_id.'>Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>';
                        }
                        // dd($access);
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
                  
        }
      
        return view('masteradmin.trip.traveler-information', compact('member','tripData'));

    }

    public function index2(Request $request, $id)
{
    $access = view()->shared('access');
    $user = Auth::guard('masteradmins')->user();
    $member = TripTravelingMember::with(['travelingrelationship'])->where(['tr_id' => $id])->latest()->get();
    $uniq_id = $user->user_id; 
    $tripData = \DB::table($uniq_id . '_tc_trip')
        ->select('tr_id', 'tr_traveler_name', 'tr_age')
        ->where('tr_id', $id)
        ->first();

    // Combine member and tripData
    $combinedData = $member->map(function($member) use ($tripData) {
        // dD($tripData);
        return [
            'trtm_id' => $member->trtm_id ?? '',
            'trtm_relationship' => '',
            'trtm_full_name' => $tripData->tr_traveler_name ?? '',
            'trtm_age' => $tripData->tr_age ?? '',
            'action' => ''
        ];
    })->toArray();

    // Debugging output
    // Uncomment the line below to see the structure of combinedData
    // dd($combinedData);

    if ($request->ajax()) {
        return Datatables::of($member)
            ->addIndexColumn()
            
            ->addColumn('action', function($members) use ($access) {
                $btn = '';
                
                if (isset($access['workflow']) && $access['workflow']) {
                    $btn .= '<a data-id="'.$members['trtm_id'].'" data-toggle="tooltip" data-original-title="Edit Role" class="editMember"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                }
                if (isset($access['workflow']) && $access['workflow']) {
                    $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$members['trtm_id'].'">
                                <i class="fas fa-trash delete_icon_grid"></i>
                                <div class="modal fade" id="delete-role-modal-'.$members['trtm_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body pad-1 text-center">
                                                <i class="fas fa-solid fa-trash delete_icon"></i>
                                                <p class="company_business_name px-10"><b>Delete Traveling Member </b></p>
                                                <p class="company_details_text px-10">Are You Sure You Want to Delete This Traveling Member ?</p>
                                                <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="delete_btn px-15 deleteMemberbtn" data-id="'.$members['trtm_id'].'">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    return view('masteradmin.trip.traveler-information', compact('member', 'tripData'));
}

public function index(Request $request, $id)
{
    //dd($id);
    $access = view()->shared('access');
    $user = Auth::guard('masteradmins')->user();
    
    // Fetch members associated with the trip
    $members = TripTravelingMember::with(['travelingrelationship'])->where('lead_id', $id)->where('lead_status','!=',1)->latest()->get();
    
    // Fetch trip data
    $uniq_id = $user->user_id; 
    $tripData = \DB::table($uniq_id . '_tc_trip_traveling_member')
        ->select( 'trtm_first_name', 'trtm_age','trtm_id')
        ->where('trtm_id', $id)
        ->where('lead_status', 1)//main lead
        ->first();

    // Check if tripData is null
    if (!$tripData) {
        return response()->json(['error' => 'Trip data not found'], 404);
    }

    // Combine member and tripData
    $combinedData = [];
    
    // Add trip data to combinedData
    if ($tripData) {
        $combinedData[] = [
            'trtm_id' => $tripData->trtm_id ?? '', // No member ID for trip data
            'trtm_relationship' => 'Main Lead',  // No relationship for trip data
            'trtm_full_name' => $tripData->trtm_first_name ?? '',
            'trtm_age' => $tripData->trtm_age ?? '',
            'lead_id' => $id ?? '',
            'action' => '-' // Placeholder for action buttons
        ];
    }

    // Add members to combinedData
    foreach ($members as $member) {
        $combinedData[] = [
            'trtm_id' => $member->trtm_id ?? '',
            'trtm_relationship' => $member->travelingrelationship->rel_name ?? '',
            'trtm_full_name' => $member->trtm_first_name ?? '',
            'trtm_age' => $member->trtm_age ?? '',
            'lead_id' => $id ?? '',
            'action' => '' // Placeholder for action buttons
        ];
    }

    // Dd($combinedData);
    if ($request->ajax()) {
        return Datatables::of($combinedData)
            ->addIndexColumn()
            ->addColumn('action', function($members) use ($access) {
                $btn = '';
                if ($members['trtm_relationship'] == 'Main Lead') {
                // Add edit button if workflow access is enabled
                if (isset($access['traveler_details']) && $access['traveler_details']) {
                    $btn .= '<a data-id="'.$members['trtm_id'].'" data-toggle="tooltip" data-original-title="Edit Member" class="editMember">
                                <i class="fas fa-pen-to-square edit_icon_grid"></i>
                                </a>';

                }

                if (isset($access['traveler_details']) && $access['traveler_details']) {
                    $btn .= '<a href="'.route('member.preferences.edit', ['trvm_id' => $members['trtm_id'], 'lead_id' => $members['lead_id']]).'" 
                    data-toggle="tooltip" 
                    data-original-title="Set Preferences" 
                    class="editMember">
                    <i class="fas fa-sliders-h edit_icon_grid"></i>
                </a>';
                }
                
                return $btn;
                }

                
                if (isset($access['traveler_details']) && $access['traveler_details']) {
                    $btn .= '<a data-id="'.$members['trtm_id'].'" data-toggle="tooltip" data-original-title="Edit Role" class="editMember"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                }
                if (isset($access['traveler_details']) && $access['traveler_details']) {
                    $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$members['trtm_id'].'">
                                <i class="fas fa-trash delete_icon_grid"></i>
                                <div class="modal fade" id="delete-role-modal-'.$members['trtm_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body pad-1 text-center">
                                                <i class="fas fa-solid fa-trash delete_icon"></i>
                                                <p class="company_business_name px-10"><b>Delete Household</b></p>
                                                <p class="company_details_text px-10">Are You Sure You Want to Delete This Household?</p>
                                                <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="delete_btn px-15 deleteMemberbtn" data-id="'.$members['trtm_id'].'">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>';
                }

                if (isset($access['traveler_details']) && $access['traveler_details']) {
                    $btn .= '<a href="'.route('member.preferences.edit', ['trvm_id' => $members['trtm_id'], 'lead_id' => $members['lead_id']]).'" 
                    data-toggle="tooltip" 
                    data-original-title="Set Preferences" 
                    class="editMember">
                    <i class="fas fa-sliders-h edit_icon_grid"></i>
                </a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    return view('masteradmin.trip.traveler-information', compact('combinedData'));
}

    public function store(Request $request,$id)
    {
        // dd($id);
       // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id; 

    
            $validatedData = $request->validate([
                'trtm_type' => 'nullable|string',
                'trtm_first_name' => 'required|string',
                'trtm_middle_name' => 'nullable|string',
                'trtm_last_name' => 'nullable|string',
                'trtm_nick_name' => 'nullable|string',
                'trtm_relationship' => 'required',
                'trtm_gender' => 'required:items.*.trtm_type,2',
                'trtm_dob' => 'nullable|string',
                'trtm_age' => 'nullable|string',
            ], [
                // 'trtm_type.required' => 'Traveling member type is required',
                'trtm_first_name.required' => 'First name is required',
                // 'trtm_last_name.required' => 'Last name is required',
                'trtm_gender.required' => 'Gender is required',
                'trtm_relationship.required' => 'Relationship is required',
                // 'trtm_dob.required' => 'Birthdate is required',
                // 'trtm_age.required' => 'Age is required',
            ]);


                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);
                
                $travelerItem->fill($validatedData);

                $travelerItem->tr_id = '';
                $travelerItem->lead_id = $id;
                $travelerItem->id = $dynamicId;
                $travelerItem->trtm_status = 1;
                $travelerItem->trtm_id = $uniqueId1;
                $travelerItem->lead_status = 2;
                
                $travelerItem->save();
        

            \MasterLogActivity::addToLog('Master Admin Trip Member is Created.');
    
     
    
        return response()->json(['success'=>'Record saved successfully.']);
    }

    public function edit($trtm_id)
    {   
        
        $member = TripTravelingMember::where(['trtm_id' => $trtm_id ])->firstOrFail();

        // dd($member);
        return response()->json($member);
    }

    public function update(Request $request,$trtm_id)
    {
        // dd($trtm_id);
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id;
        $member = TripTravelingMember::where(['trtm_id' => $trtm_id])->firstOrFail();

        // dd($member);
        if($member)
        {

            $validatedData = $request->validate([
                'trtm_type' => 'required|string',
                'trtm_first_name' => 'required|string',
                'trtm_middle_name' => 'nullable|string',
                'trtm_last_name' => 'nullable|string',
                'trtm_nick_name' => 'nullable|string',
                'trtm_relationship' => 'nullable',
                'trtm_gender' => 'nullable:items.*.trtm_type,2',
                'trtm_dob' => 'required|string',
                'trtm_age' => 'nullable|string',
            ], [
                'trtm_type.required' => 'Traveling member type is required',
                'trtm_first_name.required' => 'First name is required',
                'trtm_last_name.required' => 'Last name is required',
                'trtm_gender.required' => 'Gender is required',
                'trtm_dob.required' => 'Birthdate is required',
                'trtm_age.required' => 'Age is required',
            ]);

                $member->where(['trtm_id' => $trtm_id])->update($validatedData);

                // $member->save();

                \MasterLogActivity::addToLog('Master Admin Trip Member is Updated.');
    
               
        }
        return response()->json(['success'=>'Record updated successfully.']);
    }

    public function destroy($trip_id, $trtm_id)
    {
        //
      // dd($trtm_id);
        $member = TripTravelingMember::where(['trtm_id' => $trtm_id])->firstOrFail();
        //dd($member);
        $member->where('trtm_id', $trtm_id)->delete();
        
        \MasterLogActivity::addToLog('Master Admin User role is Deleted.');
             
        return response()->json(['success'=>'Record deleted successfully.']);
    }

}

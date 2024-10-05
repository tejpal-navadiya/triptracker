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
    public function index(Request $request,$id)
    {
        // dd($id);
      
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();
        $member = TripTravelingMember::where(['id' => Auth::guard('masteradmins')->user()->id, 'tr_id' => $id])->latest()->get();
        // dd($roles);
    
        if ($request->ajax()) {
            $member = TripTravelingMember::where(['id' => $user->id, 'tr_id' => $id])->latest()->get();
            //  dd($access);
            return Datatables::of($member)
                    ->addIndexColumn()
                    ->addColumn('action', function($role) use ($access){
                        $btn = '';
                        
                        if(isset($access['edit_role']) && $access['edit_role']) {
                            $btn .= '<a data-id="'.$role->role_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editRole"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        if(isset($access['delete_role']) && $access['delete_role']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$role->role_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$role->role_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete User Role</b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This User Role?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteRolebtn" data-id='.$role->role_id.'>Delete</button>
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
      
        return view('masteradmin.trip.traveler-information', compact('member'));

    }

    public function store(Request $request, $id)
    {
        //dd($id);
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id; 

        $trip = Trip::where(['id' => Auth::guard('masteradmins')->user()->id, 'tr_id' => $id])->firstOrFail();
        if($trip)
        {
            $validatedData = $request->validate([
                'trtm_type' => 'required|string',
                'trtm_first_name' => 'required|string',
                'trtm_middle_name' => 'nullable|string',
                'trtm_last_name' => 'nullable|string',
                'trtm_nick_name' => 'nullable|string',
                'trtm_relationship' => 'nullable:items.*.trtm_type,1',
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


                $travelerItem = new TripTravelingMember();
                $tableName = $travelerItem->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trtm_id", $chars = 6);
                
                $travelerItem->fill($validatedData);

                $travelerItem->tr_id = $id;
                $travelerItem->id = $dynamicId;
                $travelerItem->trtm_status = 1;
                $travelerItem->trtm_id = $uniqueId1;

                $travelerItem->save();
            }

            \MasterLogActivity::addToLog('Master Admin Trip Created.');
    
     
    
        return response()->json(['success'=>'Record saved successfully.']);
    }

}

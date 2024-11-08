<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Models\TripTask;
use App\Models\TaskCategory;
use App\Models\MasterUserDetails;
use App\Models\Trip;



class TripTaskController extends Controller
{
    //
    public function index(Request $request,$id)
    {
         //dd($id);
      
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();

       // \DB::enableQueryLog();


        $member = TripTask::where(['tr_id' => $id])->with(['taskstatus','tripCategory'])->latest()->get();
       // dd($member);
       // dd(\DB::getQueryLog()); 


        // dd($member);

    
        if ($request->ajax()) {
            $member = TripTask::where([ 'tr_id' => $id])->with(['taskstatus','tripCategory'])->latest()->get();
            //  dd($access);

            return Datatables::of($member)


                    ->addIndexColumn()
                    ->addColumn('status_name', function($status) {
                        return $status->taskstatus->ts_status_name ?? '';
                    })

                    ->addColumn('trvt_category', function($category) {
                        return $category->tripCategory->task_cat_name ?? '';
                    })


                    ->addColumn('action', function($members) use ($access){
                        $btn = '';
                       
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-id="'.$members->trvt_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editTask"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$members->trvt_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$members->trvt_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete Task </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteTaskbtn" data-id='.$members->trvt_id.'>Delete</button>
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
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id; 

            $validatedData = $request->validate([
                'trvt_name' => 'required|string',
                'trvt_agent_id' => 'required|string',
                'trvt_category' => 'required|string',
                'trvt_priority' => 'required|string',
                'trvt_date' => 'required|string',
                'trvt_due_date' => 'required|string',
               'trvt_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Single file validation
               'trvt_document.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048', // Multiple files validation

            ], [
                'trvt_name.required' => 'Task Name is required',    
                'trvt_agent_id.required' => 'Assign Agent is required',
                'trvt_category.required' => 'Category is required',
                'trvt_priority.required' => 'Priority is required',
                'image.image' => 'The Document field is required.',

                'trvt_document.mimes' => 'Only jpg, jpeg, png, and pdf files are allowed.', // Error message for single file
                'trvt_document.*.mimes' => 'Only jpg, jpeg, png, and pdf files are allowed.', // Error message for multiple files

            ]);


                $tripTask = new TripTask();
                $tableName = $tripTask->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvt_id", $chars = 6);

                if ($request->hasFile('trvt_document')) {
                  
                    $userFolder = session('userFolder');
                    
                    $users_cert_document =  $this->handleImageUpload($request, 'trvt_document', null, 'task_image', $userFolder);
        
                    $validatedData['trvt_document'] = $users_cert_document;
                }
                
                $tripTask->fill($validatedData);

                $tripTask->tr_id = $id;
                $tripTask->id = $dynamicId;
                $tripTask->trvt_status = 1;
                $tripTask->status = 1;
                $tripTask->trvt_id = $uniqueId1;

                $tripTask->save();
          
            \MasterLogActivity::addToLog('Master Admin Trip Task is Created.');
    
        return response()->json(['success'=>'Record saved successfully.']);
    }


    public function edit($id, $trip_id)
    {   
        
        $role = TripTask::where(['trvt_id' => $id, 'tr_id' => $trip_id])->firstOrFail();

        return response()->json($role);
    }



    public function update(Request $request, $tr_id, $trvt_id)
{
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->users_id; 

    // Fetch the existing task
    $task = TripTask::where(['id' => $dynamicId, 'tr_id' => $tr_id, 'trvt_id' => $trvt_id])->firstOrFail();

    if ($task) {
        // Validate request data
        $validatedData = $request->validate([
            'trvt_name' => 'required|string',
            'trvt_agent_id' => 'required|string',
            'trvt_category' => 'required|string',
            'trvt_priority' => 'required|string',
            'trvt_date' => 'required|string',
            'trvt_due_date' => 'required|string',
            'trvt_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Single file validation
            'status' => 'nullable',
        ], [
            'trvt_name.required' => 'Task Name is required',
            'trvt_agent_id.required' => 'Assign Agent is required',
            'trvt_category.required' => 'Category is required',
            'trvt_priority.required' => 'Priority is required',
            'trvt_document.*.mimes' => 'Only jpg, jpeg, png, and pdf files are allowed.', // Error message for multiple files
        ]);

        // Check if new document files are uploaded
        if ($request->hasFile('trvt_document')) {
            $userFolder = session('userFolder');
            // Handle file upload like in the store method
            $users_cert_document = $this->handleImageUpload($request, 'trvt_document', null, 'task_image', $userFolder);
            $validatedData['trvt_document'] = $users_cert_document;
        }

        // Update task with validated data
        $task->fill($validatedData);
        $task->save();

        \MasterLogActivity::addToLog('Master Admin Trip Task is Updated.');

        return response()->json(['success' => 'Record updated successfully.']);
    }

    return response()->json(['error' => 'Record not found.'], 404);
}




    
    // public function update(Request $request, $tr_id, $trvt_id)
    // {
    //    dd($request->all());
    //     $user = Auth::guard('masteradmins')->user();
    //     $dynamicId = $user->users_id; 
    //     $task = TripTask::where(['id' => Auth::guard('masteradmins')->user()->id, 'tr_id' => $tr_id, 'trvt_id' => $trvt_id])->firstOrFail();

    //     // dd($task);
    //     if($task)
    //     {
    //         $validatedData = $request->validate([
    //             'trvt_name' => 'required|string',
    //             'trvt_agent_id' => 'required|string',
    //             'trvt_category' => 'required|string',
    //             'trvt_priority' => 'required|string',
    //             'trvt_date' => 'required|string',
    //             'trvt_due_date' => 'required|string',

    //             'trvt_document.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048', // For multiple file validation
                 
    //             'status' => 'nullables',
    //         ], [
    //             'trvt_name.required' => 'Task Name is required',
    //             'trvt_agent_id.required' => 'Assign Agent is required',
    //             'trvt_category.required' => 'Category is required',
    //             'trvt_priority.required' => 'Priority is required',
    //             'image.image' => 'The Document field is required.',

    //             'trvt_document.*.mimes' => 'Only jpg, jpeg, png, and pdf files are allowed.', // Error message for multiple files


    //         ]);
    //         $task->where('trvt_id' , $trvt_id)->update($validatedData);

    //         $task->save();

    //         \MasterLogActivity::addToLog('Master Admin Trip Task is Updated.');

    //         return response()->json(['success'=>'Record updated successfully.']);
    //     }
    // }
    
    public function destroy($trip_id, $trvt_id)
    {
        //
      // dd($trtm_id);
        $task = TripTask::where(['trvt_id' => $trvt_id])->firstOrFail();
        //dd($member);
        $task->where('trvt_id', $trvt_id)->delete();
        
        \MasterLogActivity::addToLog('Master Admin Trip Task is Deleted.');
             
        return response()->json(['success'=>'Record deleted successfully.']);
    }
    // public function allDetails(Request $request)
    // {  
                 
    //     $trip_agent = trim($request->input('trip_agent'));   
    //     $trip_traveler = trim($request->input('trip_traveler'));   

    //     $access = view()->shared('access');
    //     $user = Auth::guard('masteradmins')->user();
    
    //     // Get master user details for filtering
    //     $masterUserDetails = new MasterUserDetails();
    //     $masterUserDetails->setTableForUniqueId($user->user_id); 
    //     $agency = $masterUserDetails->get();

    //     $task = TripTask::where(['id' => Auth::guard('masteradmins')->user()->id])->with(['trip','tripCategory','taskstatus'])->latest()->first();

    //     $tripQuery = Trip::where('tr_status', 1)->where('id', $user->users_id);

    //     $trips = new Trip();
    //     $tripTable = $trips->getTable();
        
    //     if ($trip_agent) {
    //         $tripQuery->where('tr_agent_id', $trip_agent);
    //     }
    
    //     if ($trip_traveler) {
    //         $tripQuery->where('tr_traveler_name', 'LIKE', "%{$trip_traveler}%");
    //     }
    
    //     $trip = $tripQuery->get();

    //     if ($request->ajax()) {

    //         $task = TripTask::where(['id' => $user->id])->with(['trip','tripCategory','taskstatus'])->latest()->get();
    //         //    dd($task);
    //         return Datatables::of($task)
    //                 ->addIndexColumn()
    //                 ->addColumn('trip_name', function($document) {
    //                     $trip_name = $document->trip->tr_name ?? '';
                        
    //                     return $trip_name;
    //                 })
    //                 ->addColumn('agent_name', function($document) {
    //                     $agent_name = $document->trip->tr_agent_id ?? '';
                        
    //                     return $agent_name;
    //                 })
    //                 ->addColumn('traveler_name', function($document) {
    //                     $traveler_name = $document->trip->tr_traveler_name ?? '';
                        
    //                     return $traveler_name;
    //                 })
    //                 ->addColumn('task_status_name', function($document) {
    //                     $task_status_name = $document->taskstatus->ts_status_name ?? '';
                        
    //                     return $task_status_name;
    //                 })
    //                 ->addColumn('task_cat_name', function($document) {
    //                     $task_cat_name = $document->tripCategory->task_cat_name ?? '';
                        
    //                     return $task_cat_name;
    //                 })
    //                 ->addColumn('trvt_due_date', function($document) {
    //                     $trvt_due_date = \Carbon\Carbon::parse($document->trvt_due_date)->format('M d, Y')  ?? '';
                        
    //                     return $trvt_due_date;
    //                 })
    //                 ->addColumn('action', function($members) use ($access){
    //                     $btn = '';
                        
    //                     if(isset($access['edit_role']) && $access['edit_role']) {
    //                         $btn .= '<a data-id="'.$members->trvt_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editTask"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
    //                     }
                        
    //                     if(isset($access['delete_role']) && $access['delete_role']) {
    //                         $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$members->trvt_id.'">
    //                                     <i class="fas fa-trash delete_icon_grid"></i>
    //                                     <div class="modal fade" id="delete-role-modal-'.$members->trvt_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    //                                         <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    //                                             <div class="modal-content">
    //                                                 <div class="modal-body pad-1 text-center">
    //                                                     <i class="fas fa-solid fa-trash delete_icon"></i>
    //                                                     <p class="company_business_name px-10"><b>Delete Task </b></p>
    //                                                     <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
    //                                                     <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
    //                                                     <button type="submit" class="delete_btn px-15 deleteTaskbtn" data-id='.$members->trvt_id.'>Delete</button>
    //                                                 </div>
    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </a>';
    //                     }
    //                     // dd($access);
    //                     return $btn;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->toJson();
                  
    //     }
      
    //     $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => Auth::guard('masteradmins')->user()->id])->get();

    //     return view('masteradmin.task.index',compact('task','taskCategory','agency','trip'));
    // }
    
    public function allDetails(Request $request)
    {
        $tripAgent = trim($request->input('trip_agent'));
        $tripTraveler = trim($request->input('trip_traveler'));
    
        $access = view()->shared('access');
        $user = Auth::guard('masteradmins')->user();
        // dD($user);
        $task = TripTask::where(['id' => $user->users_id])->with(['trip','tripCategory','taskstatus'])->latest()->first();

    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id);
        $agency = $masterUserDetails->get();

        
    
        $tripQuery = Trip::where('tr_status', 1)->where('id', $user->users_id);
    
        if ($tripAgent) {
            $tripQuery->where('tr_agent_id', $tripAgent);
        }
    
        if ($tripTraveler) {
            $tripQuery->where('tr_traveler_name', 'LIKE', "%{$tripTraveler}%");
        }
    
        $trip = $tripQuery->get();

        // dd($trip);
        
        if ($request->ajax()) {
            $taskQuery = TripTask::where('id', $user->users_id)
                ->with(['trip', 'tripCategory', 'taskstatus'])
                ->latest();
    
            if ($tripAgent) {
                $taskQuery->whereHas('trip', function ($q) use ($tripAgent) {
                    $q->where('tr_agent_id', $tripAgent);
                });
            }
    
            if ($tripTraveler) {
                $taskQuery->whereHas('trip', function ($q) use ($tripTraveler) {
                    $q->where('tr_traveler_name', 'LIKE', "%{$tripTraveler}%");
                });
            }
    
            $tasks = $taskQuery->get();
    
            return Datatables::of($tasks)
                ->addIndexColumn()
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return optional($document->trip)->tr_agent_id ?? '';
                })
                ->addColumn('traveler_name', function ($document) {
                    return optional($document->trip)->tr_traveler_name ?? '';
                })
                ->addColumn('task_status_name', function ($document) {
                    return optional($document->taskstatus)->ts_status_name ?? '';
                })
                ->addColumn('task_cat_name', function ($document) {
                    return optional($document->tripCategory)->task_cat_name ?? '';
                })
                ->addColumn('trvt_due_date', function ($document) {
                    return optional($document->trvt_due_date) ? \Carbon\Carbon::parse($document->trvt_due_date)->format('M d, Y') : '';
                })
                ->addColumn('action', function ($members) use ($access) {
                    $btn = '';
    
                    if(isset($access['workflow']) && $access['workflow']) {
                        $btn .= '<a data-id="'.$members->trvt_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editTask"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                    }
                    
                    if (isset($access['workflow']) && $access['workflow']) {
                        $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-' . $members->trvt_id . '">
                                    <i class="fas fa-trash delete_icon_grid"></i>
                                    <div class="modal fade" id="delete-role-modal-' . $members->trvt_id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body pad-1 text-center">
                                                    <i class="fas fa-solid fa-trash delete_icon"></i>
                                                    <p class="company_business_name px-10"><b>Delete Task </b></p>
                                                    <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
                                                    <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="delete_btn px-15 deleteTaskbtn" data-id=' . $members->trvt_id . '>Delete</button>
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
    
        $taskCategory = TaskCategory::where('task_cat_status', 1)->where('id', $user->id)->get();
       
        return view('masteradmin.task.index', compact('task', 'taskCategory', 'agency', 'trip'));
    }
    

    public function incompleteDetails(Request $request)
    {           
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();

        $task = TripTask::where(['id' => Auth::guard('masteradmins')->user()->id, 'status' => 'Incomplete'])->with(['trip','tripCategory'])->latest()->first();
        // dd($task );
    
        if ($request->ajax()) {
            $task = TripTask::where(['id' => $user->id, 'status' => 'Incomplete'])->with(['trip','tripCategory'])->latest()->get();
            //    dd($task);
            return Datatables::of($task)
                    ->addIndexColumn()
                    ->addColumn('trip_name', function($document) {
                        $trip_name = $document->trip->tr_name ?? '';
                        
                        return $trip_name;
                    })
                    ->addColumn('agent_name', function($document) {
                        $agent_name = $document->trip->tr_agent_id ?? '';
                        
                        return $agent_name;
                    })
                    ->addColumn('traveler_name', function($document) {
                        $traveler_name = $document->trip->tr_traveler_name ?? '';
                        
                        return $traveler_name;
                    })
                    ->addColumn('task_cat_name', function($document) {
                        $task_cat_name = $document->tripCategory->task_cat_name ?? '';
                        
                        return $task_cat_name;
                    })
                    ->addColumn('trvt_due_date', function($document) {
                        $trvt_due_date = \Carbon\Carbon::parse($document->trvt_due_date)->format('M d, Y')  ?? '';
                        
                        return $trvt_due_date;
                    })
                    ->addColumn('action', function($members) use ($access){
                        $btn = '';
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-id="'.$members->trvt_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editTask"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal1-'.$members->trvt_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal1-'.$members->trvt_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete Task </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteTaskbtn1" data-id='.$members->trvt_id.'>Delete</button>
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
      
        $taskCategory = TaskCategory::where(['task_cat_status' => 1, 'id' => Auth::guard('masteradmins')->user()->id])->get();

        return view('masteradmin.task.reminder-information',compact('task','taskCategory'));
    }

}
    
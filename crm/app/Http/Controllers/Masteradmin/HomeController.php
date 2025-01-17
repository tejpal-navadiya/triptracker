<?php

namespace App\Http\Controllers\Masteradmin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use Illuminate\Support\Facades\DB; // Add this line
use Carbon\Carbon;

use App\Models\TripTask;
use App\Models\TaskCategory;
use App\Models\MasterUserDetails; 
use DataTables;
use App\Models\TaskStatus;
use App\Models\TripTravelingMember;


class HomeController extends Controller
{
    //
    // public function create()
    // {
    //     $user = Auth::guard('masteradmins')->user();
    //     // dd($user);
    //     if (!$user) {
    //         return redirect()->route('masteradmin.login'); 
    //     }

    //     $masterUser = $user->masterUser()->first(); 
    //     // dd($masterUser);
    //     $plan = $masterUser->sp_id;
       
    //     if (!$plan) {
    //         session()->flash('showModal', 'Please purchase a plan first.');
    //     }elseif ($masterUser->sp_expiry_date < now()) {
    //         session()->flash('showModal', 'Your plan has expired. Please purchase a new plan.');
    //     }

    //     return view('masteradmin.auth.home');
    // }
    public function create()
    {
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        if (!$user) {
            return redirect()->route('masteradmin.login'); 
        }
        $currentDate = Carbon::now();
    
        // Fetch the total trip count
        // $tripModel = new Trip();
        // $tripModel->setTableForUniqueId($user->user_id);
        
        // $totalTrips = $tripModel->count(); 

        $tripModel = new Trip();
        $tripTable = $tripModel->getTable();
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id); 
        $masterUserTable = $masterUserDetails->getTable();
        $specificId = $user->users_id;

        if($user->users_id && $user->role_id ==0 ){
        //$totalTrips = $tripModel->count(); 
        $totalTrips = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')->count();

        }else{
            $totalTrips = Trip::where('tr_status', 1)
            ->from($tripTable)
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count();

        }

        // Fetch dynamic data for Trip Request vs. Booked
        if($user->users_id && $user->role_id ==0 ){
            $totalRequests = $tripModel->where('status', 1)->count(); // Assuming 1 = Trip Requested
        }else{
            $totalRequests = $tripModel->where('status', 1)
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count(); // Assuming 1 = Trip Requested

        }

        if($user->users_id && $user->role_id ==0 ){
        $totalBooked = $tripModel->where('status', 4)->count();   // Assuming 2 = Trip Booked
        
        }else{
            $totalBooked = $tripModel->where('status', 4)
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count(); 
            
        }
        $totalStatusTrips = $totalRequests + $totalBooked;

        // Calculate percentages
        $requestPercentage = $totalStatusTrips ? round(($totalRequests / $totalStatusTrips) * 100, 2) : 0;
        $bookedPercentage = $totalStatusTrips ? round(($totalBooked / $totalStatusTrips) * 100, 2) : 0;
        if ($user->users_id && $user->role_id == 0){
            $inProgressTrips = $tripModel->where('tr_status', 1) 
            ->whereRaw("STR_TO_DATE({$tripTable}.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
            ->whereRaw("STR_TO_DATE({$tripTable}.tr_end_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
            ->count();
        }else{
            $inProgressTrips = $tripModel
            ->whereRaw("STR_TO_DATE({$tripTable}.tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
            ->whereRaw("STR_TO_DATE({$tripTable}.tr_end_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", [$currentDate->format('m/d/Y')])
             ->where(function($query) use ($tripTable, $user, $specificId)
              { $query->where($tripTable . '.tr_agent_id', $user->users_id)
                 ->orWhere($tripTable . '.id', $specificId); })
                  ->count();
        }
        // $inProgressTrips = $tripModel->where(['status'=> 9,'id'=> $user->users_id])->count(); 
        if($user->users_id && $user->role_id ==0 ){

        $totalcompletedTrips = $tripModel->where(['status'=> 7])->count();
        }else{

            $totalcompletedTrips = $tripModel->where(['status'=> 7])
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count(); 

        }  

        if($user->users_id && $user->role_id ==0 ){
            $completedTrips = $tripModel->where(['status'=> 7])->count(); 
        }else{
            $completedTrips = $tripModel->where(['status'=> 7])
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count(); 

        } 

        if($user->users_id && $user->role_id ==0 ){
        $acceptTrips = $tripModel->where(['status'=> 4])->count(); 
        }else{
            $acceptTrips = $tripModel->where(['status'=> 4])
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })->count(); 
 
        } 

         // Fetch monthly completed trips count
         if($user->users_id && $user->role_id ==0 ){
            $completedTrips = $tripModel
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 7)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    
         }else{
            $completedTrips = $tripModel
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 7)
            ->where(function($query) use ($tripTable, $user, $specificId) {
                $query->where($tripTable . '.tr_agent_id', $user->users_id)
                    ->orWhere($tripTable . '.id', $specificId);  // Use $specificId here
            })
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    
         }
        
        // Prepare data for all 12 months
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $completedTrips[$i] ?? 0;
        }


        if ($user->users_id && $user->role_id == 0) {
            $travelercountQuery = TripTravelingMember::where('trtm_status', 1)
            ->where('lead_status', 1);
                // ->leftjoin($masterUserTable, $tripTable . '.trtm_agent_id', '=', $masterUserTable . '.users_id');
        } else {
            $travelercountQuery = TripTravelingMember::where('trtm_status', 1)
            ->where('lead_status', 1)
            ->where(function ($query) use ($user) {
                // Filtering trips based on the user being either the agent or traveler
                $query->where('trtm_agent_id', $user->users_id)
                      ->orWhere('id', $user->users_id);  // Assuming traveler is identified by user ID in the trip
            });
        }
        
        // Perform count on the query without selecting unnecessary fields
        $travelercount = $travelercountQuery->count();
        

      // Fetch the total user count
        $userModel = new MasterUserDetails();
        $userModel->setTableForUniqueId($user->user_id); 
        $totalUserCount = $userModel->where('users_email', '!=', $user->users_email)->count();

        return view('masteradmin.auth.home', compact('monthlyData',
        'totalTrips',
        'inProgressTrips',
        'completedTrips',
        'totalUserCount',
        'acceptTrips',
        'user',
        'totalcompletedTrips',
        'requestPercentage',
        'bookedPercentage',
        'totalRequests',
        'totalBooked','travelercount'));
    }

    public function incompleteDetailshome(Request $request)
    {           
        
        $access = view()->shared('access');
        $user = Auth::guard('masteradmins')->user();
        // dd()

       
        if ($request->ajax()) {
            try {
            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id);
            $masterUserDetailsTable = $masterUserDetails->getTable();  

            $tripTable = (new Trip())->getTable(); 
            $tripTaskTable = (new TripTask())->getTable();  
            // dd($tripTaskTable);
            $today = date('m/d/Y');
           // dd($today);
        //    \DB::enableQueryLog();
            if($user->users_id && $user->role_id ==0 ){

                
                $taskQuery = TripTask::where($tripTaskTable.'.status', 1)
                ->leftJoin($masterUserDetailsTable, "{$masterUserDetailsTable}.users_id", '=', "{$tripTaskTable}.trvt_agent_id")
                ->where($tripTaskTable.'.trvt_due_date', '<=', $today) 
                ->orderByRaw("FIELD(trvt_priority, 'High', 'Medium', 'Low')")
                ->with([
                    'trip.lead_traveler_name' => function ($query) {
                        $query->select('trtm_id', 'trtm_first_name'); // Fetch only the necessary fields
                    },
                    'trip',
                    'tripCategory',
                    'taskstatus'
                ]);
                
            }else{
                $taskQuery = TripTask::where($tripTaskTable.'.id', $user->users_id)
                ->leftJoin($masterUserDetailsTable, "{$masterUserDetailsTable}.users_id", '=', "{$tripTaskTable}.trvt_agent_id")
                ->where('trvt_agent_id', $user->users_id)
                ->where($tripTaskTable.'.status', 1)
                ->where($tripTaskTable.'.trvt_due_date', '<=', $today) 
                ->orderByRaw("FIELD(trvt_priority, 'High', 'Medium', 'Low')")
                ->with([
                    'trip.lead_traveler_name' => function ($query) {
                        $query->select('trtm_id', 'trtm_first_name'); // Fetch only the necessary fields
                    },
                    'trip',
                    'tripCategory',
                    'taskstatus'
                ]);
            }
           

        
            // \DB::enableQueryLog();
            $tasks = $taskQuery->get();
            // dd($tasks);
            // dd(\DB::getQueryLog()); 

            return Datatables::of($tasks)
                
                ->addIndexColumn()
                ->addColumn('trvt_name', function($task_name) {
                    $fullName = strip_tags($task_name->trvt_name);
                    $truncatedName = Str::limit($fullName, 30, '...');
                    
                    return '<span data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($fullName) . '">' . $truncatedName . '</span>';
                })
                ->addColumn('trip_name', function ($document) {
                    return optional($document->trip)->tr_name ?? '';
                })
                ->addColumn('agent_name', function ($document) {
                    return trim(($document->users_first_name ?? '') . ' ' . ($document->users_last_name ?? ''));
                })

                ->addColumn('traveler_name', function ($document) {
                    return optional($document->trip->lead_traveler_name ?? '')->trtm_first_name ?? '';

                })
                ->addColumn('task_status_name', function ($document) {
                    return optional($document->taskstatus)->ts_status_name ?? '';
                })
                ->addColumn('task_cat_name', function ($document) {
                    if ($document->trvt_category === '0') {
                        return 'System Created';
                    }
                    return optional($document->tripCategory)->task_cat_name ?? '';
                })
                ->addColumn('trvt_due_date', function($trvt_due_date) {
                    if (isset($trvt_due_date->trvt_due_date) && $trvt_due_date->trvt_due_date) {
                        $trvt_due_date = \Carbon\Carbon::parse($trvt_due_date->trvt_due_date)->format('M d, Y');
                    } else {
                        $trvt_due_date = '';
                    }
                
                    return $trvt_due_date;
                })
                ->addColumn('action', function ($members) use ($access) {
                    $btn = '';
    
                    if(isset($access['task_details']) && $access['task_details']) {
                        $btn .= '<a data-id="'.$members->trvt_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editTaskreminder"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                    }
                    
                    if (isset($access['task_details']) && $access['task_details']) {
                        $btn .= '<a data-toggle="modal" data-target="#delete-role-modalreminder-' . $members->trvt_id . '">
                                    <i class="fas fa-trash delete_icon_grid"></i>
                                    <div class="modal fade" id="delete-role-modalreminder-' . $members->trvt_id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body pad-1 text-center">
                                                    <i class="fas fa-solid fa-trash delete_icon"></i>
                                                    <p class="company_business_name px-10"><b>Delete Task </b></p>
                                                    <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
                                                    <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="delete_btn px-15 deleteTaskbtnreminder" data-id=' . $members->trvt_id . '>Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>';
                    }
                    // dd($btn);
                    return $btn;
                })
                ->rawColumns(['action','trvt_name'])
                ->toJson();
            } catch (\Exception $e) {
                \Log::error('Error in incompleteDetails AJAX: ', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }
      
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
    $task = TripTask::where(['tr_id' => $tr_id, 'trvt_id' => $trvt_id])->firstOrFail();

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
        $task->where(['tr_id' => $tr_id, 'trvt_id' => $trvt_id])->update($validatedData);
        // $task->save();

        \MasterLogActivity::addToLog('Master Admin Trip Task is Updated.');

        return response()->json(['success' => 'Record updated successfully.']);
    }

    return response()->json(['error' => 'Record not found.'], 404);
}

    
}

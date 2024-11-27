<?php

namespace App\Http\Controllers\Masteradmin;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use Illuminate\Support\Facades\DB; // Add this line

use App\Models\TripTask;
use App\Models\TaskCategory;
use App\Models\MasterUserDetails; 
use DataTables;

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
    
        $masterUser = $user->masterUser()->first(); 
        $plan = $masterUser->sp_id;
    
        if (!$plan) {
            session()->flash('showModal', 'Please purchase a plan first.');
        } elseif ($masterUser->sp_expiry_date < now()) {
            session()->flash('showModal', 'Your plan has expired. Please purchase a new plan.');
        }
    
        // Fetch the total trip count
        $tripModel = new Trip();
        $tripModel->setTableForUniqueId($user->user_id);
        $totalTrips = $tripModel->count(); 
   // Fetch dynamic data for Trip Request vs. Booked
   $totalRequests = $tripModel->where('status', 1)->count(); // Assuming 1 = Trip Requested
   $totalBooked = $tripModel->where('status', 4)->count();   // Assuming 2 = Trip Booked

   $totalStatusTrips = $totalRequests + $totalBooked;

   // Calculate percentages
   $requestPercentage = $totalStatusTrips ? round(($totalRequests / $totalStatusTrips) * 100, 2) : 0;
   $bookedPercentage = $totalStatusTrips ? round(($totalBooked / $totalStatusTrips) * 100, 2) : 0;

        
        $inProgressTrips = $tripModel->where(['status'=> 9,'id'=> $user->users_id])->count(); 
        $totalcompletedTrips = $tripModel->where(['status'=> 7,'id'=> $user->users_id])->count();
        $completedTrips = $tripModel->where(['status'=> 7,'id'=> $user->users_id])->count(); 
    
        $acceptTrips = $tripModel->where(['status'=> 4,'id'=> $user->users_id])->count(); 
      

         // Fetch monthly completed trips count
    $completedTrips = $tripModel
    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
    ->where('status', 7)
    ->groupBy('month')
    ->orderBy('month')
    ->pluck('count', 'month')
    ->toArray();

// Prepare data for all 12 months
$monthlyData = [];
for ($i = 1; $i <= 12; $i++) {
    $monthlyData[] = $completedTrips[$i] ?? 0;
}

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
        'totalBooked'));
    }

    public function incompleteDetailshome(Request $request)
    {           
        $tripAgent = trim($request->input('trip_agent'));
        $tripTraveler = trim($request->input('trip_traveler'));
    
        $access = view()->shared('access');
        $user = Auth::guard('masteradmins')->user();
        // dD($user);

        $task = TripTask::where(['id' => $user->users_id,'status' => 3])->with(['trip','tripCategory','taskstatus'])->latest()->first();

    
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($user->user_id);
        if($user->users_id && $user->role_id ==0 ){
            $agency_user = $masterUserDetails->where('users_id', '!=', $user->users_id)->get(); 
        }else{
            $agency_user = $masterUserDetails->where('users_id' , $user->users_id)->get(); 
        }

        
    
        $tripQuery = Trip::where('tr_status', 1)->where('id', $user->users_id);

        $trip = $tripQuery->get();

        // dd($trip);
        
        if ($request->ajax()) {
            $masterUserDetails = new MasterUserDetails();
            $masterUserDetails->setTableForUniqueId($user->user_id);
            $masterUserDetailsTable = $masterUserDetails->getTable();  

            $tripTable = (new Trip())->getTable(); 
            $tripTaskTable = (new TripTask())->getTable();  

            if($user->users_id && $user->role_id ==0 ){
                $taskQuery = TripTask::where($tripTaskTable.'.id', $user->users_id)
                ->leftJoin($masterUserDetailsTable, "{$masterUserDetailsTable}.users_id", '=', "{$tripTaskTable}.trvt_agent_id")
                ->where($tripTaskTable.'.status', 3)
                ->with(['trip', 'tripCategory', 'taskstatus']);
                
            }else{
                $taskQuery = TripTask::where($tripTaskTable.'.id', $user->users_id)
                ->leftJoin($masterUserDetailsTable, "{$masterUserDetailsTable}.users_id", '=', "{$tripTaskTable}.trvt_agent_id")
                ->where('trvt_agent_id', $user->users_id)
                ->where($tripTaskTable.'.status', 3)
                ->with(['trip', 'tripCategory', 'taskstatus']);
            }
           

            // $taskQuery = TripTask::where($tripTaskTable.'.id', $user->users_id)
            // ->leftJoin($masterUserDetailsTable, "{$masterUserDetailsTable}.users_id", '=', "{$tripTaskTable}.trvt_agent_id")
            // ->where('trvt_agent_id', $user->users_id)
            // ->with(['trip', 'tripCategory', 'taskstatus']);
      
     

    
            if ($tripAgent) {
                $taskQuery->where($tripTaskTable . '.trvt_agent_id', $tripAgent);
            }
    
    
            if ($tripTraveler) {
                $taskQuery->whereHas('trip', function ($q) use ($tripTraveler) {
                    $q->where('tr_traveler_name', 'LIKE', "%{$tripTraveler}%");
                });
            }
    
            $tasks = $taskQuery->get();
            // dd($tasks);
            
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
                    
                  
                    return $btn;
                })
                ->rawColumns(['action','trvt_name'])
                ->toJson();
       
        }
        if($user->users_id && $user->role_id ==0 ){
            $taskCategory = TaskCategory::where('task_cat_status', 1)->get();
        }else{
            $taskCategory = TaskCategory::where('task_cat_status', 1)->where('id', $user->users_id)->get();
        }

        return view('masteradmin.auth.home',compact('task','taskCategory', 'agency_user','trip'));
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

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TripTask;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUser;
use Illuminate\Http\JsonResponse;
use Validator;
use Carbon\Carbon;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use App\Models\MasterUserDetails;
use App\Models\TripStatus;
use App\Models\TaskStatus;


class TripController extends Controller
{
    //
    use ApiResponse;

    public function GetTripList(Request $request) 
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 500);
            }

            $uniqueId = $request->header('X-UniqueId');

            $trip = new Trip();

            if ($auth_user->users_id) {
                $trip->setTableForUniqueId($uniqueId);  
                $tripTable = $trip->getTable();
            }

            // dd($trip);
            
            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 
            
             $masterUserDetails = new MasterUserDetails();
             $masterUserDetails->setTableForUniqueId($uniqueId); 
             $masterUserTable = $masterUserDetails->getTable();

            $trips = $trip
                        ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
                        ->where('tr_status', 1)
                        ->orderBy($tripTable.'.created_at', 'desc')
                        ->paginate($perPage);
                        
                        // dd($trips);

        foreach ($trips as $tripItem) {
            $tripItem->uniqueId = $uniqueId;

            $memberCount = DB::table($uniqueId . '_tc_trip_traveling_member')
                ->where('tr_id', $tripItem->tr_id) 
                ->count();

            $tripItem->member_count = $memberCount;

            $taskCounts = DB::table($uniqueId . '_tc_traveling_task')
                ->selectRaw('
                    COUNT(CASE WHEN trvt_priority = "Medium" THEN 1 END) AS medium_count,
                    COUNT(CASE WHEN trvt_priority = "High" THEN 1 END) AS high_count,
                    COUNT(CASE WHEN trvt_priority = "Low" THEN 1 END) AS low_count
                ')
                ->where('tr_id', $tripItem->tr_id) 
                ->first();

            $tripItem->medium_count = $taskCounts->medium_count ?? 0; 
            $tripItem->high_count = $taskCounts->high_count ?? 0;
            $tripItem->low_count = $taskCounts->low_count ?? 0; 
        }
                
        //   dd($trips);

            if ($trips->isEmpty()) {
                return $this->sendError('No Trip found.', [], 500);
            }
            // dd($trips->items());
            $response = [
                'total_records' => $trips->total(),
                'per_page' => $trips->perPage(),
                'current_page' => $trips->currentPage(),
                'total_page' => $trips->lastPage(),
                 'data' => $this->TripListResponse($trips->items()), 
            ];

            // $formattedResponse = $this->TripListResponse($response['data']);

            return $this->sendResponse($response, __('messages.api.trip.list_success'));          
        }
        catch(\Exception $e)
        {
            
            $this->serviceLogError('GetTripList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }    
    }

    public function GetTaskList(Request $request) 
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 500);
            }

            $uniqueId = $request->header('X-UniqueId');

            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 

            $task = DB::table($uniqueId . '_tc_traveling_task')
                // ->where('tr_id', $request->tr_id) 
                // ->where('trvt_status', 1)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
        //   dd($task);

            if ($task->isEmpty()) {
                return $this->sendError('No Task found.', [], 500);
            }

            foreach ($task as $taskItem) {
                $taskItem->uniqueId = $uniqueId;
    
                    $member = DB::table($uniqueId . '_tc_task_category')
                    ->where('task_cat_id', $taskItem->trvt_category) 
                    ->first(); 

                if ($member) {
                    $taskItem->task_cat_name = $member->task_cat_name; 
                } else {
                    $taskItem->task_cat_name = ''; 
                }

                $trip = DB::table($uniqueId . '_tc_trip')
                ->where('tr_id', $taskItem->tr_id) 
                ->first();

                if ($trip) {
                    $taskItem->tr_name = $trip->tr_name; 
                } else {
                    $taskItem->tr_name = ''; 
                }

                $task_status = DB::table('task_status')
                ->where('ts_status_id', $taskItem->status) 
                ->first();
              //  dd($task_status);
                if ($task_status) {
                    $taskItem->status = $task_status->ts_status_name; 
                } else {
                    $taskItem->status = ''; 
                }


            }
            //dd($taskItem);
            $response = [
                'total_records' => $task->total(),
                'per_page' => $task->perPage(),
                'current_page' => $task->currentPage(),
                'total_page' => $task->lastPage(),
                'data' => $this->TripTaskListResponse($task->items()), 
            ];

            return $this->sendResponse($response, __('messages.api.task.list_success'));      
        }
        catch(\Exception $e)
        {
            
            $this->serviceLogError('GetTaskList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }    
    }

    public function GetTaskReminderList(Request $request)
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 500);
            }

            $uniqueId = $request->header('X-UniqueId');

            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 
            $today = date('m/d/Y');
            $task = DB::table($uniqueId . '_tc_traveling_task')
                // ->where('tr_id', $request->tr_id) 
                // ->where('id', $auth_user->users_id)
                ->where('trvt_date', '=', $today) 
                ->where('status', 1) 
                // ->where('trvt_status', 1)
                ->orderByRaw("FIELD(trvt_priority, 'High', 'Medium', 'Low')")
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
        //   dd($trips);

            if ($task->isEmpty()) {
                return $this->sendError('No Task found.', [], 500);
            }

            foreach ($task as $taskItem) {
                $taskItem->uniqueId = $uniqueId;
    
                    $member = DB::table($uniqueId . '_tc_task_category')
                    ->where('task_cat_id', $taskItem->trvt_category) 
                    ->first(); 

                if ($member) {
                    $taskItem->task_cat_name = $member->task_cat_name; 
                } else {
                    $taskItem->task_cat_name = ''; 
                }

                $trip = DB::table($uniqueId . '_tc_trip')
                ->where('tr_id', $taskItem->tr_id) 
                ->first();

                if ($trip) {
                    $taskItem->tr_name = $trip->tr_name; 
                } else {
                    $taskItem->tr_name = ''; 
                }

                $task_status = DB::table('task_status')
                ->where('ts_status_id', $taskItem->status) 
                ->first();
              //  dd($task_status);
                if ($task_status) {
                    $taskItem->status = $task_status->ts_status_name; 
                } else {
                    $taskItem->status = ''; 
                }

            }
            //dd($taskItem);
            $response = [
                'total_records' => $task->total(),
                'per_page' => $task->perPage(),
                'current_page' => $task->currentPage(),
                'total_page' => $task->lastPage(),
                'data' => $this->TripTaskListResponse($task->items()), 
            ];

            return $this->sendResponse($response, __('messages.api.task.list_success'));      
        }
        catch(\Exception $e)
        {
            $this->serviceLogError('GetTaskReminderList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }    
    }

    public function AddTrip(Request $request)
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 500);
            }

            $uniqueId = $request->header('X-UniqueId');

          
         
            
            //dd($taskItem);
            // $response = [
            //     'data' => $this->TripTaskListResponse($task->items()), 
            // ];

            // return $this->sendResponse($response, __('messages.api.task.list_success'));      
        }
        catch(\Exception $e)
        {
            
            $this->serviceLogError('GetTaskList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }    
    }
    
    public function filterTrip(Request $request)
    {
    try {
        $auth_user = $request->attributes->get('authenticated_user');
        if (!$auth_user) {
            return $this->sendError('Unauthenticated.', [], 500);
        }

        $uniqueId = $request->header('X-UniqueId');
        $trip = new Trip();

        if ($auth_user->users_id) {
            $trip->setTableForUniqueId($uniqueId);  
            $tripTable = $trip->getTable();
        }

        $page = $request->input('page', 1); 
        $perPage = env('PER_PAGE', 10); 
        
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($uniqueId); 
        $masterUserTable = $masterUserDetails->getTable();

        // Start building the query
        $tripQuery = $trip
            ->join($masterUserTable, $tripTable . '.tr_agent_id', '=', $masterUserTable . '.users_id')
            ->where('tr_status', 1);

        // Apply filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $trip_agent = $request->input('trip_agent');
        $trip_traveler = $request->input('trip_traveler');
        $trip_status1 = $request->input('trip_status');

        if ($startDate && !$endDate) {
            $tripQuery->whereRaw(
                "STR_TO_DATE(tr_start_date, '%m/%d/%Y') = STR_TO_DATE(?, '%m/%d/%Y')", 
                [$startDate]
            );
        } elseif ($startDate && $endDate) {
            $tripQuery->whereRaw(
                "STR_TO_DATE(tr_start_date, '%m/%d/%Y') >= STR_TO_DATE(?, '%m/%d/%Y')", 
                [$startDate]
            )
            ->whereRaw(
                "STR_TO_DATE(tr_start_date, '%m/%d/%Y') <= STR_TO_DATE(?, '%m/%d/%Y')", 
                [$endDate]
            );
        }

        if ($trip_agent) {
            $tripQuery->where($tripTable . '.tr_agent_id', $trip_agent);
        }

        if ($trip_traveler) {
            $tripQuery->where($tripTable . '.tr_traveler_name', 'LIKE', "%{$trip_traveler}%");
        }

        if ($trip_status1) {
            $tripQuery->where($tripTable . '.status', $trip_status1);
        }

        // Apply sorting and pagination
        $trips = $tripQuery
            ->orderBy($tripTable.'.created_at', 'desc')
            ->paginate($perPage);

        // Enrich trip data
        foreach ($trips as $tripItem) {
            $tripItem->uniqueId = $uniqueId;

            $memberCount = DB::table($uniqueId . '_tc_trip_traveling_member')
                ->where('tr_id', $tripItem->tr_id) 
                ->count();

            $tripItem->member_count = $memberCount;

            $taskCounts = DB::table($uniqueId . '_tc_traveling_task')
                ->selectRaw('
                    COUNT(CASE WHEN trvt_priority = "Medium" THEN 1 END) AS medium_count,
                    COUNT(CASE WHEN trvt_priority = "High" THEN 1 END) AS high_count,
                    COUNT(CASE WHEN trvt_priority = "Low" THEN 1 END) AS low_count
                ')
                ->where('tr_id', $tripItem->tr_id) 
                ->first();

            $tripItem->medium_count = $taskCounts->medium_count ?? 0; 
            $tripItem->high_count = $taskCounts->high_count ?? 0;
            $tripItem->low_count = $taskCounts->low_count ?? 0; 
        }

        if ($trips->isEmpty()) {
            return $this->sendError('No Trip found.', [], 500);
        }

        $response = [
            'total_records' => $trips->total(),
            'per_page' => $trips->perPage(),
            'current_page' => $trips->currentPage(),
            'total_page' => $trips->lastPage(),
            'data' => $this->TripListResponse($trips->items()), 
        ];

        return $this->sendResponse($response, __('messages.api.trip.list_success'));          
    } catch (\Exception $e) {
        $this->serviceLogError('GetTripList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
        return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
    }    
}

    public function filterTask(Request $request) 
    {
    try {
        $auth_user = $request->attributes->get('authenticated_user');
        if (!$auth_user) {
            return $this->sendError('Unauthenticated.', [], 500);
        }

        $uniqueId = $request->header('X-UniqueId');
        $page = $request->input('page', 1); 
        $perPage = env('PER_PAGE', 10); 

        // Retrieve filter parameters from the request
        $tripAgent = $request->input('trip_agent');
        $tripTraveler = $request->input('trip_traveler');

        // Initialize query
        $taskQuery = DB::table($uniqueId . '_tc_traveling_task')->orderBy('created_at', 'desc');

        // Apply filters
        if ($tripAgent) {
            $taskQuery->where('trvt_agent_id', $tripAgent);
        }

        if ($tripTraveler) {
            $taskQuery->whereExists(function ($query) use ($uniqueId, $tripTraveler) {
                $query->select(DB::raw(1))
                    ->from($uniqueId . '_tc_trip')
                    ->whereRaw($uniqueId . '_tc_trip.tr_id = ' . $uniqueId . '_tc_traveling_task.tr_id')
                    ->where('tr_traveler_name', 'LIKE', "%{$tripTraveler}%");
            });
        }

        // Pagination
        $task = $taskQuery->paginate($perPage);

        if ($task->isEmpty()) {
            return $this->sendError('No Task found.', [], 500);
        }

        foreach ($task as $taskItem) {
            $taskItem->uniqueId = $uniqueId;

            // Add task category name
            $member = DB::table($uniqueId . '_tc_task_category')
                ->where('task_cat_id', $taskItem->trvt_category) 
                ->first();

            $taskItem->task_cat_name = $member->task_cat_name ?? ''; 

            // Add trip name
            $trip = DB::table($uniqueId . '_tc_trip')
                ->where('tr_id', $taskItem->tr_id) 
                ->first();

            $taskItem->tr_name = $trip->tr_name ?? ''; 

            // Add task status name
            $task_status = DB::table('task_status')
                ->where('ts_status_id', $taskItem->status) 
                ->first();

            $taskItem->status = $task_status->ts_status_name ?? ''; 
        }

        // Prepare the response
        $response = [
            'total_records' => $task->total(),
            'per_page' => $task->perPage(),
            'current_page' => $task->currentPage(),
            'total_page' => $task->lastPage(),
            'data' => $this->TripTaskListResponse($task->items()), 
        ];

        return $this->sendResponse($response, __('messages.api.task.list_success'));      
    } catch (\Exception $e) {
        $this->serviceLogError('GetTaskList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
        return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
    }
}

    public function allAgentList(Request $request) 
    {
    try {
        $auth_user = $request->attributes->get('authenticated_user');
        if (!$auth_user) {
            return $this->sendError('Unauthenticated.', [], 500);
        }

        $uniqueId = $request->header('X-UniqueId');
        $perPage = $request->input('per_page', env('PER_PAGE', 10)); // Number of records per page, default to 10
        $page = $request->input('page', 1); // Current page, default to 1

        // Instantiate MasterUserDetails and set the table
        $masterUserDetails = new MasterUserDetails();
        $masterUserDetails->setTableForUniqueId($auth_user->user_id);

        // Fetch agency users based on the user's role and ID
        if ($auth_user->users_id && $auth_user->role_id == 0) {
            $agencyUsersQuery = $masterUserDetails;
        } else {
            $agencyUsersQuery = $masterUserDetails->where('users_id', $auth_user->user_id);
        }

        // Paginate the query
        $agencyUsers = $agencyUsersQuery->paginate($perPage, ['*'], 'page', $page);

        // Check if any agency users are found
        if ($agencyUsers->isEmpty()) {
            return $this->sendError('No agents found.', [], 404);
        }

        // Prepare the response data
        $responseData = $agencyUsers->map(function ($user) {
            return [
                'id' => $user->users_id,
                'users_first_name' => $user->users_first_name,
                'users_last_name' => $user->users_last_name,
                'users_email' => $user->users_email,
                'created_at' => $user->created_at,
            ];
        });

        // Prepare the paginated response
        $response = [
            'total_records' => $agencyUsers->total(),
            'per_page' => $agencyUsers->perPage(),
            'current_page' => $agencyUsers->currentPage(),
            'total_page' => $agencyUsers->lastPage(),
            'data' => $responseData,
        ];

        // Send a successful response with the data
        return $this->sendResponse($response, __('messages.api.agent.list_success'));
    } catch (\Exception $e) {
        $this->serviceLogError('AllAgentList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
        return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
    }
}
    
    public function TripStatus(Request $request) 
    {
         try {
            $tripStatuses = TripStatus::all();
    
            if ($tripStatuses->isEmpty()) {
                return $this->sendError('No Trip Status found.', [], 404);
            }
    
            return $this->sendResponse($tripStatuses, __('messages.api.trip.status_list_success'));
        } catch (\Exception $e) {
            $this->serviceLogError('GetAllTripStatus', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }
    }
    
    
    public function TaskStatus(Request $request) 
    {
         try {
            $taskStatuses = TaskStatus::all();
    
            if ($taskStatuses->isEmpty()) {
                return $this->sendError('No Task Status found.', [], 404);
            }
    
            return $this->sendResponse($taskStatuses, __('messages.api.task.status_list_success'));
        } catch (\Exception $e) {
            $this->serviceLogError('GetAllTaskStatus', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 500, false);
        }
    }
    
    

    
    
}

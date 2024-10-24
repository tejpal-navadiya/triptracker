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
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $uniqueId = $request->header('X-UniqueId');

            $trip = new Trip();

            if ($auth_user->users_id) {
                $trip->setTableForUniqueId($uniqueId);  
            }

            // dd($trip);
            
            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 

            $trips = $trip->where('tr_status', 1)
                        ->where('id', $auth_user->users_id)
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);

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
                return $this->sendError('No Trip found.', [], 404);
            }
            
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
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function GetTaskList(Request $request) 
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $uniqueId = $request->header('X-UniqueId');

            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 

            $task = DB::table($uniqueId . '_tc_traveling_task')
                ->where('tr_id', $request->tr_id) 
                ->where('id', $auth_user->users_id)
                ->where('trvt_status', 1)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
        //   dd($trips);

            if ($task->isEmpty()) {
                return $this->sendError('No Task found.', [], 404);
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
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function GetTaskReminderList(Request $request)
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 401);
            }

            $uniqueId = $request->header('X-UniqueId');

            $page = $request->input('page', 1); 
            $perPage = env('PER_PAGE', 10); 

            $task = DB::table($uniqueId . '_tc_traveling_task')
                ->where('tr_id', $request->tr_id) 
                ->where('id', $auth_user->users_id)
                ->where('status', 3) 
                ->where('trvt_status', 1)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
        //   dd($trips);

            if ($task->isEmpty()) {
                return $this->sendError('No Task found.', [], 404);
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
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }

    public function AddTrip(Request $request)
    {
        try{
            $auth_user = $request->attributes->get('authenticated_user');
            //dd($auth_user->users_id);
            if (!$auth_user) {
                return $this->sendError('Unauthenticated.', [], 401);
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
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }
    
}

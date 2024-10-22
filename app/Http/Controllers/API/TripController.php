<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUser;
use Illuminate\Http\JsonResponse;
use Validator;
use Carbon\Carbon;
use App\Models\Trip;

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


            if ($trips->isEmpty()) {
                return $this->sendError('No Trip found.', [], 404);
            }
            
            $response = [
                'total_records' => $trips->total(),
                'per_page' => $trips->perPage(),
                'current_page' => $trips->currentPage(),
                'total_page' => $trips->lastPage(),
                'data' => $trips->items(), 
            ];

            $result = $this->TripListResponse($response);

            return $this->sendResponse($response, __('messages.api.trip.list_success'));          
        }
        catch(\Exception $e)
        {
            
            $this->serviceLogError('GetTripList', $user_id = 0, $e->getMessage(), json_encode($request->all()), $e);
            return $this->sendError($e->getMessage(), config('global.null_object'), 401, false);
        }    
    }
}

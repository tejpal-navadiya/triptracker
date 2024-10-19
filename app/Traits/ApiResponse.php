<?php

namespace App\Traits;
use DB;
use Carbon\Carbon;

trait ApiResponse
{
    public function sendResponse($result, $message)
    {
     $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**

    * Return an error JSON response.

    *

    * @param  string  $message

    * @param  int  $code

    * @param  array|string|null  $data

    * @return \Illuminate\Http\JsonResponse

    */

    public function sendError($error, $errorMessages = [], $code = 404)
    {
     $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function serviceLogError($service_name = 'service_name',$user_id = 0,$message = 'message',$requested_field = 'requested_field',$response_data="response_data")
    {
        $service_error_log = [
            'service_name'      => $service_name,
            'user_id'           => $user_id,
            'message'           => $message,
            'requested_field'   => $requested_field,
            'response_data'     => $response_data,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
        DB::table('service_error_log')->insert($service_error_log);
    }

}
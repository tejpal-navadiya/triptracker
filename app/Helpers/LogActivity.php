<?php

namespace App\Helpers;
use App\Http\Controllers\Controller;
use Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\LogActivities as LogActivityModel;

class LogActivity
{
    public static function addToLog($subject)
    {	

        $user = Auth::user();
    	$log = [];
        $controller = new Controller();
        $id = $controller->GenerateUniqueRandomString($table='log_activities_table', $column="id", $chars=6);

        $log['id'] = $id;
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = $user->id;
    
    	LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }

	public static function deleteOldLogs()
    {
        // Define your threshold date (1 day ago)
        $thresholdDate = Carbon::now()->subDay()->toDateTimeString();

        // Delete log entries older than the threshold date
        $deleted = LogActivityModel::where('created_at', '<', $thresholdDate)->delete();
    }
}
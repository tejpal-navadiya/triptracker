<?php

namespace App\Helpers;
use Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\MasterAdminLogActivities as MasterLogActivityModel;

class MasterLogActivity
{
    public static function addToLog($subject)
    {
        $user = Auth::guard('masteradmins')->user();
        if (!$user) {
            return; // Handle cases where the user is not authenticated
        }
        // dd($user);
        // Create an instance of MasterAdminLogActivities
        $logActivityModel = new MasterLogActivityModel();
        // Set the table name for this user's logs
        $logActivityModel->setTableForUniqueId($user->user_id);

        $log = [
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'agent' => Request::header('user-agent'),
            'user_id' => $user->users_id,
        ];

        // Create the log entry
        $logActivityModel->create($log);
    }

    public static function logActivityLists()
    {
        $user = Auth::guard('masteradmins')->user();
        if (!$user) {
            return collect(); // Handle cases where the user is not authenticated
        }

        $logActivityModel = new MasterLogActivityModel();
        $logActivityModel->setTableForUniqueId($user->user_id);
        // dd($logActivityModel->get());
        return $logActivityModel->latest()->get();
    }

    public static function deleteOldLogs(int $days = 1)
    {
        
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $thresholdDate = Carbon::now()->subDays($days)->toDateTimeString();
        // \DB::enableQueryLog();

        $logActivityModel = new MasterLogActivityModel();
        $logActivityModel->setTableForUniqueId($user->buss_unique_id);

        $deleted = $logActivityModel->where('created_at', '<', $thresholdDate)->delete();
        // dd(\DB::getQueryLog()); 
        // dd($deleted);
    }
}
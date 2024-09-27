<?php

namespace App\Http\Controllers\superadmin;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\View\View;
use App\Models\MasterUser;
use App\Models\MasterUserDetails;
use DB;
use App\Http\Controllers\Controller;

class BusinessDetailController extends Controller
{
    public function index(): View
    {
        $MasterUser = MasterUser::with('plan')->get();

        $MasterUser->each(function ($user) {
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($user->buss_unique_id);
            $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
            $user->totalUserCount = $totalUserCount;

        });
        // dd($MasterUser);
        return view('superadmin.businessdetails.index')->with('MasterUser', $MasterUser);
    }
    public function show($id)
    {
        $user = MasterUser::findOrFail($id);

        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);

        $udetail = $userDetails->where('users_email', '!=', $user->user_email)->get();
        $totalUserCount = $userDetails->where('users_email', '!=', $user->user_email)->count();
        $user->totalUserCount = $totalUserCount;

        $tableName = $user->buss_unique_id . '_tc_users_role';

        foreach ($udetail as $detail) {
            $role = DB::table($tableName)->where('role_id', $detail->role_id)->first();
            $detail->role_name = $role ? $role->role_name : 'No Role Assigned';
        }

        return view('superadmin.businessdetails.view_business', compact('user', 'udetail'));
    }
    public function updateStatus($id)
    {
        // Fetch the user (business) by ID
        $business = MasterUser::findOrFail($id);

        // Toggle the business status (assuming the field is 'user_status')
        $business->user_status = $business->user_status == 1 ? 0 : 1;
        $business->save();

        // Return JSON response with the updated status
        return response()->json(['status' => 'success', 'new_status' => $business->user_status]);
    }



}

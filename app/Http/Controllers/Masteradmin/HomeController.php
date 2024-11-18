<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use App\Models\MasterUserDetails;
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

        $inProgressTrips = $tripModel->where(['status'=> 9,'id'=> $user->users_id])->count(); 
       
        $completedTrips = $tripModel->where(['status'=> 7,'id'=> $user->users_id])->count(); 
    
        $acceptTrips = $tripModel->where(['status'=> 4,'id'=> $user->users_id])->count(); 
      
      // Fetch the total user count
        $userModel = new MasterUserDetails();
        $userModel->setTableForUniqueId($user->user_id); 
        $totalUserCount = $userModel->where('users_email', '!=', $user->users_email)->count();

        return view('masteradmin.auth.home', compact('totalTrips','inProgressTrips','completedTrips','totalUserCount','acceptTrips','user'));
    }
    
}

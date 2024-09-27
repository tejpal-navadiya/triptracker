<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function create()
    {
        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        if (!$user) {
            return redirect()->route('masteradmin.login'); 
        }

        $masterUser = $user->masterUser()->first(); 
        // dd($masterUser);
        $plan = $masterUser->sp_id;

        if (!$plan) {
            session()->flash('showModal', 'Please purchase a plan first.');
        }elseif ($masterUser->sp_expiry_date < now()) {
            session()->flash('showModal', 'Your plan has expired. Please purchase a new plan.');
        }

        return view('masteradmin.auth.home');
    }

}

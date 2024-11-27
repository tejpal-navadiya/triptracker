<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUser;

class HomesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function create()
    // {
    //     $user = Auth::guard('web')->user();
      
    //     if (!$user) {
    //         return redirect()->route('login'); // Handle unauthenticated cases
    //     }

    //     return view('auth.dashboard');
    // }
    public function create()
    {
        $user = Auth::guard('web')->user();
      
        if (!$user) {
            return redirect()->route('login'); // Handle unauthenticated cases
        }

        // Fetching total counts
        $totalBusinesses = MasterUser::count();
        $activeBusinesses = MasterUser::where('user_status', 1)->count();
        $inactiveBusinesses = MasterUser::where('user_status', 0)->count();

        // Pass the data to the view
        return view('auth.dashboard', compact('totalBusinesses', 'activeBusinesses', 'inactiveBusinesses'));
    }

}

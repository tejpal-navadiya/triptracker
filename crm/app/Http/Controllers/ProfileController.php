<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
       
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($user->image) {
                Storage::delete('superadmin/profile_image/' . $user->image);
            }

            // Generate a unique filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $uniqueFilename = Str::uuid() . '.' . $extension;

            // Store the new image in 'storage/app/superadmin/profile_image' directory
            $request->file('image')->storeAs('superadmin/profile_image', $uniqueFilename);

            // Save the unique filename to the database
            $user->image = $uniqueFilename;
        }
        
        $request->user()->save();
        \LogActivity::addToLog('Admin User Profile Updated.');
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function logActivity()
    {
        $user = Auth::guard('web')->user();
        // dd($user);
        if($user)
        {
            $admin_user = User::where('user_id','=',$user->id);
            // \DB::enableQueryLog();
            
            $logs = \LogActivity::logActivityLists();
            // dd($logs);

            $logs->transform(function ($log) use ($user) {
                $log->user_name = $user ? $user->name : 'Unknown'; 
                return $log;
            });
            // dd($logs);
            // dd(\DB::getQueryLog()); 
            
                return view('superadmin.logs.index')
                                            ->with('admin_user',$admin_user)
                                            ->with('logs',$logs);

            return redirect()->action('Admin\AdminLoginController@index')->with('failure','You are not authorized to access.');
        }else{
            return redirect()->action('Admin\AdminLoginController@index')->with('failure','You need to login first.');
        }
    }

    

}

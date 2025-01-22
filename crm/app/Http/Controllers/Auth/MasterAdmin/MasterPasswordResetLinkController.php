<?php

namespace App\Http\Controllers\Auth\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Notifications\ResetPasswordMail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\MasterUserDetails;
use App\Models\MasterUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;

class MasterPasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('masteradmin.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate request
        $request->validate([
            'user_email' => 'required|email',
            'user_id' => 'required|string',
        ]);
        
        $token = Str::random(64);
    
        // Check if email exists in the table
        $email = $request->user_email;
        $user_id = $request->user_id;
        $existingToken = DB::table('master_password_reset_tokens')->where('email', $email)->first();
       // dd($existingToken);
        if ($existingToken) {
            // Update the existing record
            DB::table('master_password_reset_tokens')
                ->where('email', $email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            // Insert a new record
            DB::table('master_password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }
        
        
        // Fetch the user associated with the email
        //$users = MasterUser::where('buss_unique_id', $user_id)->first();
        //dd($users);
        // if (!$users) {
        //     // Handle the case where the user is not found
        //     return back()->withErrors(['user_email' => __('messages.masteradmin.forgot-password.user_not_found')]);

        // }
        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user_id);


        $userWithRoleZero = $userDetails->where('user_id', $request->user_id)
        ->where('role_id', '0')
        ->where('users_email', $request->user_email)
        ->first();
        if ($userWithRoleZero) {
            // dd('if');
            // If user with role_id == 0 exists, update password using users_email
            $usersd = $userDetails->where('users_email', $request->user_email)
                ->where('user_id', $request->user_id)
                ->where('role_id', '0')->first();
        } else {
            // dd( 'else');
            // If no user with role_id == 0, update password using user_work_email
            $usersd = $userDetails->where('user_work_email', $request->user_email)
                ->where('user_id', $request->user_id)
                ->first();
        }

        
        // Checking if user exists before accessing role_id
        // if ($user->isNotEmpty() && $user->first()->role_id == '0') {
          

        //     $user = $userDetails->where('users_email', $email)->first();
        // } else {
        //     $user = $userDetails->where('user_work_email', $email)->first();
        // }

        // if ($user->isNotEmpty()) {
        //     $users = $user->where('role_id', '0');
        
        //     if ($user) {
        //         $users = $userDetails->where('role_id', '0')->where('users_email', $email)->first();
        //     } else {
        //         $users = $userDetails->where('user_work_email', $email)->first();
        //     }
        // }
     
        if (!$userWithRoleZero) {
            // Handle the case where the user is not found
            return back()->withErrors(['user_email' => __('messages.masteradmin.forgot-password.user_not_found')]);

        }
    
       // Send the email
        try {
            Mail::to($email)->send(new ResetPasswordMail($token, $email,$user_id));
            return back()->with(['forgotpassword-link-success' =>__('messages.masteradmin.forgot-password.link_send_success')]);
        } catch (\Exception $e) {
            return back()->with(['forgotpassword-link-error' =>__('messages.masteradmin.forgot-password.link_send_error')]);
        }
    }
}

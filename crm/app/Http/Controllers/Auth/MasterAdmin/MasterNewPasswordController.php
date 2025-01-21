<?php

namespace App\Http\Controllers\Auth\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use DB;
use App\Models\MasterUser;
use App\Models\MasterUserDetails;


class MasterNewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // dd($request);
        $email = $request->query('email');
        $token = $request->route('token'); 
        $user_id = $request->query('user_id'); 
        return view('masteradmin.auth.reset-password', ['email' => $email,'token' => $token, 'user_id' => $user_id]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
      // dd($request->all());
        $request->validate([
            'token' => ['required'],
            'user_email' => ['required', 'email'],
            'user_password' => [
                function ($attribute, $value, $fail) {
                    $errors = [];

                    // Include all validation checks in the same message, even if empty
                    if (empty($value)) {
                        $errors[] = 'be provided';
                    }
                    if (strlen($value) < 8) {
                        $errors[] = 'have at least 8 characters';
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $errors[] = 'contain at least one lowercase letter';
                    }
                    if (!preg_match('/[A-Z]/', $value)) {
                        $errors[] = 'contain at least one uppercase letter';
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        $errors[] = 'contain at least one number';
                    }
                    if (!preg_match('/[@$!%*?&]/', $value)) {
                        $errors[] = 'contain at least one special character';
                    }

                    // Combine all errors into a single message
                    if (!empty($errors)) {
                        $fail('Password must ' . implode(', ', $errors) . '.');
                    }
                },
            ],
        ], [
            'user_email.required' => 'The Email field is required.',
            'user_email.email' => 'The Email must be a valid email address.',
            'user_password.required' => 'The Password field is required.',
            'password_confirmation.required' => 'The confirmation Password field is required.',
        ]);
        
       
            $updatePassword = DB::table('master_password_reset_tokens')
                                ->where([
                                'email' => $request->user_email, 
                                'token' => $request->token
                                ])
                                ->first();
                                // dd(\DB::getQueryLog());
            // dd($updatePassword);                     
            if(!$updatePassword){
                return back()->with(['forgotpassword-error' =>__('messages.masteradmin.forgot-password.send_error')]);
            }
            // $users = MasterUser::where('user_email', $request->user_email)->first();
            // $user = MasterUser::where('user_email', $request->user_email)
            // ->update(['user_password' => Hash::make($request->user_password)]);
            $userDetails = new MasterUserDetails();
            $userDetails->setTableForUniqueId($request->user_id);
         

            $userWithRoleZero = $userDetails->where('user_id', $request->user_id)
                                ->where('role_id', 0)
                                ->where('users_email', $request->user_email)
                                ->first();

            if ($userWithRoleZero) {
                // dd('if');
                // If user with role_id == 0 exists, update password using users_email
                $usersd = $userDetails->where('users_email', $request->user_email)
                    ->where('user_id', $request->user_id)
                    ->update(['users_password' => Hash::make($request->user_password)]);
            } else {
                // dd( 'else');
                // If no user with role_id == 0, update password using user_work_email
                $usersd = $userDetails->where('user_work_email', $request->user_email)
                    ->where('user_id', $request->user_id)
                    ->update(['users_password' => Hash::make($request->user_password)]);
            }

          

            DB::table('master_password_reset_tokens')->where(['email'=> $request->user_email])->delete();
           
            return redirect()->route('masteradmin.login')->with(['forgotpassword-success' =>__('messages.masteradmin.forgot-password.send_success')]);
            
    }
}

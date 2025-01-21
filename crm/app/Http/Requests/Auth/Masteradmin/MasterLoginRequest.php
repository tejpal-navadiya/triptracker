<?php

namespace App\Http\Requests\Auth\Masteradmin;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\MasterUser;
use App\Models\MasterUserDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;


class MasterLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_email' => ['required', 'string', 'email'], // corrected rule
            'user_password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
    
        $credentials = $this->only('user_email', 'user_password','user_id');

        $validator = \Validator::make($credentials, [
            'user_email' => ['required', 'email'],
            'user_password' => ['required', 'string'],
            'user_id' => ['required', 'string'], // Adjust rules as needed
        ], [
            'user_email.required' => 'The email field is required.',
            'user_email.email' => 'The email must be a valid email address.',
            'user_password.required' => 'The password field is required.',
            'user_id.required' => 'The user ID field is required.',
        ]);
    
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = MasterUser::where('buss_unique_id', $credentials['user_id'])
        ->first();

        if (!$user) {
            // No user found, send an error message
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'user_id' => 'User not found or credentials are incorrect.',
                
            ]);

        }

        $userDetails = new MasterUserDetails();
        $userDetails->setTableForUniqueId($user->buss_unique_id);

       // $user = $userDetails->where('user_id', $user->buss_unique_id)->get();
        //dD($user);
        
        // Checking if user exists before accessing role_id
        // if ($user->isNotEmpty() && $user->first()->role_id == 0) {
        //     $users = $userDetails->where('users_email', $credentials['user_email'])
        //     ->where('user_id', $credentials['user_id'])
        //     ->first();
        // } else {
        //     $users = $userDetails->where('user_work_email', $credentials['user_email'])
        //     ->where('user_id', $credentials['user_id'])
        //     ->first();
        // }
    
        // if ($user->isNotEmpty()) {
        //     $users = $user->firstWhere('role_id', '0');
            
        //     if ($user) {
        //         $users = $userDetails->where('users_email', $credentials['user_email'])
        //     ->where('user_id', $credentials['user_id'])
        //     ->first();
        //     } else {
        //         $users = $userDetails->where('user_work_email', $credentials['user_email'])
        //             ->where('user_id', $credentials['user_id'])
        //             ->first();
        //     }
        // }
     
        $userWithRoleZero = $userDetails->where('user_id', $user->buss_unique_id)
        ->where('role_id', 0)
        ->where('users_email', $credentials['user_email'])
        ->first();

        if ($userWithRoleZero) {
        // dd('if');
        // If user with role_id == 0 exists, update password using users_email
        $users = $userDetails->where('users_email', $credentials['user_email'])
            ->where('user_id', $credentials['user_id'])
             ->first();
        } else {
        // dd( 'else');
        // If no user with role_id == 0, update password using user_work_email
        $users = $userDetails->where('user_work_email', $credentials['user_email'])
                     ->where('user_id', $credentials['user_id'])
                     ->first();
        }

        //dd($users);
        if (! $users || ! Hash::check($credentials['user_password'], $users->users_password)) {
            RateLimiter::hit($this->throttleKey());
    
            throw ValidationException::withMessages([
                'user_email' => trans('auth.failed'),
                'user_id' => trans('auth.failed'),
                
            ]);

        }
      
        // Log the user in with the 'masteradmins' guard
        Auth::guard('masteradmins')->login($users, $this->boolean('user_remember'));

        Auth::guard('masteradmins')->setUser($users);

       $users =  Auth::guard('masteradmins')->user();
        //dd($users);

        Cache::put('masteradmins_user_' . Auth::guard('masteradmins')->user()->users_id, Auth::guard('masteradmins')->user(), now()->addMinutes(30));
        
        session(['user_details' => $users]);

       

        $msg=$users->users_first_name.$users->users_last_name." is Logged in";
        \MasterLogActivity::addToLog($msg);
        
        if ($this->boolean('user_remember')) {
            
            Cookie::queue(Cookie::make('user_id', $this->input('user_id'), 60 * 24 * 30)); // 30 days
            Cookie::queue(Cookie::make('user_email', $this->input('user_email'), 60 * 24 * 30)); // 30 days
            Cookie::queue(Cookie::make('user_password', $this->input('user_password'), 60 * 24 * 30)); // 30 days
            Cookie::queue(Cookie::make('user_remember', $this->boolean('user_remember'), 60 * 24 * 30));
        } else {
            Cookie::queue(Cookie::forget('user_id'));
            Cookie::queue(Cookie::forget('user_email'));
            Cookie::queue(Cookie::forget('user_password'));
            Cookie::queue(Cookie::forget('user_remember'));
        }
    
        RateLimiter::clear($this->throttleKey());
    }
    

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'user_email' => trans('auth.throttle', [ // changed to 'user_email'
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('user_email')).'|'.$this->ip()); // changed to 'user_email'
    }
}

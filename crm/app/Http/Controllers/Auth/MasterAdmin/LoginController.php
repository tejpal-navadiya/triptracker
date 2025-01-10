<?php

namespace App\Http\Controllers\Auth\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\Auth\Masteradmin\MasterLoginRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    //

    public function create(): View
    {
        // dd('hiii');
        $rememberedId = Cookie::get('user_id', '');
        $rememberedEmails = Cookie::get('user_email', '');
        $rememberedPasswords = Cookie::get('user_password', '');
        $rememberedRemebers = Cookie::get('user_remember', '');
        // dd($rememberedEmail);
        return view('masteradmin.auth.login',compact('rememberedEmails', 'rememberedPasswords', 'rememberedRemebers','rememberedId'));
    }

    public function store(MasterLoginRequest $request): RedirectResponse
    {
        // dd($request);
        $request->authenticate();
        session()->setId('master_admin_' . session_id());


        $user = Auth::guard('masteradmins')->user();
        $minutes = 30;

        Cache::store('masteradmin_cache')->put('user_id', $user->users_id, $minutes);

        // Cache::store('masteradmin_cache')->put('user_'.$user->users_id, $user->toArray(), $minutes);

        $this->createTable($user->id);

        return redirect()->intended(RouteServiceProvider::MASTER_HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
       
        Auth::guard('masteradmins')->logout();
    
        
        session()->forget('user_configured');
        Cookie::queue(Cookie::forget('user_session'));
        session()->forget('user_id');
       

        $request->session()->forget('masteradmin');
        $response = redirect()->route('masteradmin.login')->with('message', 'Successfully logged out.');
        $response->withCookie(Cookie::forget(env('MASTERADMIN_SESSION_COOKIE')));

        return $response;

    }

}

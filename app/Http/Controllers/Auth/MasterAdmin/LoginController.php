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

        $request->session()->regenerate();

        $user = Auth::guard('masteradmins')->user();

        $this->createTable($user->id);

        return redirect()->intended(RouteServiceProvider::MASTER_HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
       
        Auth::guard('masteradmins')->logout();
    
        // Clear masteradmins-specific cache if needed
        Cache::forget('masteradmins_user_' . Auth::guard('masteradmins')->id());
        
        session()->forget('user_configured');
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
        
        Cookie::queue(Cookie::forget('user_session'));

        return redirect('/agency/login/');
    }

}

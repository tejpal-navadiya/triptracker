<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    
    public function create(): View
    {
        $rememberedEmail = Cookie::get('email', '');
        $rememberedPassword = Cookie::get('password', '');
        $rememberedRemeber = Cookie::get('remember', '');
        // dd($rememberedEmail);
        return view('auth.login',compact('rememberedEmail', 'rememberedPassword','rememberedRemeber'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // dd($request);
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
      
        // Clear web-specific cache if needed
        Cache::forget('web_user_' . Auth::guard('web')->id());


        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return redirect('/admin/login/');
    }
}

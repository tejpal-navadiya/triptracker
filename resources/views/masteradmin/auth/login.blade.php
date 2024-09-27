<x-guest-layout>
    <h5 class="login-box-msg">Login !</h5>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if(Session::has('forgotpassword-success'))
      <p class="text-success" > {{ Session::get('forgotpassword-success') }}</p>
    @endif
    @if(Session::has('forgotpassword-error'))
      <p class="text-danger" > {{ Session::get('forgotpassword-error') }}</p>
    @endif
    <form method="POST" action="{{ route('masteradmin.login.store') }}">
        @csrf

        <div class="input-group mb-2">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-regular fa-user"></span>
                </div>
            </div>
            <x-text-input id="user_id" class="form-control" type="text" name="user_id" :value="old('user_id',$rememberedId)" 
                autofocus autocomplete="user_id" placeholder="User id" />
            
        </div>
        <x-input-error :messages="$errors->get('user_id')" class="mt-1 mb-1" />

        <div class="input-group mb-2">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-regular fa-user"></span>
                </div>
            </div>
            <x-text-input id="user_email" class="form-control" type="email" name="user_email" :value="old('user_email',$rememberedEmails)" 
                autofocus autocomplete="email" placeholder="Email" />
            
        </div>
        <x-input-error :messages="$errors->get('user_email')" class="mb-1 mt-1" />

        <div class="input-group mb-1">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-regular fa-eye"></span>
                </div>
            </div>
            <x-text-input id="user_password" class="form-control" type="password" name="user_password" :value="old('user_password',$rememberedPasswords)" 
                autocomplete="current-password" placeholder="Password" />
            
        </div>
        <x-input-error :messages="$errors->get('user_password')" class="mb-1 mt-1" />
        
        <div class="block mt-2 d-flex justify-content-between">
            <label for="user_remember" class="inline-flex items-center">
                <input id="user_remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="user_remember" {{ $rememberedRemebers == true ? 'checked' : '' }} />
                    <span class="ms-2 font_18">{{ __('Remember me') }}</span>
            </label>
            <p class="mb-0">
                @if (Route::has('masteradmin.password.request'))
                    <a href="{{ route('masteradmin.password.request') }}" class="forgot_text">{{ __('Forgot your password?') }}</a>
                @endif
            </p>
        </div>

        <div class="flex items-center justify-end mt-3">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
            <p class="text-center font_18 mb-0">Don't have an account? <a href="{{ route('masteradmin.register') }}" class="back_text">Register</a></p>
        </div>
    </form>
</x-guest-layout>
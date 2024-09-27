<title>Forgot Password | Profityo</title>
<x-guest-layout>
    <h5 class="login-box-msg">Forgot Password !</h5>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
        <!-- Email Address -->
        <div class="input-group mb-3">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-regular fa-envelope"></span>
            </div>
          </div>
          <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Email"/>
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="input-group mb-3">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-regular fa-eye"></span>
            </div>
          </div>
          <x-text-input id="password" class="form-control" type="password" name="password"  autocomplete="new-password" placeholder="New Password"/>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="input-group mb-3">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-regular fa-eye"></span>
            </div>
          </div>
          <x-text-input id="password_confirmation" class="form-control"
                                type="password"
                                name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password"/>
           <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Reset Password') }}
        </x-primary-button>
    </form>
</x-guest-layout>

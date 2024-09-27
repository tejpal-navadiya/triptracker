<title>Forgot Password | Profityo</title>
<x-guest-layout>
    <h5 class="login-box-msg">Forgot Password !</h5>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" class="mb-0" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group mb-2">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-regular fa-user"></span>
            </div>
          </div>
          <input id="email" class="form-control" type="email" name="email" :value="old('email')" autofocus placeholder="Enter Your Email">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1 mb-1" />

        <button type="submit" class="btn login_btn">Send Password Reset Link</button>
        <p class="text-center font_18 mb-0 mt-2">Back to <a href="{{ route('login') }}" class="back_text">Login</a></p>
    </form>
</x-guest-layout>

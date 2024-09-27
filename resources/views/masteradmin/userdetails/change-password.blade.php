<title>Change Password | Profityo</title>
<x-guest-layout>

    <h5 class="login-box-msg">Change Password !</h5>
    @if(Session::has('link-success'))
      <p class="text-success" > {{ Session::get('link-success') }}</p>
    @endif
    @if(Session::has('link-error'))
      <p class="text-danger" > {{ Session::get('link-error') }}</p>
    @endif
    <form method="POST"
    action="{{ route('business.userdetail.storePassword', ['user_id' => $user_id]) }}">
        @csrf

        <!-- Password Reset Token -->
        
        <div class="input-group mb-3">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-regular fa-envelope"></span>
            </div>
        </div>
        <x-text-input id="user_email" class="form-control" type="email" name="user_email" :value="old('user_email', $email)" required autofocus autocomplete="username" placeholder="Email"/>
        <x-input-error :messages="$errors->get('user_email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="input-group mb-3">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-regular fa-eye"></span>
            </div>
        </div>
        <x-text-input id="user_password" class="form-control" type="password" name="user_password" autocomplete="new-password" placeholder="New Password"/>
        <x-input-error :messages="$errors->get('user_password')" class="mt-2" />
    </div>

    <x-primary-button>
        {{ __('Change Password') }}
    </x-primary-button>
    </form>
</x-guest-layout>

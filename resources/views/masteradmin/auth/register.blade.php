<x-guest-layout>
    <h5 class="login-box-msg">Register !</h5>
    @if(Session::has('link-success'))
      <p class="text-success" > {{ Session::get('link-success') }}</p>
    @endif
    @if(Session::has('link-error'))
      <p class="text-danger" > {{ Session::get('link-error') }}</p>
    @endif
              
    <form method="POST" action="{{ route('masteradmin.register.store') }}">
        @csrf
        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-user"></span>
              </div>
            </div>
            <input type="text" class="form-control @error('user_first_name') is-invalid @enderror"
                id="planname" name="user_first_name" placeholder="Enter Full Name"
                value="{{ old('user_first_name') }}">
          </div>

            @error('user_first_name')
                <div class="invalid-feedback mb-2">{{ $message }}</div>
            @enderror

          </div>

        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-envelope"></span>
              </div>
            </div>
            <input type="email" class="form-control @error('user_email') is-invalid @enderror"
                id="planname" name="user_email" placeholder="Enter Email"
                value="{{ old('user_email') }}">
          </div>
          @error('user_email')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-user"></span>
              </div>
            </div>
            <input type="text" class="form-control @error('user_business_name') is-invalid @enderror"
                id="planname" name="user_business_name" placeholder="Enter Business Name"
                value="{{ old('user_business_name') }}">
          </div>
          @error('user_business_name')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-phone"></span>
              </div>
            </div>
            <input type="text" class="form-control @error('user_phone') is-invalid @enderror"
                id="planname" name="user_phone" placeholder="Enter Phone"
                value="{{ old('user_phone') }}">
          </div>
          @error('user_phone')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-eye"></span>
              </div>
            </div>

            <input type="password" class="form-control @error('user_password') is-invalid @enderror"
                id="user_password" name="user_password" placeholder="Enter Password"
                value="{{ old('user_password') }}">
          </div>

          @error('user_password')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <div class="input-group mb-3">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-eye"></span>
              </div>
            </div>

            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                id="password_confirmation" name="password_confirmation" placeholder="Enter confirm Password"
                value="{{ old('password_confirmation') }}">
          </div>

          @error('password_confirmation')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <x-primary-button>
          {{ __('Register') }}
        </x-primary-button>
        <p class="text-center font_18 mb-0">Already' Have An Account? <a href="{{ route('masteradmin.login') }}" class="back_text">Login</a></p>
    </form>

</x-guest-layout>


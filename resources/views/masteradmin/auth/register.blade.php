<x-guest-layout>
    <h5 class="login-box-msg">Register !</h5>
    @if(Session::has('link-success'))
      <p class="text-success" > {{ Session::get('link-success') }}</p>
    @endif
    @if(Session::has('link-error'))
      <p class="text-danger" > {{ Session::get('link-error') }}</p>
    @endif
              
    <form method="POST" action="{{ route('masteradmin.register.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
          <div class="input-group mb-2">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-regular fa-user"></span>
              </div>
            </div>
            <input type="text" class="form-control @error('user_agencies_name') is-invalid @enderror"
                id="user_agencies_name" name="user_agencies_name" placeholder="Enter Agencies Name *"
                value="{{ old('user_agencies_name') }}">
          </div>

            @error('user_agencies_name')
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
            <input type="text" class="form-control @error('user_franchise_name') is-invalid @enderror"
                id="user_franchise_name" name="user_franchise_name" placeholder="Enter Host of Franchise Name"
                value="{{ old('user_franchise_name') }}">
          </div>

            @error('user_franchise_name')
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
            <input type="text" class="form-control @error('user_consortia_name') is-invalid @enderror"
                id="user_consortia_name" name="user_consortia_name" placeholder="Enter Consortia Name"
                value="{{ old('user_consortia_name') }}">
          </div>

            @error('user_consortia_name')
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
            <input type="text" class="form-control @error('user_first_name') is-invalid @enderror"
                id="user_first_name" name="user_first_name" placeholder="Enter First Name*"
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
                <span class="fas fa-regular fa-user"></span>
              </div>
            </div>
            <input type="text" class="form-control @error('user_last_name') is-invalid @enderror"
                id="user_last_name" name="user_last_name" placeholder="Enter Last Name"
                value="{{ old('user_last_name') }}">
          </div>

            @error('user_last_name')
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
                id="user_email" name="user_email" placeholder="Email Address *"
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
            <input type="number" class="form-control @error('user_iata_clia_number') is-invalid @enderror"
                id="user_iata_clia_number" name="user_iata_clia_number" placeholder="Enter Personal CLIA Number"
                value="{{ old('user_iata_clia_number') }}">
          </div>
          @error('user_iata_clia_number')
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
            <input type="number" class="form-control @error('user_clia_number') is-invalid @enderror"
                id="user_clia_number" name="user_clia_number" placeholder="Enter Personal CLIA Number"
                value="{{ old('user_clia_number') }}">
          </div>
          @error('user_clia_number')
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
            <input type="number" class="form-control @error('user_iata_number') is-invalid @enderror"
                id="user_iata_number" name="user_iata_number" placeholder="Enter Personal IATA Number"
                value="{{ old('user_iata_number') }}">
          </div>
          @error('user_iata_number')
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
            <input type="text" class="form-control @error('user_address') is-invalid @enderror"
                id="user_address" name="user_address" placeholder="Enter Address*"
                value="{{ old('user_address') }}">
          </div>
          @error('user_address')
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
            <input type="text" class="form-control @error('user_city') is-invalid @enderror"
                id="user_city" name="user_city" placeholder="Enter City"
                value="{{ old('user_city') }}">
          </div>
          @error('user_city')
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
              <select id="user_state" name="user_state" class="form-control select2" style="width: 100%;">
                  <option>Select State</option>
                  @foreach($states as $value)
                  <option value="{{ $value->id }}" {{ $value->id == old('user_state') ? 'selected' : '' }}>
                      {{ $value->name }}
                  </option>
                  @endforeach
              </select>
          </div>
          @error('user_state')
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
            <input type="number" class="form-control @error('user_zip') is-invalid @enderror"
                id="user_zip" name="user_zip" placeholder="Enter Zip"
                value="{{ old('user_zip') }}">
          </div>
          @error('user_zip')
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
              <select id="sp_id" name="sp_id" class="form-control select2" style="width: 100%;">
                  <option>Select Subscription Plan</option>
                  @foreach($plan as $value)
                  <option value="{{ $value->sp_id }}" {{ $value->sp_id == old('sp_id') ? 'selected' : '' }}>
                      {{ $value->sp_name }}
                  </option>
                  @endforeach
              </select>
          </div>
          @error('sp_id')
              <div class="invalid-feedback mb-2">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <div class="input-group">
              <input type="file" name="image" accept="image/*" class="">
              <span>Please upload a valid image file. Size of image should not be more than 2MB.</span>
          </div>
          @error('image')
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


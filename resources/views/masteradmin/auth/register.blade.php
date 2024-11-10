<x-guest-layout>
    <h5 class="login-box-msg">Register !</h5>
    @if (Session::has('link-success'))
        <p class="text-success"> {{ Session::get('link-success') }}</p>
    @endif
    @if (Session::has('link-error'))
        <p class="text-danger"> {{ Session::get('link-error') }}</p>
    @endif

    <form method="POST" action="{{ route('masteradmin.register.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Agency Name</label>
                <span class="text-danger">*</span>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Host Of Franchise Name</label>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Consortia Name</label>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">First Name</label>
                <span class="text-danger">*</span>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Last Name</label>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Email Address</label>
                <span class="text-danger">*</span>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-envelope"></span>
                        </div>
                    </div>
                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" id="user_email"
                        name="user_email" placeholder="Email Address *" value="{{ old('user_email') }}">
                </div>
                @error('user_email')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>



            <div class="col-md-6 col-xl-4">
                <label class="form-label">Select IATA or CLIA Number</label>
                <span class="text-danger">*</span>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_iata_clia_number" id="clia_iata_option" value="IATA or CLIA Number" >
                    <label class="form-check-label" for="clia_iata_option">
                        IATA or CLIA Number
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_iata_clia_number" id="iata_option" value="Personal IATA Number" >
                    <label class="form-check-label" for="iata_option">
                        Personal IATA Number
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="user_iata_clia_number" id="clia_option" value="Personal CLIA Number">
                    <label class="form-check-label" for="clia_option">
                        Personal CLIA Number
                    </label>
                </div>
               
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Personal Number</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-phone"></span>
                        </div>
                    </div>
                    <input type="number" class="form-control @error('user_iata_number') is-invalid @enderror"
                        id="user_iata_number" name="user_iata_number" placeholder="Enter Personal Number"
                        value="{{ old('user_iata_number') }}">
                </div>
                @error('user_iata_number')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Address</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-map-marker-alt"></span>

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


            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Country</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-globe"></span> <!-- Icon for Country -->
                        </div>
                    </div>
                    <select id="tr_country" name="user_country" class="form-control" style="width: 100%;">
                        <option>Select Country</option>
                        @foreach ($country as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('user_state')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">State</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-flag"></span>
                        </div>
                    </div>
                    <select id="tr_state" name="user_state" class="form-control" style="width: 100%;">
                        <option>Select State</option>
                    </select>
                </div>
                @error('user_state')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">City</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-city"></span>
                        </div>
                    </div>
                    <select class="form-control form-control select2" id="lib_city" name="user_city" autofocus>
                        <option value="" selected>Select City</option>
                        <!-- Cities will be populated here based on the selected state -->
                    </select>
                </div>
                @error('user_city')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Zip</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-phone"></span>
                        </div>
                    </div>
                    <input type="number" class="form-control @error('user_zip') is-invalid @enderror"
                        id="user_zip" name="user_zip" placeholder="Enter Zip" value="{{ old('user_zip') }}">
                </div>
                @error('user_zip')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Subscription Plan</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-phone"></span>
                        </div>
                    </div>
                    <select id="sp_id" name="sp_id" class="form-control select2" style="width: 100%;">
                        <option>Select Subscription Plan</option>
                        @foreach ($plan as $value)
                            <option value="{{ $value->sp_id }}"
                                {{ $value->sp_id == old('sp_id') ? 'selected' : '' }}>
                                {{ $value->sp_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('sp_id')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Agency Logo / Profile Image</label>
                <div class="input-group">
                    <input type="file" name="image" accept="image/*" class="form-control form-control-file">
                </div>
                <span style=" font-size: 12px; display: block; line-height: 15px; margin: -5px 0 10px 0;">Please upload
                    a valid image file. Size of image should not be more than 2MB.</span>
                @error('image')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Password</label>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Confirm Password</label>
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
        </div>
        <x-primary-button>
            {{ __('Register') }}
        </x-primary-button>
        <p class="text-center mb-0">Already' Have An Account? <a href="{{ route('masteradmin.login') }}"
                class="back_text">Login</a></p>

    </form>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            // Handle change event for the country dropdown
            $('#tr_country').change(function() {
                var countryId = $(this).val();

                if (countryId) {
                    $.ajax({
                        url: '{{ route('authregisterStates', ':countryId') }}'.replace(
                            ':countryId',
                            countryId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Clear the existing state options
                            $('#tr_state').empty();
                            $('#tr_state').append(
                                '<option value="">Select a State...</option>');

                            // Populate the state dropdown with new options
                            $.each(data, function(key, value) {
                                $('#tr_state').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error fetching states: ' + textStatus);
                        }
                    });
                } else {
                    // Reset the state dropdown if no country is selected
                    $('#tr_state').empty();
                    $('#tr_state').append('<option value="">Select a State...</option>');
                }
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#tr_state').change(function() {
                var stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: '{{ route('authregisterCities', ':stateId') }}'.replace(':stateId',
                            stateId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Clear the existing city options
                            $('#lib_city').empty();
                            $('#lib_city').append('<option value="">Select a City...</option>');

                            // Populate the city dropdown with new options
                            $.each(data, function(key, value) {
                                $('#lib_city').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error fetching cities: ' + textStatus);
                        }
                    });
                } else {
                    // Reset the city dropdown if no state is selected
                    $('#lib_city').empty();
                    $('#lib_city').append('<option value="">Select a City...</option>');
                }
            });
        });
    </script>



</x-guest-layout>
<style type="text/css">
    .login-page,
    .register-page {
        background-position: left -220px top 0;
    }

    button.btn.login_btn {
        max-width: 250px;
        margin: 0 auto 10px;
        display: block;
    }

    .login-box {
        margin-left: 43%;
        max-width: 810px;
    }

    @media (orientation: portrait) {
        .login-box {
            margin-left: 0;
            max-width: 600px;
            width: 100%;
        }
    }

    @media screen and (max-width: 767px) {
        .login-box {
            max-width: 340px;
        }

        .login-box .col-md-6.col-xl-4 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .login-page,
        .register-page {
            background-position: 0;
            height: 100%;
            padding: 0 0 20px;
        }

        .login-logo img.brand-image {
            max-width: 150px;
        }
    }
</style>

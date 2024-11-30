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
        <input type="hidden" name="plan_id" id="plan_id" value="{{ request()->query('plan_id') ?? '' }}">      
        <input type="hidden" name="period" id="period" value="{{ request()->query('period') ?? '' }}">       

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
                <label for="user_agencies_name" class="form-label">Business Email Address</label>
                <span class="text-danger">*</span>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-envelope"></span>
                        </div>
                    </div>
                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" id="user_email"
                        name="user_email" placeholder="Business Email Address *" value="{{ old('user_email') }}">
                </div>
                @error('user_email')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_personal_email" class="form-label">Personal Email Address</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-envelope"></span>
                        </div>
                    </div>
                    <input type="email" class="form-control @error('user_personal_email') is-invalid @enderror" id="user_personal_email"
                        name="user_personal_email" placeholder="Personal Email Address" value="{{ old('user_personal_email') }}">
                </div>
                @error('user_personal_email')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_business_phone" class="form-label">Business Phone</label>
                <span class="text-danger">*</span>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-envelope"></span>
                        </div>
                    </div>
                    <input type="number" class="form-control @error('user_business_phone') is-invalid @enderror" id="user_business_phone"
                        name="user_business_phone" placeholder="Business Phone" value="{{ old('user_business_phone') }}">
                </div>
                @error('user_business_phone')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-6 col-xl-4">
                <label for="user_personal_phone" class="form-label">Personal Phone</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-regular fa-envelope"></span>
                        </div>
                    </div>
                    <input type="number" class="form-control @error('user_personal_phone') is-invalid @enderror" id="user_personal_phone"
                        name="user_personal_phone" placeholder="Personal Phone" value="{{ old('user_personal_phone') }}">
                </div>
                @error('user_personal_phone')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-6 col-xl-4">
                <label for="user_iata_clia_number" class="form-label">IATA or CLIA Number</label>
                <span class="text-danger">*</span>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-solid fa-info"></span>
                        </div>
                    </div>
                    <input type="number" class="form-control @error('user_iata_clia_number') is-invalid @enderror"
                        id="user_iata_clia_number" name="user_iata_clia_number" placeholder="Enter IATA or CLIA Number"
                        value="{{ old('user_iata_clia_number') }}">
                </div>
                @error('user_iata_clia_number')
                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 col-xl-4">
                <label for="user_clia_number" class="form-label">Personal CLIA Number</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-solid fa-info"></span>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_iata_number" class="form-label">Personal IATA Number</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-solid fa-info"></span>
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

            <div class="col-md-6 col-xl-4">
                <label for="user_agencies_name" class="form-label">Address</label>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-map-marker-alt"></span>

                        </div>
                    </div>
                    <input type="text" class="form-control @error('user_address') is-invalid @enderror"
                        id="user_address" name="user_address" placeholder="Enter Address"
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
                        <option value="">Select Country</option>
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
                        <option value="">Select State</option>
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
                            <span class="fas fa-map-pin"></span>
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
                <label for="user_agencies_name" class="form-label">Agency Logo</label>
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
                <span class="text-danger">*</span>
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
                <span class="text-danger">*</span>
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

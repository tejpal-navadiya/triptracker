<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Agency | Trip Tracker</title>
    @include('layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0">Add Agency</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Add Agency</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('businessdetails.index') }}" class="add_btn_br px-10">Cancel</a>
                            <button type="submit" form="bdetails" class="add_btn px-10">Save</button>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Add Agency</h3>
                        </div>
                        
                        <!-- /.card-header -->
                        <form id="bdetails" method="POST" action="{{ route('businessdetails.agencyinsert') }}"  enctype="multipart/form-data">
                            @csrf

<!--@if($errors->any())-->
<!--    <div class="alert alert-danger">-->
<!--        <ul>-->
<!--            @foreach ($errors->all() as $error)-->
<!--                <li>{{ $error }}</li>-->
<!--            @endforeach-->
<!--        </ul>-->
<!--    </div>-->
<!--@endif-->


                            <div class="card-body2">
                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Agency Name</label>
                                            <x-text-input type="text" class="form-control" id="user_agencies_name"
                                                placeholder="Enter agency Name" name="user_agencies_name" autofocus
                                                autocomplete="user_agencies_name"
                                                  />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_agencies_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_name" :value="__('Host Of Franchise Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="user_franchise_name"
                                                placeholder="Enter Franchise Name" name="user_franchise_name" autofocus
                                                autocomplete="user_franchise_name"
                                                />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_franchise_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_name" :value="__('Consortia Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="user_consortia_name"
                                                placeholder="Enter Consortia Name" name="user_consortia_name" autofocus
                                                autocomplete="user_consortia_name"
                                                 />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_consortia_name')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">First Name<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="user_first_name"
                                                placeholder="Enter Name" name="user_first_name" autofocus
                                                autocomplete="user_first_name"  />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_first_name')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Last Name<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="user_last_name"
                                                placeholder="Enter Name" name="user_last_name" autofocus
                                                autocomplete="user_last_name"  />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_last_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Business Email Address<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="user_email"
                                                placeholder="Enter Business Email Address" name="user_email" autofocus
                                                autocomplete="user_email"  />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_email')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Personal Email Address</label>
                                            <x-text-input type="email" class="form-control" id="user_personal_email"
                                                placeholder="Enter Personal Email Address" name="user_personal_email" autofocus
                                                autocomplete="user_personal_email"  />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_personal_email')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Business Phone<span class="text-danger">*</span></label>
                                            <x-text-input type="number" class="form-control" id="user_business_phone"
                                                placeholder="Enter Business Phone" name="user_business_phone" autofocus
                                                autocomplete="user_business_phone"  />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_business_phone')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_personal_phone">Personal Phone</label>
                                            <x-text-input type="number" class="form-control" id="user_personal_phone"
                                                placeholder="Enter Personal Phone" name="user_personal_phone" autofocus
                                                autocomplete="user_personal_phone"  />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_personal_phone')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <label for="user_iata_clia_number" class="form-label">IATA or CLIA Number</label>
                                    <span class="text-danger">*</span>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-regular fa-envelope"></span>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control "
                                            id="user_iata_clia_number" name="user_iata_clia_number" placeholder="Enter IATA or CLIA Number"
                                            >
                                    </div>
                                   <x-input-error class="mt-2" :messages="$errors->get('user_iata_clia_number')" />
                                </div>

                                <div class="col-md-4">
                                    <label for="user_clia_number" class="form-label">Personal CLIA Number</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-regular fa-envelope"></span>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control "
                                        id="user_clia_number" name="user_clia_number" placeholder="Enter Personal CLIA Number"
                                         >
                                    </div>
                                    @error('users_clia_number')
                                        <div class="invalid-feedback mb-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="user_iata_number" class="form-label">Personal IATA Number</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-regular fa-envelope"></span>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control"
                                            id="user_iata_number" name="user_iata_number" placeholder="Enter Personal IATA Number"
                                             >
                                    </div>
                                    @error('users_iata_number')
                                        <div class="invalid-feedback mb-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-input-label for="user_address" :value="__('Address')" />

                                            <x-text-input type="text" min="0" class="form-control"
                                                id="user_address" placeholder="Enter Address" name="user_address"
                                                autofocus autocomplete="Address"  />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_address')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="user_country">Country<span class="text-danger"></span></label>
                                        <div class="mb-2 form-group">
                                            <select id="tr_country" name="user_country" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="">Select Country</option>
                                                <!-- Changed for better usability -->

                                                @foreach ($country as $value)
                                                    <option value="{{ $value->id }}" >
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('user_country')
                                            <!-- Change this to user_country -->
                                            <div class="invalid-feedback mb-2">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 col-xl-4">
                                        <label for="user_state">State<span class="text-danger"></span></label>
                                        <div class="input-group mb-2">
                                            <select id="tr_state" name="user_state" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="" selected>Select State</option>

                                             
                                            </select>
                                        </div>
                                        @error('user_state')
                                            <div class="invalid-feedback mb-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <label for="user_city">City<span class="text-danger"></span></label>
                                        <div class="input-group mb-2">
                                            <select class="form-control form-control select2" id="lib_city"
                                                name="user_city" autofocus>
                                                <option value="" selected>Select City</option>

                                               

                                            </select>
                                        </div>
                                        @error('user_city')
                                            <div class="invalid-feedback mb-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 col-xl-4">
                                        <label for="user_zip">Zip<span class="text-danger"></span></label>

                                        <div class="input-group mb-2">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-regular fa-phone"></span>
                                                </div>
                                            </div>
                                            <input type="number"
                                                class="form-control "
                                                id="user_zip" name="user_zip" placeholder="Enter Zip"
                                                >
                                        </div>
                                        @error('user_zip')
                                            <div class="invalid-feedback mb-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Agency Logo </label>
                                                <input class="form-control" name="agency_logo" type="file"
                                                    id="formFile">
                                            </div>
                                            <label for="trvd_document">Only jpg, jpeg, png, and pdf files are
                                                allowed</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-xl-4">
                                        <label for="sp_id" class="form-label">Subscription Plan</label>
                                        <span class="text-danger">*</span>
                                        <div class="input-group mb-2">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-regular fa-eye"></span>
                                                </div>
                                            </div>
                        
                                            <select id="sp_id" name="sp_id" class="form-control select2" style="width: 100%;">
                                                <option value=''>Select Subscription Plan</option>
                                                @foreach ($plan as $value)
                                                    <option value="{{ $value->sp_id }}" {{ $value->sp_id == old('sp_id') ? 'selected' : '' }}>
                                                        {{ $value->sp_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('sp_id')" />
                                        
                                    </div>
                                    
                                    <div class="col-md-6 col-xl-4">
                                        <label for="plan_type" class="form-label">Choose a plan type</label>
                                        <span class="text-danger">*</span>
                                        <div class="input-group mb-2">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-regular fa-eye"></span>
                                                </div>
                                            </div>
                                            <select id="plan_type" name="plan_type" class="form-control select2" style="width: 100%;">
                                                <option value=''>Select Subscription Plan Type</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </div>
                                       <x-input-error class="mt-2" :messages="$errors->get('plan_type')" />

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
                        
                                            <input type="password" class="form-control"
                                                id="user_password" name="user_password" placeholder="Enter Password"
                                                value="{{ old('user_password') }}">
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('user_password')" />
                                      
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
                        
                                            <input type="password" class="form-control"
                                                id="password_confirmation" name="password_confirmation" placeholder="Enter confirm Password"
                                                value="{{ old('password_confirmation') }}">
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                                       
                                    </div>

                                    <div class="col-md-12 text-center py-20">
                                        <a href="{{ route('businessdetails.index') }}"
                                            class="add_btn_br px-10">Cancel</a>
                                        <button type="submit" class="add_btn px-10">Save</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    @include('layouts.footerlink')

</body>

</html>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle change event for the country dropdown
        $('#tr_country').change(function() {
            var countryId = $(this).val();

            if (countryId) {
                $.ajax({
                    url: '{{ route('get_admin_States', ':countryId') }}'.replace(':countryId',
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
                    url: '{{ route('get_admin_Cities', ':stateId') }}'.replace(':stateId',
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

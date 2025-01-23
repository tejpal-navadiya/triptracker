<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Agency User | Trip Tracker</title>
    @include('layouts.headerlink')
    <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

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
                            <h1 class="m-0">Add Agency User</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Add Agency User</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('businessdetails.show', $user_id) }}" class="add_btn_br px-10">Cancel</a>
                            <button type="submit" form="agencyForm" class="add_btn px-10">Save</button>
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
                            <h3 class="card-title">Add Agency User</h3>
                        </div>
                        <!-- /.card-header -->
                        <form id="agencyForm" method="POST" action="{{ route('businessdetails.agencystore', $user_id) }}"
                            enctype="multipart/form-data">
                            @csrf
<!--                            @if($errors->any())-->
<!--    <div class="alert alert-danger">-->
<!--        <ul>-->
<!--            @foreach ($errors->all() as $error)-->
<!--                <li>{{ $error }}</li>-->
<!--            @endforeach-->
<!--        </ul>-->
<!--    </div>-->
<!--@endif-->


                            <div class="card-body2">

                                {{-- First Row --}}
                              
                                <div class="row pxy-15 px-10">

                                   <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_agency_numbers">Agency ID Number <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="user_agency_numbers"
                                                placeholder="Enter Agency ID Number" name="user_agency_numbers" autofocus
                                                autocomplete="user_agency_numbers" value="{{ $nextAgencyNumber }}" readonly/>
                                    
                                            <x-input-error class="mt-2" :messages="$errors->get('user_agency_numbers')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="users_first_name">First Name <span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="users_first_name"
                                                placeholder="Enter First Name" name="users_first_name" autofocus
                                                autocomplete="users_first_name" value="{{ old('users_first_name') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('users_first_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="users_last_name">Last Name <span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="users_last_name"
                                                placeholder="Enter Last Name" name="users_last_name" autofocus
                                                autocomplete="users_last_name" value="{{ old('users_last_name') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_last_name')" />
                                        </div>
                                    </div>
                                </div>



                                <div class="row pxy-15 px-10">
                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_work_email">Business Email Address <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="user_work_email"
                                                placeholder="Enter Business Email Address" name="user_work_email" autofocus
                                                autocomplete="user_work_email" value="{{ old('user_work_email') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_work_email')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="users_email">Personal Email Address</label>
                                            <x-text-input type="email" class="form-control" id="users_email"
                                                placeholder="Enter Personal Email Address" name="users_email" autofocus
                                                autocomplete="users_email" value="{{ old('users_email') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_email')" />
                                        </div>
                                    </div>

                                </div>


                                <div class="row pxy-15 px-10">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_start_date" :value="__('Birthday')" />
                                            <div class="input-group date" id="tr_start_date" data-target-input="nearest">
                                                <x-flatpickr id="completed_date" name="user_dob" placeholder="mm/dd/yyyy" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="completed-date-icon">
                                                        <i class="fa fa-calendar-alt"></i>
                                                        <input type="hidden" id="tr_start_date_hidden"
                                                            value="{{ old('user_dob') }}" />
                                                    </div>

                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="role_id">User Role<span class="text-danger">*</span></label>
                                            <select class="form-control" id="role_id" name="role_id" autofocus>
                                                <option disabled {{ old('role_id') ? '' : 'selected' }}>Select Role
                                                </option>
                                                @foreach ($users_role as $user)
                                                    <option value="{{ $user->role_id }}"
                                                        {{ old('role_id') == $user->role_id ? 'selected' : '' }}>
                                                        {{ $user->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="users_password">Password
                                            <span class="text-danger">*</span></label>
                                            
                                            <x-text-input type="password" class="form-control" id="users_password"
                                                placeholder="Enter Password" name="users_password" autofocus
                                                autocomplete="users_password" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_password')" />
                                        </div>
                                    </div>



                                </div>



                                <div class="row pxy-15 px-10">
                                    <div class="col">
                                        <div class="form-group">

                                           
                                            <button type="button" id="add"
                                                class="add_tripmembertbtn btn btn-primary"><i
                                                    class="fas fa-plus add_plus_icon"></i>Add Phone Number</button>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12" id="dynamic_field">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="user_emergency_contact_person" :value="__('Emergency Contact Person')"> </x-input-label>
                                        <x-text-input type="text" class="form-control" id="user_emergency_contact_person"
                                            placeholder="Enter Emergency Contact Person" name="user_emergency_contact_person"
                                            autofocus autocomplete="user_emergency_contact_person"
                                            value="{{ old('user_emergency_contact_person') }}" />

                                        
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="user_emergency_phone_number" :value="__('Emergency Phone Number')"> </x-input-label>
                                        <x-text-input type="text" class="form-control" id="user_emergency_phone_number"
                                            placeholder="Enter Emergency Phone Number" name="user_emergency_phone_number"
                                            autofocus autocomplete="user_emergency_phone_number"
                                            value="{{ old('user_emergency_phone_number') }}" />

                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="user_emergency_email" :value="__('Emergency Email Address')"></x-input-label>
                                        <x-text-input type="email" class="form-control" id="user_emergency_email"
                                            placeholder="Enter Emergency Email Address" name="user_emergency_email"
                                            autofocus autocomplete="user_emergency_email"
                                            value="{{ old('user_emergency_email') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('user_emergency_email')" />
                                      


                                    </div>
                                </div>

                            </div>

                            {{-- End Fourths Row --}}


                            {{-- Fourth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-input-label for="users_address" :value="__('Address')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="users_address"
                                            placeholder="Enter Address" name="users_address" autofocus
                                            autocomplete="users_address" value="{{ old('users_address') }}" />

                                    </div>
                                </div>

                            </div>

                            {{-- End Fourths Row --}}


                            {{-- Fifth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="users_country" :value="__('Country')"> </x-input-label>

                                        <select id="tr_country" name="users_country" class="form-control select2"
                                            style="width: 100%;">
                                            <option value="0">Select Country</option>
                                            @foreach ($country as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('users_country')" />

                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="users_state" :value="__('State')"> </x-input-label>
                                        <select id="tr_state" name="users_state" class="form-control select2"
                                            style="width: 100%;">
                                            <option value="0">Select State</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="users_city" :value="__('City')"> </x-input-label>

                                        <select class="form-control form-control select2" id="lib_city"
                                            name="users_city" autofocus>
                                            <option value="0" selected>Select City</option>
                                            <!-- Cities will be populated here based on the selected state -->
                                        </select>

                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="users_zip" :value="__('Zip')"> </x-input-label>
                                        <x-text-input type="number" class="form-control" id="users_zip"
                                            placeholder="Enter Zip" name="users_zip" autofocus autocomplete="users_zip"
                                            value="{{ old('users_zip') }}" />
                                      
                                    </div>
                                </div>

                            </div>

                            {{-- End Fifth Row --}}

                            <div class="row py-20 px-10">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('businessdetails.show', $user_id) }}" class="add_btn_br px-10">Cancel</a>
                                    <button id="submitButton" type="submit" class="add_btn px-10">Submit</button>
                                </div>
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
    
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

        <script>
            document.getElementById('agencyForm').addEventListener('submit', function(e) {
                const submitButton = document.getElementById('submitButton');
                if (submitButton.disabled) {
                    // Prevent further form submission attempts
                    e.preventDefault();
                    return false;
                }

                // Disable the submit button to prevent multiple submissions
                submitButton.disabled = true;

                // Optionally show some feedback, like changing button text
                submitButton.innerText = 'Submitting...';
            });
        </script>



        <script>
            document.addEventListener('DOMContentLoaded', function() {

                var fromdatepicker = flatpickr("#completed_date", {
                    locale: 'en',
                    altInput: true,
                    dateFormat: "m/d/Y",
                    altFormat: "m/d/Y",
                    allowInput: true,
                });



                document.getElementById('completed-date-icon').addEventListener('click', function() {
                    fromdatepicker.open();
                });

            });
        </script>



        <script>
            $(document).ready(function() {

                var rowCount = 0;

                $('#add').click(function() {
                    rowCount++;

                    $('#dynamic_field').append(` 
                        <div class="item-row row" id="row${rowCount}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_agent_id" :value="__('Phone Number')"> 
                                        
                                    </x-input-label>
                                    <x-text-input type="text" class="form-control" id="trtm_first_name${rowCount}"
                                        placeholder="Enter Phone Number" name="items[${rowCount}][age_user_phone_number]" autofocus
                                        autocomplete="tr_agent_id" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="trtm_gender">Type</label>
                                    <div class="d-flex">

                                        <select class="form-control select2" style="width: 100%;" id="trtm_gender${rowCount}" 
                                            name="items[${rowCount}][age_user_type]">
                                          <option>Select</option>

                                          @foreach ($phones_type as $product)
                                              <option value="{{ $product->agent_phone_id }}">{{ $product->type }}</option>
                                           @endforeach

                                        </select>

                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <label for="trtm_gender">&nbsp;</label>
                                <div class="d-flex">

                                <a class="delete_btn delete-item" id="${rowCount}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732ZM6.00065 5.5C6.00065 5.36739 5.94797 5.24022 5.8542 5.14645C5.76044 5.05268 5.63326 5 5.50065 5C5.36804 5 5.24087 5.05268 5.1471 5.14645C5.05333 5.24022 5.00065 5.36739 5.00065 5.5V10.5C5.00065 10.6326 5.05333 10.7598 5.1471 10.8536C5.24087 10.9473 5.36804 11 5.50065 11C5.63326 11 5.76044 10.9473 5.8542 10.8536C5.94797 10.7598 6.00065 10.6326 6.00065 10.5V5.5ZM8.50065 5C8.63326 5 8.76044 5.05268 8.8542 5.14645C8.94797 5.24022 9.00065 5.36739 9.00065 5.5V10.5C9.00065 10.6326 8.94797 10.7598 8.8542 10.8536C8.76044 10.9473 8.63326 11 8.50065 11C8.36804 11 8.24087 10.9473 8.1471 10.8536C8.05333 10.7598 8.00065 10.6326 8.00065 10.5V5.5C8.00065 5.36739 8.05333 5.24022 8.1471 5.14645C8.24087 5.05268 8.36804 5 8.50065 5ZM3.48998 11.3113C3.52594 11.6824 3.69881 12.0268 3.9749 12.2774C4.25098 12.528 4.61048 12.6667 4.98332 12.6667H9.01798C9.39082 12.6667 9.75032 12.528 10.0264 12.2774C10.3025 12.0268 10.4754 11.6824 10.5113 11.3113L11.2833 3.33333H2.71798L3.48998 11.3113Z" fill="white"></path>
                                </svg>
                                Remove Phone Number</a>
                            </div>
                            </div>
        
                            <hr />
                        </div>
                    `);

                });

                $(document).on('click', '.delete-item', function() {
                    var rowId = $(this).attr("id");
                    $('#row' + rowId).remove();
                });
            });
        </script>


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


</body>

</html>


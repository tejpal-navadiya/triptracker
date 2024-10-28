@extends('masteradmin.layouts.app')s
<!DOCTYPE html>


<title>Add User | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Agency Users') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Add User') }}</li>
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

                        {{-- @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif --}}


                        <div class="card-header">
                            <h3 class="card-title">Add User</h3>
                        </div>


                        <!-- /.card-header -->
                        <form id="agencyForm" method="POST" action="{{ route('agency.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="card-body2">

                                {{-- First Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Agency ID Number <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Agency ID Number" name="user_agency_numbers" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('user_agency_numbers') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_agency_numbers')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">First Name <span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter First Name" name="users_first_name" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('users_first_name') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('users_first_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Last Name <span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Last Name" name="users_last_name" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('users_last_name') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_last_name')" />
                                        </div>
                                    </div>

                                </div>

                                {{-- End First Row --}}


                                {{-- sec Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Qualification')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Qualification" name="user_qualification" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('user_qualification') }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Work Email Address <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Work Email Address" name="user_work_email" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('user_work_email') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('user_work_email')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Personal Email Address<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Personal Email Address" name="users_email" autofocus
                                                autocomplete="tr_agent_id" value="{{ old('users_email') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_email')" />
                                        </div>
                                    </div>

                                </div>

                                {{-- End sec Row --}}


                                {{-- third Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_start_date" :value="__('Start Date')" />
                                            <div class="input-group date" id="tr_start_date" data-target-input="nearest">
                                                <x-flatpickr id="completed_date" name="user_dob"
                                                    placeholder="mm/dd/yyyy" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="completed-date-icon">
                                                        <i class="fa fa-calendar-alt"></i>
                                                        <input type="hidden" id="tr_start_date_hidden"
                                                            value="{{ old('user_dob') }}" />
                                                    </div>

                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_start_date')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">User Role<span class="text-danger">*</span></label>
                                            <select class="form-control" id="tr_category" name="role_id" autofocus>
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
                                            <x-input-label for="tr_agent_id" :value="__('Password')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="password" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Password" name="users_password" autofocus
                                                autocomplete="tr_agent_id" />

                                            <x-input-error class="mt-2" :messages="$errors->get('users_password')" />
                                        </div>
                                    </div>



                                </div>


                                {{-- Dynamic Input Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />

                                            <button type="button" id="add"
                                                class="add_tripmembertbtn btn btn-primary"><i
                                                    class="fas fa-plus add_plus_icon"></i>Add Phone Number</button>
                                        </div>


                                        <div class="col-md-12" id="dynamic_field">

                                        </div>

                                    </div>
                                </div>

                            </div>

                            {{-- End  Dynamic Input Row --}}


                            {{-- Fourth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Contact Person')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Emergency Contact" name="user_emergency_contact_person"
                                            autofocus autocomplete="tr_agent_id"
                                            value="{{ old('user_emergency_contact_person') }}" />

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Phone Number')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Emergency Phone" name="user_emergency_phone_number"
                                            autofocus autocomplete="tr_agent_id"
                                            value="{{ old('user_emergency_phone_number') }}" />

                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Email Address')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="email" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Emergency Email Address" name="user_emergency_email"
                                            autofocus autocomplete="tr_agent_id"
                                            value="{{ old('user_emergency_email') }}" />

                                    </div>
                                </div>

                            </div>

                            {{-- End Fourths Row --}}


                            {{-- Fourth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Address')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="tr_agent_id"
                                            placeholder="Select Agent" name="users_address" autofocus
                                            autocomplete="tr_agent_id" value="{{ old('users_address') }}" />

                                    </div>
                                </div>

                            </div>

                            {{-- End Fourths Row --}}


                            {{-- Fifth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('country')"> <span
                                                class="text-danger">*</span></x-input-label>

                                        <select id="tr_country" name="users_country" class="form-control select2"
                                            style="width: 100%;">
                                            <option>Select Country</option>
                                            @foreach ($country as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('users_country')" />

                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('state')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <select id="tr_state" name="users_state" class="form-control select2"
                                            style="width: 100%;">
                                            <option>Select State</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('City')"> <span
                                                class="text-danger">*</span></x-input-label>

                                        <select class="form-control form-control select2" id="lib_city"
                                            name="users_city" autofocus>
                                            <option value="" selected>Select City</option>
                                            <!-- Cities will be populated here based on the selected state -->
                                        </select>

                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('zip')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="number" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Zip" name="users_zip" autofocus autocomplete="tr_agent_id"
                                            value="{{ old('users_zip') }}" />
                                        <x-input-error class="mt-2" :messages="$errors->get('users_zip')" />
                                    </div>
                                </div>

                            </div>

                            {{-- End Fifth Row --}}

                            <div class="row py-20 px-10">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('trip.index') }}" class="add_btn_br px-10">Cancel</a>
                                    <button id="submitButton" type="submit" class="add_btn px-10">Save</button>
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
                    altFormat: "d/m/Y",
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="tr_agent_id" :value="__('Phone Number')"> 
                                        <span class="text-danger">*</span>
                                    </x-input-label>
                                    <x-text-input type="text" class="form-control" id="trtm_first_name${rowCount}"
                                        placeholder="Enter Phone Number" name="items[${rowCount}][age_user_phone_number]" autofocus
                                        autocomplete="tr_agent_id" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                </div>
                            </div>
        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trtm_gender">Type<span class="text-danger">*</span></label>
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
        
                            <div class="col-md-6">
                                <i class="fa fa-trash delete-item" id="${rowCount}"> Remove Phone Number</i>
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
                            url: '{{ route('agencyStates', ':countryId') }}'.replace(':countryId',
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
                            url: '{{ route('agencyCities', ':stateId') }}'.replace(':stateId',
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
    @endsection
@endif

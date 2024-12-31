@extends('masteradmin.layouts.app')
<title>Edit Trip | Trip Tracker</title>
@if (isset($access['edit_traveler']) && $access['edit_traveler'])
    @section('content')
        <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Edit Traveler') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Edit Traveler') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('masteradmin.travelers.travelersDetails') }}" class="add_btn_br px-10">Cancel</a>
                            <button type="submit" form="travelerFormedit" class="add_btn px-10">Save</button>
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
                            <h3 class="card-title">Edit Traveler</h3>
                        </div>
                        <!-- /.card-header -->
                        <form id="travelerFormedit" action="{{ route('masteradmin.travelers.update', $trip->trtm_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body2">
                                <div class="row pxy-15 px-10">
                                    <!-- <div class="col-md-4"> -->

                                        <!-- <div class="form-group">
                                            <x-input-label for="tr_name" :value="__('Name of Trip')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_name"
                                                placeholder="Enter Name of Trip" name="tr_name"  autofocus
                                                autocomplete="tr_name" :value="old('tr_name', $trip->tr_name ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                        </div> -->
                                    <!-- </div> -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="trtm_agent_id">Agent Name<span class="text-danger">*</span></label>
                                            <select id="trtm_agent_id" name="trtm_agent_id" class="form-control select2">
                                                <option disabled selected>Select Agent</option>
                                                @foreach ($agency_users as $value)
                                                    <option value="{{ $value->users_id }}"
                                                        {{ old('trtm_agent_id', $trip->trtm_agent_id ?? '') == $value->users_id ? 'selected' : '' }}>
                                                        {{ $value->users_first_name }} {{ $value->users_last_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_agent_id')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="trtm_first_name">Traveler Name<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="trtm_first_name"
                                                placeholder="Traveler Name" name="trtm_first_name" autofocus
                                                autocomplete="trtm_first_name" :value="old('trtm_first_name', $trip->trtm_first_name ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_first_name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_dob" :value="__('Birthdate')" />
                                            <div class="input-group date" id="trtm_dob" data-target-input="nearest">
                                                <x-flatpickr id="birthdate_date" name="trtm_dob" placeholder="mm/dd/yyyy" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="birthdate-hidden-icon">
                                                        <i class="fa fa-calendar-alt"></i>
                                                        <input type="hidden" id="birthdate_hidden"
                                                            value="{{ $trip->trtm_dob }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_dob')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row pxy-15 px-10">
                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_age" :value="__('Age')" />
                                            <x-text-input type="text" class="form-control" id="trtm_age" placeholder="Enter Age"
                                                name="trtm_age" autofocus autocomplete="trtm_age" :value="old('trtm_age', $trip->trtm_age ?? '')" readonly />
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_age')" />
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="trtm_email">Email Address<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="trtm_email"
                                                placeholder="Enter Email Address" name="trtm_email" autofocus
                                                autocomplete="trtm_email" :value="old('trtm_email', $trip->trtm_email ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_number" :value="__('Phone Number')" />
                                            <x-text-input type="number" min="0" class="form-control"
                                                id="trtm_number" placeholder="Enter Phone Number" name="trtm_number" autofocus
                                                autocomplete="trtm_number" :value="old('trtm_number', $trip->trtm_number ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_number')" />
                                        </div>
                                    </div>
                                

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_notes" :value="__('Notes')" />
                                            <textarea type="text" class="form-control" id="trtm_notes" placeholder="Enter Notes" name="trtm_notes"
                                                 autofocus autocomplete="trtm_notes">{{ $trip->trtm_notes }}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_notes')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_address" :value="__('Address')" />
                                            <x-text-input type="text" class="form-control" id="trtm_address"
                                                placeholder="Enter Address" name="trtm_address" autofocus
                                                autocomplete="trtm_address" :value="old('tr_address', $trip->trtm_address ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_address')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Country')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select id="tr_country" name="trtm_country" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select Country</option>
                                                @foreach ($country as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('trtm_country', $trip->trtm_country ?? '') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->name }} ({{ $value->iso2 }})
                                                    </option>
                                                @endforeach

                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('users_state')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('State')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <select id="tr_state" name="trtm_state" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select State</option>
                                                @foreach ($states as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('trtm_state', $trip->trtm_state ?? '') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->name }} ({{ $value->iso2 }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('users_state')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('City')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select class="form-control form-control select2" id="lib_city"
                                                name="trtm_city" autofocus>
                                                <option value="" selected>Select City</option>
                                                @foreach ($city as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('trtm_city', $trip->trtm_city ?? '') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('users_city')" />
                                        </div>
                                    </div>

                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="trtm_zip" :value="__('Zip')" />
                                            <x-text-input type="number" class="form-control" id="trtm_zip"
                                                placeholder="Enter Zip" name="trtm_zip" autofocus
                                                autocomplete="trtm_zip" :value="old('trtm_zip', $trip->trtm_zip ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('trtm_zip')" />
                                        </div>
                                    </div>
                                </div>

                                {{-- end by Rvi --}}

                                <div class="row py-20 px-10">
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('masteradmin.travelers.travelersDetails') }}"
                                            class="add_btn_br px-10">Cancel</a>
                                        <button type="submit" class="add_btn px-10">Save</button>
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
        <script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                var fromdatepicker = document.getElementById('tr_start_date_hidden');
                var todatepicker = document.getElementById('tr_end_date_hidden');

                fromdatepicker = flatpickr("#completed_date", {
                    locale: 'en',
                    altInput: true,
                    dateFormat: "m/d/Y",
                    altFormat: "m/d/Y",
                    allowInput: true,
                    defaultDate: fromdatepicker.value || null,
                });

                todatepicker = flatpickr("#expiration_date", {
                    locale: 'en',
                    altInput: true,
                    dateFormat: "m/d/Y",
                    altFormat: "m/d/Y",
                    allowInput: true,
                    defaultDate: todatepicker.value || null,
                });

                document.getElementById('completed-date-icon').addEventListener('click', function() {
                    fromdatepicker.open();
                });

                document.getElementById('expiration-date-icon').addEventListener('click', function() {
                    todatepicker.open();
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
                            url: '{{ route('getStates', ':countryId') }}'.replace(':countryId',
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
                            url: '{{ route('getRegisterCities', ':stateId') }}'.replace(':stateId',
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
        <script>
        document.addEventListener('DOMContentLoaded', function () {

            var birthdatedate = document.getElementById('birthdate_hidden');
            birthdatedate = flatpickr("#birthdate_date", {
                locale: 'en',
                altInput: true,
                dateFormat: "m/d/Y",
                altFormat: "m/d/Y",
                allowInput: true,
                defaultDate: birthdatedate.value || null,
            });

            document.getElementById('birthdate-hidden-icon').addEventListener('click', function () {
                birthdatedate.open();
            });

            var birthdateInput = document.querySelector('#birthdate_date');
            var ageInput = document.querySelector('#tr_age');

            birthdateInput.addEventListener('change', function () {
                var birthdate = new Date(birthdateInput.value);
                var today = new Date();
                var age = today.getFullYear() - birthdate.getFullYear();
                var m = today.getMonth() - birthdate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }

                if (age < 0) {
                    ageInput.value = 0;
                    // alert("Invalid birthdate. Please enter a valid birthdate.");
                } else {
                    ageInput.value = age;
                }

            });
        });
        </script>
    @endsection
@endif

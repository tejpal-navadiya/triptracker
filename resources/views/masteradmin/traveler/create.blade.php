@extends('masteradmin.layouts.app')
<title>Add Traveler | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Add Traveler') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Add Traveler') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                <a href="#"><button class="add_btn_br">Cancel</button></a>
                                <a href="#"><button class="add_btn">Save</button></a>
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
                            <h3 class="card-title">Add Traveler</h3>
                        </div>
                        <!-- /.card-header -->
                        <form id="travelerForm" method="POST" action="{{ route('masteradmin.travelers.store') }}">
                            @csrf
                            <input type="hidden" value="travelers" name="travelers">
                            <div class="card-body2">
                                <div class="row pxy-15 px-10">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_name">Name of Trip<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_name"
                                                placeholder="Enter Name of Trip" name="tr_name" autofocus
                                                autocomplete="tr_name" value="{{ old('tr_name') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label for="tr_agent_id">Agent Name<span class="text-danger">*</span></label>

                                            <select id="tr_agent_id" name="tr_agent_id" class="form-control select2">
                                                <option disabled selected>Select Agent</option>
                                                @foreach ($agency_user as $value)
                                                    <option value="{{ $value->users_id }}">
                                                        {{ $value->users_first_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label for="tr_agent_id">Traveler Name<span class="text-danger">*</span></label>

                                            <x-text-input type="text" class="form-control" id="tr_traveler_name"
                                                placeholder="Traveler Name" name="tr_traveler_name" autofocus
                                                autocomplete="tr_traveler_name" value="{{ old('tr_traveler_name') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_traveler_name')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row pxy-15 px-10">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Start Date<span class="text-danger">*</span></label>
                                            <div class="input-group date" id="tr_start_date" data-target-input="nearest">
                                                <x-flatpickr id="completed_date" name="tr_start_date"
                                                    placeholder="mm/dd/yyyy" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="completed-date-icon">
                                                        <i class="fa fa-calendar-alt"></i>
                                                        <input type="hidden" id="tr_start_date_hidden" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">End Date<span class="text-danger">*</span></label>
                                            <div class="input-group date" id="tr_end_date" data-target-input="nearest">
                                                <x-flatpickr id="expiration_date" name="tr_end_date"
                                                    placeholder="mm/dd/yyyy" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="expiration-date-icon">
                                                        <i class="fa fa-calendar-alt"></i>
                                                        <input type="hidden" id="tr_end_date_hidden" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_num_people" :value="__('Number of People')" />
                                            <x-text-input type="number" min="0" class="form-control"
                                                id="tr_num_people" placeholder="Enter Number of People"
                                                name="tr_num_people" autofocus autocomplete="tr_num_people"
                                                value="{{ old('tr_num_people') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')"
                                                value="{{ old('tr_num_people') }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label for="tr_email">Email Address<span class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="tr_email"
                                                placeholder="Enter Email Address" name="tr_email" autofocus
                                                autocomplete="tr_email" value="{{ old('tr_email') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Phone Number')" />
                                            <x-text-input type="number" min="0" class="form-control"
                                                id="tr_phone" placeholder="Enter Phone Number" name="tr_phone" autofocus
                                                autocomplete="tr_phone" value="{{ old('tr_phone') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_desc" :value="__('Description')" />
                                            <textarea type="text" class="form-control" id="tr_desc" placeholder="Enter Description" name="tr_desc"
                                                autofocus autocomplete="tr_desc"> {{ old('tr_email') }}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_desc')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Country')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select id="tr_country" name="tr_country" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select Country</option>
                                                @foreach ($country as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_country')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('state')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <select id="tr_state" name="tr_state" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select State</option>
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_state')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('City')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select class="form-control form-control select2" id="lib_city"
                                                name="tr_city" autofocus>
                                                <option value="" selected>Select City</option>
                                                <!-- Cities will be populated here based on the selected state -->
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_city')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Address')" />
                                            <x-text-input type="text" class="form-control" id="tr_phone"
                                                placeholder="Enter Address" name="tr_address" autofocus
                                                autocomplete="tr_phone" value="{{ old('tr_address') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_address')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Zip')" />
                                            <x-text-input type="number" class="form-control" id="tr_phone"
                                                placeholder="Enter Zip Code" name="tr_zip" autofocus
                                                autocomplete="tr_phone" value="{{ old('tr_zip') }}" />
                                            {{-- <x-input-error class="mt-2" :messages="$errors->get('tr_zip')" /> --}}
                                        </div>
                                    </div>

                                    {{-- end by Rvi --}}
                                </div>




                                <div class="row py-20 px-10">
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('masteradmin.travelers.travelersDetails') }}"
                                            class="add_btn_br px-10">Cancel</a>
                                        <button type="submit" id="submitButton" class="add_btn px-10">Save</button>
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
            document.getElementById('travelerForm').addEventListener('submit', function(e) {
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

                var todatepicker = flatpickr("#expiration_date", {
                    locale: 'en',
                    altInput: true,
                    dateFormat: "m/d/Y",
                    altFormat: "d/m/Y",
                    allowInput: true,
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
    @endsection
@endif

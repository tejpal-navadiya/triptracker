@extends('masteradmin.layouts.app')
<title>Edit Trip | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Edit Traveler') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Edit Traveler') }}</li>
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
                            <h3 class="card-title">Edit Traveler</h3>
                        </div>
                        <!-- /.card-header -->
                        <form action="{{ route('masteradmin.travelers.update', $trip->tr_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="travelers" name="travelers">
                            <div class="card-body2">
                                <div class="row pxy-15 px-10">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <x-input-label for="tr_name" :value="__('Name of Trip')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_name"
                                                placeholder="Enter Name of Trip" name="tr_name"  autofocus
                                                autocomplete="tr_name" :value="old('tr_name', $trip->tr_name ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Agent Name<span class="text-danger">*</span></label>
                                            <select id="tr_agent_id" name="tr_agent_id" class="form-control select2">
                                                <option disabled selected>Select Agent</option>
                                                @foreach ($agency_users as $value)
                                                    <option value="{{ $value->users_id }}"
                                                        {{ old('tr_agent_id', $trip->tr_agent_id ?? '') == $value->users_id ? 'selected' : '' }}>
                                                        {{ $value->users_first_name }} {{ $value->users_last_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Traveler Name<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_traveler_name"
                                                placeholder="Traveler Name" name="tr_traveler_name" autofocus
                                                autocomplete="tr_traveler_name" :value="old('tr_traveler_name', $trip->tr_traveler_name ?? '')" />

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
                                                        <input type="hidden" id="tr_start_date_hidden"
                                                            value="{{ $trip->tr_start_date }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_start_date')" />
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
                                                        <input type="hidden" id="tr_end_date_hidden"
                                                            value="{{ $trip->tr_end_date }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_end_date')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Email Address<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="email" class="form-control" id="tr_email"
                                                placeholder="Enter Email Address" name="tr_email" autofocus
                                                autocomplete="tr_email" :value="old('tr_email', $trip->tr_email ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Phone Number')" />
                                            <x-text-input type="number" min="0" class="form-control"
                                                id="tr_phone" placeholder="Enter Phone Number" name="tr_phone" autofocus
                                                autocomplete="tr_phone" :value="old('tr_phone', $trip->tr_phone ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_num_people" :value="__('Number of People')" />
                                            <x-text-input type="number" min="0" class="form-control"
                                                id="tr_num_people" placeholder="Enter Number of People"
                                                name="tr_num_people"  autofocus autocomplete="tr_num_people"
                                                :value="old('tr_num_people', $trip->tr_num_people ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_desc" :value="__('Description')" />
                                            <textarea type="text" class="form-control" id="tr_desc" placeholder="Enter Description" name="tr_desc"
                                                 autofocus autocomplete="tr_desc">{{ $trip->tr_desc }}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_desc')" />
                                        </div>
                                    </div>


                                    {{-- Added By Rvi --}}


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Country')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select id="tr_country" name="tr_country" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select Country</option>
                                                @foreach ($country as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('tr_country', $trip->tr_country ?? '') == $value->id ? 'selected' : '' }}>
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
                                            <select id="tr_state" name="tr_state" class="form-control select2"
                                                style="width: 100%;">
                                                <option>Select State</option>
                                                @foreach ($states as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('tr_state', $trip->tr_state ?? '') == $value->id ? 'selected' : '' }}>
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
                                                name="tr_city" autofocus>
                                                <option value="" selected>Select City</option>
                                                @foreach ($city as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('tr_city', $trip->tr_city ?? '') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('users_city')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Address')" />
                                            <x-text-input type="text" class="form-control" id="tr_phone"
                                                placeholder="Enter Address" name="tr_address" autofocus
                                                autocomplete="tr_address" :value="old('tr_address', $trip->tr_address ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_address')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Zip')" />
                                            <x-text-input type="number" class="form-control" id="tr_phone"
                                                placeholder="Enter Zip" name="tr_zip" autofocus
                                                autocomplete="tr_zip" :value="old('tr_zip', $trip->tr_zip ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_zip')" />
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
                    altFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: fromdatepicker.value || null,
                });

                todatepicker = flatpickr("#expiration_date", {
                    locale: 'en',
                    altInput: true,
                    dateFormat: "m/d/Y",
                    altFormat: "d/m/Y",
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
    @endsection
@endif

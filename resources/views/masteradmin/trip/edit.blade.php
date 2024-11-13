@extends('masteradmin.layouts.app')

<title>Edit Trip | Trip Tracker</title>

@if (isset($access['workflow']) && $access['workflow'])

    @section('content')

        <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">



        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper">

            <!-- Content Header (Page header) -->

            <div class="content-header">

                <div class="container-fluid">

                    <div class="row mb-2 align-items-center justify-content-between">

                        <div class="col-auto">

                            <h1 class="m-0">{{ __('Edit Trip') }}</h1>

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>

                                <li class="breadcrumb-item active">{{ __('Edit Trip') }}</li>

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

                            <h3 class="card-title">Edit Trip</h3>

                        </div>

                        <!-- /.card-header -->

                        <form action="{{ route('trip.update', $trip->tr_id) }}" method="POST">

                            @csrf

                            @method('PUT')

                            <div class="card-body2">

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">



                                        <div class="form-group">

                                            <label for="tr_agent_id">Name of Trip<span class="text-danger">*</span></label>

                                            <x-text-input type="text" class="form-control" id="tr_name"

                                                placeholder="Enter Name of Trip" name="tr_name" autofocus

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

                                                        {{ $value->users_first_name ?? ''}} {{ $value->users_last_name ?? ''}}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                    </div>



                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label for="tr_agent_id">Traveler Name<span class="text-danger">*</span></label>

                                            <x-text-input type="text" class="form-control" id="tr_traveler_name"

                                                placeholder="Traveler Name" name="tr_traveler_name"  autofocus

                                                autocomplete="tr_traveler_name" :value="old('tr_traveler_name', $trip->tr_traveler_name ?? '')" />



                                            <x-input-error class="mt-2" :messages="$errors->get('tr_traveler_name')" />

                                        </div>

                                    </div>

                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_dob" :value="__('Birthdate')" />

                                            <div class="input-group date" id="tr_dob" data-target-input="nearest">



                                                <x-flatpickr id="birthdate_date" name="tr_dob" placeholder="mm/dd/yyyy" />

                                                <div class="input-group-append">

                                                    <div class="input-group-text" id="birthdate-hidden-icon">

                                                        <i class="fa fa-calendar-alt"></i>

                                                        <input type="hidden" id="birthdate_hidden"

                                                            value="{{ $trip->tr_dob }}" />

                                                    </div>

                                                </div>

                                            </div>

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_dob')" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_age" :value="__('Age')" />

                                            <x-text-input type="text" class="form-control" id="tr_age"

                                                placeholder="Enter Age" name="tr_age"  autofocus

                                                autocomplete="tr_age" :value="old('tr_age', $trip->tr_age ?? '')" readonly />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_age')" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_number" :value="__('Trip Number')" />

                                            <x-text-input type="number" class="form-control" id="tr_number"

                                                placeholder="Enter Trip Number" name="tr_number"  autofocus

                                                autocomplete="tr_number" :value="old('tr_number', $trip->tr_number ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_number')" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_email" :value="__('Email Address')" />

                                            <x-text-input type="email" class="form-control" id="tr_email"

                                                placeholder="Enter Email Address" name="tr_email"  autofocus

                                                autocomplete="tr_email" :value="old('tr_email', $trip->tr_email ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_phone" :value="__('Phone Number')" />

                                            <x-text-input type="number" class="form-control" id="tr_phone"

                                                placeholder="Enter Phone Number" name="tr_phone"  autofocus

                                                autocomplete="tr_phone" :value="old('tr_phone', $trip->tr_phone ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_num_people" :value="__('Number of People')" />

                                            <x-text-input type="text" class="form-control" id="tr_num_people"

                                                placeholder="Enter Number of People" name="tr_num_people" 

                                                autofocus autocomplete="tr_num_people" :value="old('tr_num_people', $trip->tr_num_people ?? '')" readonly />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />

                                        </div>

                                    </div>



                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_start_date" :value="__('Start Date')" />

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

                                            <x-input-label for="tr_end_date" :value="__('End Date')" />

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

                                            <x-input-label for="tr_value_trip" :value="__('Value of trip')" />

                                            <x-text-input type="text" class="form-control" id="tr_value_trip"

                                                placeholder="Enter Value of trip" name="tr_value_trip"  autofocus

                                                autocomplete="tr_value_trip" :value="old('tr_value_trip', $trip->tr_value_trip ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_value_trip')" />

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





                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <x-input-label for="tr_desc" :value="__('Status')" />

                                            <select id="status" name="status" class="form-control select2">

                                                <option disabled selected>Select Status</option>



                                                @foreach ($tripstatus as $value)

                                                    <option value="{{ $value->tr_status_id }}"

                                                        {{ old('status', $selectedStatus) == $value->tr_status_id ? 'selected' : '' }}>

                                                        {{ $value->tr_status_name }}

                                                    </option>

                                                @endforeach





                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('status')" />

                                        </div>

                                    </div>



                                </div>





                                <div class="type-of-trip-main">

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                                        <li class="nav-item" role="presentation">

                                            <button class="nav-link active" id="home-tab" data-toggle="tab"

                                                data-target="#home" type="button" role="tab" aria-controls="home"

                                                aria-selected="true">Type of Trip</button>

                                        </li>

                                        <li class="nav-item" role="presentation">

                                            <button class="nav-link" id="profile-tab" data-toggle="tab"

                                                data-target="#profile" type="button" role="tab"

                                                aria-controls="profile" aria-selected="false">Add Traveling

                                                Member</button>

                                        </li>

                                    </ul>

                                    <div class="tab-content" id="myTabContent">

                                        <div class="tab-pane fade show active" id="home" role="tabpanel"

                                            aria-labelledby="home-tab">

                                            <div class="col-md-12">

                                                <div class="form-group">

                                                    <x-input-label for="tr_type_trip" :value="__('Type of Trip')" />

                                                    <!-- <div class="d-flex">

                                                        @foreach ($triptype as $value)

                                                            <div class="custom-control custom-checkbox">

                                                                <input class="checkbox-inputbox custom-control-input"

                                                                    type="checkbox" id="{{ $value->ty_id }}"

                                                                    name="tr_type_trip[]" value="{{ $value->ty_name }}">

                                                                <label for="{{ $value->ty_id }}"

                                                                    class="custom-control-label">{{ $value->ty_name }}</label>

                                                            </div>

                                                        @endforeach

                                                    </div> -->

                                                    @php

                                                    $tripTypes = json_decode($trip->tr_type_trip, true);



                                                    if (json_last_error() !== JSON_ERROR_NONE) {

                                                        $tripTypes = [];

                                                    }



                                                    //print_r($tripTypes);

                                                    @endphp

                                                    <div class="d-flex">

                                                    @foreach ($triptype as $value)

                                                        <div class="custom-control custom-checkbox">

                                                            <input class="checkbox-inputbox custom-control-input"

                                                                type="checkbox" 

                                                                id="{{ $value->ty_id }}"

                                                                name="tr_type_trip[]" 

                                                                value="{{ $value->ty_name }}"

                                                                @if(in_array($value->ty_name, is_array($trip->tr_type_trip) ? $trip->tr_type_trip : (is_array($tripTypes) ? $tripTypes : [])) ) checked @endif>



                                                            <label for="{{ $value->ty_id }}" class="custom-control-label">{{ $value->ty_name }}</label>

                                                        </div>

                                                    @endforeach

                                                </div>





                                                    <x-input-error class="mt-2" :messages="$errors->get('tr_type_trip')" />

                                                </div>

                                            </div>



                                            <div class="col type-of-trip-in">

                                                <h4>Type of Trip</h4>



                                                <!-- Nav tabs -->

                                                <!-- Tab Navigation -->

                                                <ul class="nav nav-tabs" id="tab-list">

                                                    <!-- Dynamic tab links will be added here -->

                                                </ul>



                                                <!-- Tab Content -->

                                                    <div class="tab-content" id="tab-content">

                                                        @php

                                                            $tripTypes = json_decode($trip->tr_type_trip, true) ?? []; // Decode and ensure it's an array

                                                        @endphp



                                                        <div class="dynamic-fields" id="4-fields">


                                                            

                                                            @php

                                                                $displayedTripTypes = []; // Array to track displayed trip types

                                                            @endphp

                                                            @foreach ($typeoftrip as $index => $trip)

                                                            

                                                            @if (in_array($trip->trip_type_name, $tripTypes) && !in_array($trip->trip_type_name, $displayedTripTypes))  {{-- Check if current trip type is in $tripTypes --}}

                                                            
                                                            @php

                                                                $rowtriptype = 0; // Initialize a counter for row trip type

                                                            @endphp

                                                                    <li class="nav-item">

                                                                        <a id="tab-{{ $index }}-tab" class="{{ $index === 0 ? 'active' : '' }}" data-toggle="tab" href="#tab-{{ $index }}">

                                                                            {{ $trip->trip_type_name }}

                                                                        </a>

                                                                    </li>

                                                                    @php

                                                                    $displayedTripTypes[] = $trip->trip_type_name; // Add the trip type to the displayed array

                                                                    @endphp

                                                                @endif                                        

                                                                    <!-- Tab Content -->

                                                                    <div id="tab-{{ $index }}" class="tab-pane {{ $index === 0 ? 'active' : '' }}">

                                                                        @php

                                                                            $tripFields = collect($tripTypes)->filter(fn($t) => $t === $trip->trip_type_name)->values();

                                                                        @endphp

                                                                       

                                                                        @foreach ($tripFields as $fieldIndex => $tripField)

                                                                        @php

                                                                            $fieldIndex = $fieldIndex + 1; // Initialize a counter for row trip type

                                                                        @endphp

                                                                            
                                                                                <div class="row align-items-center mb-3">

                                                                                <input type="hidden" name="trip_types[{{ $fieldIndex }}][{{ $rowtriptype }}][trip_type_name]" value="{{ $trip->trip_type_name }}">



                                                                                <div class="col-md-4">

                                                                                    <input type="text" name="trip_types[{{ $fieldIndex }}][{{ $rowtriptype }}][trip_type_text]" class="form-control" placeholder="Supplier" value="{{ old('trip_types.' . $rowtriptype . '.' . $fieldIndex . '.trip_type_text', $trip->trip_type_text) }}">

                                                                                </div>



                                                                                <div class="col-md-4">

                                                                                    <input type="text" name="trip_types[{{ $fieldIndex }}][{{ $rowtriptype }}][trip_type_confirmation]" class="form-control" placeholder="Confirmation #" value="{{ old('trip_types.' . $rowtriptype . '.' . $fieldIndex . '.trip_type_confirmation', $trip->trip_type_confirmation) }}">

                                                                                </div>



                                                                                @if ($rowtriptype === 0) 

                                                                                    <div class="col-md-2">

                                                                                        <button type="button" class="add_btn w-100 add-btn" data-target="{{ $index }}">+ Add Another</button>

                                                                                    </div>

                                                                            



                                                                                <!-- Delete Button -->

                                                                                <div class="col-md-2">

                                                                                    <button class="delete_btn delete-btn w-100">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">

                                                                                            <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732ZM6.00065 5.5C6.00065 5.36739 5.94797 5.24022 5.8542 5.14645C5.76044 5.05268 5.63326 5 5.50065 5C5.36804 5 5.24087 5.05268 5.1471 5.14645C5.05333 5.24022 5.00065 5.36739 5.00065 5.5V10.5C5.00065 10.6326 5.05333 10.7598 5.1471 10.8536C5.24087 10.9473 5.36804 11 5.50065 11C5.63326 11 5.76044 10.9473 5.8542 10.8536C5.94797 10.7598 6.00065 10.6326 6.00065 10.5V5.5ZM8.50065 5C8.63326 5 8.76044 5.05268 8.8542 5.14645C8.94797 5.24022 9.00065 5.36739 9.00065 5.5V10.5C9.00065 10.6326 8.94797 10.7598 8.8542 10.8536C8.76044 10.9473 8.63326 11 8.50065 11C8.36804 11 8.24087 10.9473 8.1471 10.8536C8.05333 10.7598 8.00065 10.6326 8.00065 10.5V5.5C8.00065 5.36739 8.05333 5.24022 8.1471 5.14645C8.24087 5.05268 8.36804 5 8.50065 5ZM3.48998 11.3113C3.52594 11.6824 3.69881 12.0268 3.9749 12.2774C4.25098 12.528 4.61048 12.6667 4.98332 12.6667H9.01798C9.39082 12.6667 9.75032 12.528 10.0264 12.2774C10.3025 12.0268 10.4754 11.6824 10.5113 11.3113L11.2833 3.33333H2.71798L3.48998 11.3113Z" fill="#9A9DA4"></path>

                                                                                        </svg> Delete

                                                                                    </button>

                                                                                </div>

                                                                                @else

                                                                                <div class="col-md-2">

                                                                                    <button class="delete_btn delete-btn w-100">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">

                                                                                            <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732ZM6.00065 5.5C6.00065 5.36739 5.94797 5.24022 5.8542 5.14645C5.76044 5.05268 5.63326 5 5.50065 5C5.36804 5 5.24087 5.05268 5.1471 5.14645C5.05333 5.24022 5.00065 5.36739 5.00065 5.5V10.5C5.00065 10.6326 5.05333 10.7598 5.1471 10.8536C5.24087 10.9473 5.36804 11 5.50065 11C5.63326 11 5.76044 10.9473 5.8542 10.8536C5.94797 10.7598 6.00065 10.6326 6.00065 10.5V5.5ZM8.50065 5C8.63326 5 8.76044 5.05268 8.8542 5.14645C8.94797 5.24022 9.00065 5.36739 9.00065 5.5V10.5C9.00065 10.6326 8.94797 10.7598 8.8542 10.8536C8.76044 10.9473 8.63326 11 8.50065 11C8.36804 11 8.24087 10.9473 8.1471 10.8536C8.05333 10.7598 8.00065 10.6326 8.00065 10.5V5.5C8.00065 5.36739 8.05333 5.24022 8.1471 5.14645C8.24087 5.05268 8.36804 5 8.50065 5ZM3.48998 11.3113C3.52594 11.6824 3.69881 12.0268 3.9749 12.2774C4.25098 12.528 4.61048 12.6667 4.98332 12.6667H9.01798C9.39082 12.6667 9.75032 12.528 10.0264 12.2774C10.3025 12.0268 10.4754 11.6824 10.5113 11.3113L11.2833 3.33333H2.71798L3.48998 11.3113Z" fill="#9A9DA4"></path>

                                                                                        </svg> Delete

                                                                                    </button>

                                                                                </div>

                                                                                @endif

                                                                            </div>

                                                                            @php

                                                                                $rowtriptype = $rowtriptype + 1; // Initialize a counter for row trip type

                                                                            @endphp


                                                                        @endforeach

                                                                    </div>

                                                            @endforeach

                                                        </div>



                                                    </div>

    





                                            </div>

                                        </div>

                                        <div class="tab-pane fade" id="profile" role="tabpanel"

                                            aria-labelledby="profile-tab">



                                        <div class="col-md-12" id="dynamic_field">

                                        @php

                                            $i = 1;

                                        @endphp

                                        @foreach ($tripmember as $index => $item)

                                            @php

                                                $rowCount = $i + 1;



                                            @endphp

                                            <div class="item-row row" id="row{{ $rowCount }}">

                                                <input type="hidden" name="trtm_id_hidden" id="trtm_id_hidden"

                                                    value="{{ $rowCount }}" />

                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <div class="d-flex">

                                                       

                                                            <input type="hidden" name="trtm_type_hidden"

                                                                class="trtm_type_hidden" id="trtm_type_hidden"

                                                                value="{{ $item->trtm_type }}" />

                                                            <div class="custom-control custom-radio custom-control-inline">

                                                                <input type="radio" class="trtm_type custom-control-input"

                                                                    id="trtm_type_family_{{ $rowCount }}"

                                                                    name="items[{{ $rowCount }}][trtm_type]"

                                                                    value="1"

                                                                    {{ $item->trtm_type == 1 ? 'checked' : '' }}><label

                                                                    for="trtm_type_family_{{ $rowCount }}" class="custom-control-label">Family

                                                                    Member</label>

                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">

                                                                <input type="radio" class="trtm_type custom-control-input"

                                                                    id="trtm_type_trip_{{ $rowCount }}"

                                                                    name="items[{{ $rowCount }}][trtm_type]"

                                                                    value="2"

                                                                    {{ $item->trtm_type == 2 ? 'checked' : '' }}><label

                                                                    for="trtm_type_trip_{{ $rowCount }}" class="custom-control-label">Trip

                                                                    Member</label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="trtm_first_name">First Name<span

                                                                class="text-danger">*</span></label>

                                                        <div class="d-flex">

                                                            <input type="text" class="form-control"

                                                                id="trtm_first_name{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_first_name]"

                                                                placeholder="Enter First Name"

                                                                value="{{ $item->trtm_first_name }}">

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3 family-member-field">

                                                    <div class="form-group">

                                                        <label for="trtm_middle_name">Middle name</label>

                                                        <div class="d-flex">

                                                            <input type="text" class="form-control"

                                                                id="trtm_middle_name{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_middle_name]"

                                                                placeholder="Enter Middle name"

                                                                value="{{ $item->trtm_middle_name }}">

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="trtm_last_name">Last Name</label>

                                                        <div class="d-flex">

                                                            <input type="text" class="form-control"

                                                                id="trtm_last_name{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_last_name]"

                                                                placeholder="Enter Last Name"

                                                                value="{{ $item->trtm_last_name }}">

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3 family-member-field">

                                                    <div class="form-group">

                                                        <label for="trtm_nick_name">Nickname</label>

                                                        <div class="d-flex">

                                                            <input type="text" class="form-control"

                                                                id="trtm_nick_name{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_nick_name]"

                                                                placeholder="Enter Nickname" value="{{ $rowCount }}">

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3 family-member-field">

                                                    <div class="form-group">

                                                        <label for="trtm_relationship">Relationship<span

                                                                class="text-danger">*</span></label>

                                                        <div class="d-flex">

                                                            <input type="text" class="form-control"

                                                                id="trtm_relationship{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_relationship]"

                                                                placeholder="Enter Relationship"

                                                                value="{{ $item->trtm_relationship }}">

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="trtm_gender">Gender<span

                                                                class="text-danger">*</span></label>

                                                        <div class="d-flex">

                                                            <select class="form-control select2" style="width: 100%;"

                                                                id="trtm_gender{{ $rowCount }}"

                                                                name="items[{{ $rowCount }}][trtm_gender]">

                                                                <option default>Select Gender</option>

                                                                <option value="Male"

                                                                    {{ $item->trtm_gender == 'Male' ? 'selected' : '' }}>

                                                                    Male</option>

                                                                <option value="Female"

                                                                    {{ $item->trtm_gender == 'Female' ? 'selected' : '' }}>

                                                                    Female</option>

                                                                <option value="Other"

                                                                    {{ $item->trtm_gender == 'Other' ? 'selected' : '' }}>

                                                                    Other</option>

                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>



                                              

                                                <!-- Dynamic Row Example -->

                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="trtm_dob_{{ $index }}">Birthdate</label>

                                                        <div class="d-flex">

                                                            <div class="input-group date"

                                                                id="trtm_dob_{{ $index }}"

                                                                data-target-input="nearest">

                                                                <x-flatpickr id="traveler_date_{{ $index }}"

                                                                    name="items[{{ $rowCount }}][trtm_dob]"

                                                                    placeholder="mm/dd/yyyy" />

                                                                <div class="input-group-append">

                                                                    <div class="input-group-text"

                                                                        id="traveler-date-icon_{{ $index }}">

                                                                        <i class="fa fa-calendar-alt"></i>

                                                                        <input type="hidden"

                                                                            id="trtm_dob_hidden_{{ $index }}"

                                                                            value="{{ $item->trtm_dob ?? '' }}" />

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="trtm_age_{{ $index }}">Age</label>

                                                        <!-- Ensure the ID is consistent -->

                                                        <div class="d-flex">

                                                            <input type="text"

                                                                name="items[{{ $rowCount }}][trtm_age]"

                                                                class="form-control" aria-describedby="inputGroupPrepend"

                                                                placeholder="Enter Age"

                                                                id="trtm_ages_{{ $index }}"

                                                                value="{{ $item->trtm_age ?? '' }}" readonly>

                                                        </div>

                                                    </div>

                                                </div>



                                                <div class="col-md-3">

                                                    

                                                        <button class="delete_btn delete-item" id="{{ $rowCount }}">

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">

                                                        <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732ZM6.00065 5.5C6.00065 5.36739 5.94797 5.24022 5.8542 5.14645C5.76044 5.05268 5.63326 5 5.50065 5C5.36804 5 5.24087 5.05268 5.1471 5.14645C5.05333 5.24022 5.00065 5.36739 5.00065 5.5V10.5C5.00065 10.6326 5.05333 10.7598 5.1471 10.8536C5.24087 10.9473 5.36804 11 5.50065 11C5.63326 11 5.76044 10.9473 5.8542 10.8536C5.94797 10.7598 6.00065 10.6326 6.00065 10.5V5.5ZM8.50065 5C8.63326 5 8.76044 5.05268 8.8542 5.14645C8.94797 5.24022 9.00065 5.36739 9.00065 5.5V10.5C9.00065 10.6326 8.94797 10.7598 8.8542 10.8536C8.76044 10.9473 8.63326 11 8.50065 11C8.36804 11 8.24087 10.9473 8.1471 10.8536C8.05333 10.7598 8.00065 10.6326 8.00065 10.5V5.5C8.00065 5.36739 8.05333 5.24022 8.1471 5.14645C8.24087 5.05268 8.36804 5 8.50065 5ZM3.48998 11.3113C3.52594 11.6824 3.69881 12.0268 3.9749 12.2774C4.25098 12.528 4.61048 12.6667 4.98332 12.6667H9.01798C9.39082 12.6667 9.75032 12.528 10.0264 12.2774C10.3025 12.0268 10.4754 11.6824 10.5113 11.3113L11.2833 3.33333H2.71798L3.48998 11.3113Z" fill="white"></path>

                                                        </svg>

                                                        Remove

                                                        </button>

                                                </div>

                                            </div>

                                            <hr />

                                            @php

                                                $i++;

                                            @endphp

                                        @endforeach

                                        

                                        </div> 

                                        <div class="col-md-12">

                                            <button type="button" id="add"

                                            class="add_tripmembertbtn add_btn"><i

                                            class="fas fa-plus add_plus_icon"></i>Add Traveling Member</button>

                                        </div>





                                        </div>

                                    </div>

                                    @if($triptinerary && count($triptinerary) > 0)

                                

                                    <div class="itinerary-link">

                                        <div class="dynamic-fields" id="Itinerary-fields">

                                            @foreach($triptinerary as $index => $itinerary)

                                                <div class="row align-items-center mb-3">

                                                    <div class="col-md-4">

                                                        <input type="text" name="itinerary[{{ $index }}][trit_text]" 

                                                            class="form-control" 

                                                            placeholder="Itinerary Link"

                                                            value="{{ $itinerary->trit_text ?? '' }}">

                                                    </div>

                                                    <div class="col-md-2">

                                                        @if($loop->last)

                                                            <button type="button" class="add_btn w-100 add-btn2" data-target="Itinerary">+ Add Another</button>

                                                        @else

                                                            <button class="btn btn-danger btn-sm delete-btn w-100">Delete</button>

                                                        @endif

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                    @else



                                    <div class="itinerary-link">

                                        <div class="dynamic-fields" id="Itinerary-fields">

                                            <div class="row align-items-center mb-3">

                                                <div class="col-md-4">

                                                    <input type="text" name="itinerary[0][trit_text]"

                                                        class="form-control" placeholder="Itinerary Link">

                                                </div>

                                                <div class="col-md-2">

                                                    <button type="button" class="add_btn w-100 add-btn2"

                                                        data-target="Itinerary">+ Add Another</button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    @endif                                   



                                <div class="row py-20 px-10">

                                    <div class="col-md-12 text-center">

                                        <a href="{{ route('trip.index') }}" class="add_btn_br px-10">Cancel</a>

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

            $(document).ready(function() {

                $('.trtm_type_hidden').each(function() {

                    var rowId = $(this).closest('.item-row').attr('id');



                    if ($(this).val() == 1) {



                        $(`#${rowId} .family-member-field`).show();

                        $(`#${rowId} .trip-member-field`).hide();

                    } else if ($(this).val() == 2) {

                        $(`#${rowId} .family-member-field`).hide();

                        $(`#${rowId} .trip-member-field`).show();

                    }

                });

            });



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



                var birthdatedate = document.getElementById('birthdate_hidden');



                birthdatedate = flatpickr("#birthdate_date", {

                    locale: 'en',

                    altInput: true,

                    dateFormat: "m/d/Y",

                    altFormat: "d/m/Y",

                    allowInput: true,

                    defaultDate: birthdatedate.value || null,

                });



                document.getElementById('birthdate-hidden-icon').addEventListener('click', function() {

                    birthdatedate.open();

                });





            });





            document.querySelectorAll('[id^="trtm_dob_hidden_"]').forEach(function(hiddenInput) {

                var index = hiddenInput.id.split('_').pop(); // Extract the unique index from the hidden input ID



                // Initialize Flatpickr

                var travelerDatePicker = flatpickr(`#traveler_date_${index}`, {

                    locale: 'en',

                    altInput: true,

                    dateFormat: "m/d/Y",

                    altFormat: "d/m/Y",

                    allowInput: true,

                    defaultDate: hiddenInput.value || null,

                });



                var calendarIcon = document.getElementById(`traveler-date-icon_${index}`);

                if (calendarIcon) {

                    calendarIcon.addEventListener('click', function() {

                        travelerDatePicker.open();

                    });

                } else {

                    // console.warn(`Calendar icon with ID traveler-date-icon_${index} not found.`);

                }



                // Traveler date input

                var travelerDateInput = document.querySelector(`#traveler_date_${index}`);

                // Ensure the ID is consistent with the ID in the HTML

                var travelerageInput = document.querySelector(`#trtm_ages_${index}`);



                if (travelerDateInput) {

                    travelerDateInput.addEventListener('change', function() {

                        var birthdate = new Date(travelerDateInput.value);

                        var today = new Date();

                        var age = today.getFullYear() - birthdate.getFullYear();

                        var m = today.getMonth() - birthdate.getMonth();



                        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {

                            age--;

                        }



                        // Now set the age in the correct input

                        if (travelerageInput) {

                            travelerageInput.value = (age < 0) ? 0 : age;

                        } else {

                            // console.error(`Traveler age input with ID trtm_ages_${index} not found.`);

                        }

                    });

                } else {

                    // console.warn(`Traveler date input with ID traveler_date_${index} not found.`);

                }

            });

        </script>



        <script>

            $(document).ready(function() {

                var rowCount = 0;



                $('#add').click(function() {

                    // alert('add');

                    rowCount++;

                    $('#dynamic_field').append(`

                        <div class="item-row row" id="row${rowCount}">

                        <div class="col-md-3">

                        <div class="form-group">

                        <div class="d-flex">

                        <div class="custom-control custom-radio custom-control-inline">

                        <input type="radio" class="trtm_type custom-control-input" id="trtm_type_family${rowCount}" name="items[${rowCount}][trtm_type]" value="1" >

                        <label for="trtm_type_family${rowCount}" class="custom-control-label">Family Member</label> 

                        </div>

                        <div class="custom-control custom-radio custom-control-inline">

                        <input type="radio" class="trtm_type custom-control-input" id="trtm_type_trip${rowCount}" name="items[${rowCount}][trtm_type]" value="2">

                        <label for="trtm_type_trip${rowCount}" class="custom-control-label">Trip Member</label>

                        </div>       

                        </div>

                        </div>

                        </div>



                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_first_name">First Name<span

                    class="text-danger">*</span></label>

                    <div class="d-flex">

                    <input type="text" class="form-control" id="trtm_first_name${rowCount}" name="items[${rowCount}][trtm_first_name]" placeholder="Enter First Name">

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3 family-member-field">

                    <div class="form-group">

                    <label for="trtm_middle_name">Middle name</label>

                    <div class="d-flex">

                    <input type="text" class="form-control" id="trtm_middle_name${rowCount}" name="items[${rowCount}][trtm_middle_name]" placeholder="Enter Middle name">

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_last_name">Last Name</label>

                    <div class="d-flex">

                    <input type="text" class="form-control" id="trtm_last_name${rowCount}" name="items[${rowCount}][trtm_last_name]" placeholder="Enter Last Name">

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3 family-member-field">

                    <div class="form-group">

                    <label for="trtm_nick_name">Nickname</label>

                    <div class="d-flex">

                    <input type="text" class="form-control" id="trtm_nick_name${rowCount}" name="items[${rowCount}][trtm_nick_name]" placeholder="Enter Nickname">

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3 family-member-field">

                    <div class="form-group">

                    <label for="trtm_relationship">Relationship<span

                    class="text-danger">*</span></label>

                    <div class="d-flex">

                    <input type="text" class="form-control" id="trtm_relationship${rowCount}" name="items[${rowCount}][trtm_relationship]" placeholder="Enter Relationship">

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_gender">Gender<span

                    class="text-danger">*</span></label>

                    <div class="d-flex">

                    <select class="form-control select2" style="width: 100%;" id="trtm_gender${rowCount}" name="items[${rowCount}][trtm_gender]" >

                    <option default>Select Gender</option>

                    <option value="Male">Male</option>

                    <option value="Female">Female</option>

                    <option value="Other">Other</option>

                    </select>

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_dob">Birthdate</label>

                    <div class="d-flex">

                    <div class="input-group date" id="trtm_dob" data-target-input="nearest">

                    <x-flatpickr id="traveler_date_${rowCount}" name="items[${rowCount}][trtm_dob]" placeholder="mm/dd/yyyy" />

                    <div class="input-group-append">

                    <div class="input-group-text" id="traveler-date-icons_${rowCount}">

                    <i class="fa fa-calendar-alt"></i>

                    <input type="hidden" id="trtm_dob_hidden" value="" />

                    </div>

                    </div>

                    </div>

                    </div>

                    </div>

                    </div>



                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_age">Age</label>

                    <div class="d-flex">

                    <input type="text" name="items[${rowCount}][trtm_age]" class="form-control" aria-describedby="inputGroupPrepend" placeholder="Enter Age" id="trtm_age_${rowCount}" readonly>

                    </div>

                    </div>

                    </div>

                    <div class="col-md-3">

                    <div class="form-group">

                    <label for="trtm_age">&nbsp;</label>

                    <div class="d-flex">

                    <button class="delete_btn delete-item" id="${rowCount}">

                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">

                    <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732ZM6.00065 5.5C6.00065 5.36739 5.94797 5.24022 5.8542 5.14645C5.76044 5.05268 5.63326 5 5.50065 5C5.36804 5 5.24087 5.05268 5.1471 5.14645C5.05333 5.24022 5.00065 5.36739 5.00065 5.5V10.5C5.00065 10.6326 5.05333 10.7598 5.1471 10.8536C5.24087 10.9473 5.36804 11 5.50065 11C5.63326 11 5.76044 10.9473 5.8542 10.8536C5.94797 10.7598 6.00065 10.6326 6.00065 10.5V5.5ZM8.50065 5C8.63326 5 8.76044 5.05268 8.8542 5.14645C8.94797 5.24022 9.00065 5.36739 9.00065 5.5V10.5C9.00065 10.6326 8.94797 10.7598 8.8542 10.8536C8.76044 10.9473 8.63326 11 8.50065 11C8.36804 11 8.24087 10.9473 8.1471 10.8536C8.05333 10.7598 8.00065 10.6326 8.00065 10.5V5.5C8.00065 5.36739 8.05333 5.24022 8.1471 5.14645C8.24087 5.05268 8.36804 5 8.50065 5ZM3.48998 11.3113C3.52594 11.6824 3.69881 12.0268 3.9749 12.2774C4.25098 12.528 4.61048 12.6667 4.98332 12.6667H9.01798C9.39082 12.6667 9.75032 12.528 10.0264 12.2774C10.3025 12.0268 10.4754 11.6824 10.5113 11.3113L11.2833 3.33333H2.71798L3.48998 11.3113Z" fill="white"></path>

                    </svg>

                    Remove

                    </button>

                    </div>

                    </div>

                    </div>



                    </div>

                    <hr />

                    `);



                    $(`#row${rowCount} .family-member-field`).hide();

                    $(`#row${rowCount} .trip-member-field`).hide();



                    var numofpeople = document.querySelector('#tr_num_people');

                    numofpeople.value = rowCount;



                    var travelerdates = flatpickr(`#traveler_date_${rowCount}`, {

                        locale: 'en',

                        altInput: true,

                        dateFormat: "m/d/Y",

                        altFormat: "d/m/Y",

                        allowInput: true,

                    });



                    document.getElementById(`traveler-date-icons_${rowCount}`).addEventListener('click',

                        function() {

                            // alert('jhk');

                            travelerdates.open();

                        });





                    var birthdateInput = document.querySelector(`#traveler_date_${rowCount}`);

                    var ageInput = document.querySelector(`#trtm_age_${rowCount}`);



                    birthdateInput.addEventListener('change', function() {

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



                $(document).on('change', '.trtm_type', function() {

                    var rowId = $(this).closest('.item-row').attr('id').replace('row', '');

                    if ($(this).val() == 1) {

                        $(`#row${rowId} .family-member-field`).show();

                        $(`#row${rowId} .trip-member-field`).hide();

                    } else if ($(this).val() == 2) {

                        $(`#row${rowId} .family-member-field`).hide();

                        $(`#row${rowId} .trip-member-field`).show();

                    }

                });



                $(document).on('click', '.delete-item', function() {

                    var rowId = $(this).attr("id");

                    $('#row' + rowId).remove();



                    rowCount--;

                    $('#tr_num_people').val(rowCount);

                });



                var birthdateInput = document.querySelector('#birthdate_date');

                var ageInput = document.querySelector('#tr_age');



                birthdateInput.addEventListener('change', function() {

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



        <script>

            $(document).ready(function() {

                // Function to add new fields

                var rowCountItinerary = 0;

                $('.add-btn2').click(function() {

                    rowCountItinerary++;

                    var target = $(this).data('target');

                    var newRow = `

          <div class="row align-items-center mb-3">

          <div class="col-md-4">

          <input type="text" class="form-control" name="itinerary[${rowCountItinerary}][trit_text]" placeholder="${target.charAt(0).toUpperCase() + target.slice(1)} Link">

          </div>



          <div class="col-md-2">

          <button class="btn btn-danger btn-sm delete-btn w-100">Delete</button>

          </div>

          </div>

          `;

                    $('#' + target + '-fields').append(newRow);

                });



                // Function to delete fields

                $(document).on('click', '.delete-btn', function() {

                    $(this).closest('.row').remove();

                });

            });







            $(document).ready(function() {

                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

                    var activeTab = $(e.target).text(); // Get active tab text

                    $('#trip_type_text').val(activeTab); // Set it to hidden field

                });

            });

        </script>



        <script>

            $(document).ready(function() {

                // When a tab is clicked, update the hidden input field with the tab's text

                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

                    var tripTypeName = $(e.target).text(); // Get the tab text (Cruise, Excursion, etc.)

                    $('#trip_type_name').val(tripTypeName); // Set the hidden field value

                });

            });

        </script>



<script>

   document.addEventListener('DOMContentLoaded', function () {

        const checkboxes = document.querySelectorAll('input[name="tr_type_trip[]"]');

        const tabList = document.getElementById('tab-content');

        const tabContent = document.getElementById('tab-content');



        // A set to keep track of currently active trip types to avoid duplicates

        const activeTripTypes = new Set();



        checkboxes.forEach(checkbox => {

            checkbox.addEventListener('change', function () {

                handleTabCreationAndRemoval(checkbox);

            });

        });



        // Function to create or remove tabs based on checkbox state

        function handleTabCreationAndRemoval(checkbox) {

            const tripTypeName = checkbox.value;

            const tripTypeId = checkbox.id;



            // If the checkbox is checked and trip type is not active, add the tab and its fields

            if (checkbox.checked && !activeTripTypes.has(tripTypeName)) {

                activeTripTypes.add(tripTypeName); // Mark this trip type as active

                createTabAndFields(tripTypeName, tripTypeId);

            }

            // If the checkbox is unchecked, remove the tab and its fields

            else if (!checkbox.checked && activeTripTypes.has(tripTypeName)) {

                activeTripTypes.delete(tripTypeName); // Remove this trip type from active set

                removeTabAndFields(tripTypeId);

            }

        }



        // Function to create the tab and fields for a checked checkbox

        function createTabAndFields(tripTypeName, tripTypeId) {

            // Check if the tab already exists (should not happen due to the set check)

            const existingTab = document.getElementById(`${tripTypeId}-tab`);

            const existingTabPanel = document.getElementById(`tab-${tripTypeId}`);

            

            // If tab and panel do not exist, create them

            if (!existingTab) {

                // Create tab link

                const tabLink = document.createElement('li');

                tabLink.className = 'nav-item';

                tabLink.innerHTML = `

                    <a id="${tripTypeId}-tab" href="#tab-${tripTypeId}" data-bs-toggle="tab">${tripTypeName}</a>

                `;

                tabList.appendChild(tabLink);



                // Create tab panel

                const tabPanel = document.createElement('div');

                tabPanel.id = `tab-${tripTypeId}`; // Set unique ID for the tab panel

                tabPanel.innerHTML = `

                    <div class="dynamic-fields" id="${tripTypeId}-fields">

                        <input type="hidden" name="trip_types[${tripTypeId}][0][trip_type_name]" value="${tripTypeName}">

                        <div class="row align-items-center mb-3">

                            <div class="col-md-4">

                                <input type="text" name="trip_types[${tripTypeId}][0][trip_type_text]" class="form-control" placeholder="${tripTypeName} Supplier">

                            </div>

                            <div class="col-md-4">

                                <input type="text" name="trip_types[${tripTypeId}][0][trip_type_confirmation]" class="form-control" placeholder="${tripTypeName} Confirmation #">

                            </div>

                            <div class="col-md-2">

                                <button type="button" class="add_btn w-100 add-btn" data-target="${tripTypeId}">+ Add Another</button>

                            </div>

                            <div class="col-md-2">

                                <button class="delete_btn delete-btn w-100">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">

                                        <path d="M5.66732 2.33333H8.33398C8.33398 1.97971 8.19351 1.64057 7.94346 1.39052C7.69341 1.14048 7.35427 1 7.00065 1C6.64703 1 6.30789 1.14048 6.05784 1.39052C5.80779 1.64057 5.66732 1.97971 5.66732 2.33333ZM4.66732 2.33333C4.66732 2.02692 4.72767 1.7235 4.84493 1.44041C4.96219 1.15731 5.13407 0.900088 5.35074 0.683418C5.56741 0.466748 5.82463 0.294875 6.10772 0.177614C6.39082 0.0603535 6.69423 0 7.00065 0C7.30707 0 7.61049 0.0603535 7.89358 0.177614C8.17667 0.294875 8.4339 0.466748 8.65057 0.683418C8.86724 0.900088 9.03911 1.15731 9.15637 1.44041C9.27363 1.7235 9.33398 2.02692 9.33398 2.33333H13.1673C13.2999 2.33333 13.4271 2.38601 13.5209 2.47978C13.6146 2.57355 13.6673 2.70073 13.6673 2.83333C13.6673 2.96594 13.6146 3.09312 13.5209 3.18689C13.4271 3.28066 13.2999 3.33333 13.1673 3.33333H12.2873L11.5073 11.4073C11.4475 12.026 11.1593 12.6002 10.6991 13.0179C10.2389 13.4356 9.63952 13.6669 9.01798 13.6667H4.98332C4.36189 13.6667 3.76272 13.4354 3.30262 13.0177C2.84252 12.6 2.55447 12.0259 2.49465 11.4073L1.71398 3.33333H0.833984C0.701376 3.33333 0.574199 3.28066 0.480431 3.18689C0.386663 3.09312 0.333984 2.96594 0.333984 2.83333C0.333984 2.70073 0.386663 2.57355 0.480431 2.47978C0.574199 2.38601 0.701376 2.33333 0.833984 2.33333H4.66732Z" fill="#868686"/>

                                    </svg> Delete

                                </button>

                            </div>

                        </div>

                    </div>

                `;

                tabContent.appendChild(tabPanel);

                // Attach the Add Button Event Listener

                const addButton = tabPanel.querySelector('.add-btn');

                let entryIndex = 1;  // To increment index for each added row

                addButton.addEventListener('click', function () {

                    addNewRow(tripTypeName, tripTypeId, entryIndex);

                    entryIndex++;

                });



                // Activate the first tab when it is created

                if (tabList.children.length === 1) {

                    tabLink.querySelector('a').classList.add('active');

                    tabPanel.classList.add('show', 'active');

                }

            }

        }



        function addNewRow(tripTypeName, tripTypeId, entryIndex) {

            const fieldsContainer = document.getElementById(`${tripTypeId}-fields`);



            const newRow = document.createElement('div');

            newRow.classList.add('row', 'align-items-center', 'mb-3');

            newRow.innerHTML = `

                <input type="hidden" name="trip_types[${tripTypeId}][${entryIndex}][trip_type_name]" value="${tripTypeName}">

                <div class="col-md-4">

                    <input type="text" name="trip_types[${tripTypeId}][${entryIndex}][trip_type_text]" class="form-control" placeholder="${tripTypeName} Supplier">

                </div>

                <div class="col-md-4">

                    <input type="text" name="trip_types[${tripTypeId}][${entryIndex}][trip_type_confirmation]" class="form-control" placeholder="${tripTypeName} Confirmation #">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-danger btn-sm delete-btn w-100">Delete</button>

                </div>

            `;

            fieldsContainer.appendChild(newRow);



            // Add event listener to delete button in new row

            const deleteButton = newRow.querySelector('.delete-btn');

            deleteButton.addEventListener('click', function () {

                newRow.remove();  // Remove only the specific row

            });

        }



        // Function to remove the tab and fields for an unchecked checkbox

        function removeTabAndFields(tripTypeId) {

            const tabLink = document.getElementById(`${tripTypeId}-tab`);

            const tabPanel = document.getElementById(`tab-${tripTypeId}`);



            // Remove the tab and the corresponding tab content panel

            if (tabLink) tabLink.remove();

            if (tabPanel) tabPanel.remove();

        }



        // Function to remove tab and fields for an unchecked checkbox

        function removeTabAndFields(tripTypeId) {

            const tabLink = document.getElementById(`${tripTypeId}-tab`);

            const tabPanel = document.getElementById(`tab-${tripTypeId}`);



            // Remove the tab and the corresponding tab content panel

            if (tabLink) tabLink.remove();

            if (tabPanel) tabPanel.remove();

        }

    });

       

       </script>



        

    @endsection

@endif


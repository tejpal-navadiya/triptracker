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
                                            <x-input-label for="tr_name" :value="__('Name of Trip')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_name"
                                                placeholder="Enter Name of Trip" name="tr_name" required autofocus
                                                autocomplete="tr_name" :value="old('tr_name', $trip->tr_name ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                        </div>



                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Agent Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Select Agent" name="tr_agent_id" required autofocus
                                                autocomplete="tr_agent_id" :value="old('tr_agent_id', $trip->tr_agent_id ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_traveler_name" :value="__('Traveler Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_traveler_name"
                                                placeholder="Traveler Name" name="tr_traveler_name" required autofocus
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
                                                placeholder="Enter Age" name="tr_age" required autofocus
                                                autocomplete="tr_age" :value="old('tr_age', $trip->tr_age ?? '')" readonly />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_age')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_number" :value="__('Trip Number')" />
                                            <x-text-input type="text" class="form-control" id="tr_number"
                                                placeholder="Enter Trip Number" name="tr_number" required autofocus
                                                autocomplete="tr_number" :value="old('tr_number', $trip->tr_number ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_number')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_email" :value="__('Email Address')" />
                                            <x-text-input type="email" class="form-control" id="tr_email"
                                                placeholder="Enter Email Address" name="tr_email" required autofocus
                                                autocomplete="tr_email" :value="old('tr_email', $trip->tr_email ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Phone Number')" />
                                            <x-text-input type="text" class="form-control" id="tr_phone"
                                                placeholder="Enter Phone Number" name="tr_phone" required autofocus
                                                autocomplete="tr_phone" :value="old('tr_phone', $trip->tr_phone ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_num_people" :value="__('Number of People')" />
                                            <x-text-input type="text" class="form-control" id="tr_num_people"
                                                placeholder="Enter Number of People" name="tr_num_people" required
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
                                                placeholder="Enter Value of trip" name="tr_value_trip" required autofocus
                                                autocomplete="tr_value_trip" :value="old('tr_value_trip', $trip->tr_value_trip ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_value_trip')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_desc" :value="__('Description')" />
                                            <textarea type="text" class="form-control" id="tr_desc" placeholder="Enter Description" name="tr_desc"
                                                required autofocus autocomplete="tr_desc">{{ $trip->tr_desc }}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_desc')" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-input-label for="tr_type_trip" :value="__('Type of Trip')" />
                                            <div>
                                                @foreach ($triptype as $value)
                                                    @php
                                                        $decodedTripTypes =
                                                            json_decode($trip->tr_type_trip, true) ?? [];
                                                    @endphp
                                                    @if (in_array($value->ty_name, $decodedTripTypes))
                                                        <input class="checkbox-inputbox" type="checkbox"
                                                            id="{{ $value->ty_id }}" name="tr_type_trip[]"
                                                            value="{{ $value->ty_name }}" checked>
                                                    @else
                                                        <input class="checkbox-inputbox" type="checkbox"
                                                            id="{{ $value->ty_id }}" name="tr_type_trip[]"
                                                            value="{{ $value->ty_name }}">
                                                    @endif
                                                    <label for="{{ $value->ty_id }}">{{ $value->ty_name }}</label>
                                                @endforeach
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_type_trip')" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" id="add" class="add_tripmembertbtn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add
                                            Traveling Member</button>
                                    </div>
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
                                                            <input type="radio" class="trtm_type"
                                                                id="trtm_type_family_{{ $rowCount }}"
                                                                name="items[{{ $rowCount }}][trtm_type]"
                                                                value="1"
                                                                {{ $item->trtm_type == 1 ? 'checked' : '' }}><label
                                                                for="trtm_type_family_{{ $rowCount }}">Family
                                                                Member</label>
                                                            <input type="radio" class="trtm_type"
                                                                id="trtm_type_trip_{{ $rowCount }}"
                                                                name="items[{{ $rowCount }}][trtm_type]"
                                                                value="2"
                                                                {{ $item->trtm_type == 2 ? 'checked' : '' }}><label
                                                                for="trtm_type_trip_{{ $rowCount }}">Trip
                                                                Member</label>
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

                                                <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="trtm_dob_{{ $index }}">Birthdate</label>
                            <div class="d-flex">
                                <div class="input-group date" id="trtm_dob_{{ $index }}" data-target-input="nearest">
                                    <x-flatpickr id="traveler_date_{{ $index }}" name="items[{{ $index }}][trtm_dob]" placeholder="mm/dd/yyyy" />
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="traveler-date-icon_{{ $index }}">
                                            <i class="fa fa-calendar-alt"></i>
                                            <input type="hidden" id="trtm_dob_hidden_{{ $index }}" value="{{ $item->trtm_dob ?? '' }}" />
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
                              <input type="text" name="items[{{ $rowCount }}][trtm_age]" class="form-control" aria-describedby="inputGroupPrepend" placeholder="Enter Age" id="trtm_ages_{{ $rowCount }}" value="{{ $item->trtm_age ?? '' }}" readonly>
                          </div>
                      </div>
                  </div> -->

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
                                                    <i class="fa fa-trash delete-item" id="{{ $rowCount }}">
                                                        Remove</i>
                                                </div>
                                            </div>
                                            <hr />
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </div>
                                </div>

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
          <input type="radio" class="trtm_type" id="trtm_type_family${rowCount}" name="items[${rowCount}][trtm_type]" value="1" ><label for="trtm_type_family${rowCount}">Family Member</label>
          <input type="radio" class="trtm_type" id="trtm_type_trip${rowCount}" name="items[${rowCount}][trtm_type]" value="2"><label for="trtm_type_trip${rowCount}">Trip Member</label>
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
            <x-flatpickr id="traveler_dates_${rowCount}" name="items[${rowCount}][trtm_dob]" placeholder="mm/dd/yyyy" />
            <div class="input-group-append">
            <div class="input-group-text" id="traveler_date_icon_${rowCount}">
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
      <i class="fa fa-trash delete-item" id="${rowCount}"> Remove</i>
      </div>

    </div>
    <hr />
    `);

                    $(`#row${rowCount} .family-member-field`).hide();
                    $(`#row${rowCount} .trip-member-field`).hide();

                    var numofpeople = document.querySelector('#tr_num_people');
                    numofpeople.value = parseInt(numofpeople.value) + 1;

                    var travelerdate = flatpickr(`#traveler_dates_${rowCount}`, {
                        locale: 'en',
                        altInput: true,
                        dateFormat: "m/d/Y",
                        altFormat: "d/m/Y",
                        allowInput: true,
                    });

                    document.getElementById(`traveler_date_icon_${rowCount}`).addEventListener('click',
                        function() {
                            // alert('jhk');
                            travelerdate.open();
                        });


                    var birthdateInput = document.querySelector(`#traveler_dates_${rowCount}`);
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

                    var currentValue = parseInt($('#tr_num_people').val()); // get the current value
                    var newValue = currentValue - 1; // decrement the value by 1
                    $('#tr_num_people').val(newValue); // set the new value
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
    @endsection
@endif

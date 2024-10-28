@extends('masteradmin.layouts.app')
<!DOCTYPE html>


<title>Edit Agency | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Edit Agency User') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Edit Agency') }}</li>
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

                        @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif


                        <div class="card-header">
                            <h3 class="card-title">Edit Agency</h3>
                        </div>


                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('agency.update', $agency->users_id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            @method('PUT') <!-- Spoof the PUT method -->


                            <div class="card-body2">

                                {{-- First Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Agency ID Number')">
                                                <span class="text-danger">* </span></x-input-label>

                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Select Agent" name="user_agency_numbers" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_agency_numbers ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_id')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('First Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter First Name" name="users_first_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->users_first_name ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_first_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Last Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Last Name" name="users_last_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->users_last_name ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_last_name')" />
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
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_qualification ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_qualification')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Work Email Address')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Work Email Address" name="user_work_email" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_work_email ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_work_email')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Personal Email Address')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Personal Email Address" name="users_email" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->users_email ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_personal_email')" />
                                        </div>
                                    </div>

                                </div>

                                {{-- End sec Row --}}


                                {{-- third Row --}}

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
                                                            value="{{ $agency->user_dob ?? '' }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_start_date')" />
                                        </div>
                                    </div>





                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('User Role')">
                                                <span class="text-danger">*</span>
                                            </x-input-label>
                                            <select class="form-control" id="tr_category" name="role_id" autofocus>
                                                <option value="" disabled selected>Select Category</option>

                                                @foreach ($users_role as $type)
                                                    <option value="{{ $type->role_id }}"
                                                        {{ old('age_user_type', $agency->role_id ?? '') == $type->role_id ? 'selected' : '' }}>
                                                        {{ $type->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_type')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Password')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <x-text-input type="password" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Password" name="users_password" autofocus
                                                autocomplete="tr_agent_id" :value="old('users_password', $agency->users_password ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_password')" />
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


                                            <div class="col-md-12" id="dynamic_field">
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($agent as $index => $item)
                                                    @php
                                                        $rowCount = $i + 1;

                                                    @endphp

                                                    <div class="item-row row" id="row{{ $rowCount }}">
                                                        <input type="hidden" name="age_id" id="trtm_id_hidden"
                                                            value="{{ $rowCount }}" />


                                                        <div class="col-md-6 family-member-field">
                                                            <div class="form-group">
                                                                <label for="trtm_first_name">Phone Number<span
                                                                        class="text-danger">*</span></label>
                                                                <div class="d-flex">
                                                                    <input type="text" class="form-control"
                                                                        id="trtm_first_name{{ $rowCount }}"
                                                                        name="items[{{ $rowCount }}][age_user_phone_number]"
                                                                        placeholder="Enter First Name"
                                                                        value="{{ $item->age_user_phone_number }}">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6 family-member-field">
                                                            <div class="form-group">
                                                                <label for="trtm_gender">Type<span
                                                                        class="text-danger">*</span></label>
                                                                <div class="d-flex">
                                                                    <select class="form-control select2"
                                                                        style="width: 100%;"
                                                                        id="trtm_gender{{ $rowCount }}"
                                                                        name="items[{{ $rowCount }}][age_user_type]">

                                                                        <option default>Select</option>

                                                                        @foreach ($phones_type as $product)
                                                                            <option value="{{ $product->agent_phone_id }}"
                                                                                {{ old('type', $item->age_user_type ?? '') == $product->agent_phone_id ? 'selected' : '' }}>
                                                                                {{ $product->type }}</option>
                                                                        @endforeach

                                                                    </select>

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
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}

                            {{-- End  Dynamic Input Row --}}


                            {{-- Fourth Row --}}

                            <div class="row pxy-15 px-10">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Contact Person')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="tr_agent_id"
                                            placeholder="Contact Person Name" name="user_emergency_contact_person"
                                            autofocus autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_emergency_contact_person ?? '')" />

                                        <x-input-error class="mt-2" :messages="$errors->get('age_user_emergency_contact')" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Phone Number')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="text" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Emergency Phone" name="user_emergency_phone_number"
                                            autofocus autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_emergency_phone_number ?? '')" />

                                        <x-input-error class="mt-2" :messages="$errors->get('age_user_phone_number')" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('Emergency Email Address')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="email" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Emergency Email Address" name="user_emergency_email"
                                            autofocus autocomplete="tr_agent_id" :value="old('age_user_id', $agency->user_emergency_email ?? '')" />

                                        <x-input-error class="mt-2" :messages="$errors->get('age_user_emergency_email')" />
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
                                            autocomplete="tr_agent_id" :value="old('age_user_id', $agency->users_address ?? '')" />

                                        <x-input-error class="mt-2" :messages="$errors->get('age_user_address')" />
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
                                                <option value="{{ $value->id }}"
                                                    {{ old('users_country', $agency->users_country ?? '') == $value->id ? 'selected' : '' }}>
                                                    {{ $value->name }} ({{ $value->iso2 }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('users_state')" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('state')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <select id="tr_state" name="users_state" class="form-control select2"
                                            style="width: 100%;">
                                            <option>Select State</option>

                                            @foreach ($states as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ old('users_states', $agency->users_state ?? '') == $value->id ? 'selected' : '' }}>
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
                                            name="users_city" autofocus>
                                            <option value="" selected>Select City</option>

                                            @foreach ($city as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ old('users_city', $agency->users_city ?? '') == $value->id ? 'selected' : '' }}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('users_city')" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="tr_agent_id" :value="__('zip')"> <span
                                                class="text-danger">*</span></x-input-label>
                                        <x-text-input type="number" class="form-control" id="tr_agent_id"
                                            placeholder="Enter Zip" name="users_zip" :value="old('age_user_id', $agency->users_zip ?? '')" autofocus
                                            autocomplete="tr_agent_id" />

                                        <x-input-error class="mt-2" :messages="$errors->get('users_zip')" />
                                    </div>
                                </div>
                            </div>



                            {{-- End Fifth Row --}}

                            <div class="row py-20 px-10">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('trip.index') }}" class="add_btn_br px-10">Cancel</a>
                                    <button type="submit" class="add_btn px-10">Save</button>
                                </div>
                            </div>
                    </div>
                    </form>


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





                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            var fromdatepicker = document.getElementById('tr_start_date_hidden');

                            var fromdatepicker = flatpickr("#completed_date", {
                                locale: 'en',
                                altInput: true,
                                dateFormat: "m/d/Y",
                                altFormat: "d/m/Y",
                                allowInput: true,
                                defaultDate: fromdatepicker.value || null,
                            });
                            document.getElementById('completed-date-icon').addEventListener('click', function() {
                                fromdatepicker.open();
                            });

                        });
                    </script>
                @endsection
@endif

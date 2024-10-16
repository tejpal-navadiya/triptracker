@extends('masteradmin.layouts.app')
<!DOCTYPE html>


<title>Edit Agency | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
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

                        {{-- @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif --}}


                        <div class="card-header">
                            <h3 class="card-title">Edit Agency</h3>
                        </div>


                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('agency.update', $agency->age_id) }}"
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
                                                placeholder="Select Agent" name="age_user_id" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_id ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_id')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('First Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter First Name" name="age_user_first_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_first_name ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_first_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Last Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Last Name" name="age_user_last_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_last_name ?? '')" />

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
                                                placeholder="Enter Qualification" name="age_user_qualification" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_qualification ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_qualification')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Work Email Address')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Work Email Address" name="age_user_work_email" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_work_email ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_work_email')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Personal Email Address')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="email" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Personal Email Address" name="age_user_personal_email"
                                                autofocus autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_personal_email ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_personal_email')" />
                                        </div>
                                    </div>

                                </div>

                                {{-- End sec Row --}}


                                {{-- third Row --}}

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Birthdate')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="date" class="form-control" id="tr_agent_id"
                                                placeholder="Select Birthdate" name="age_user_dob" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_dob ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('age_user_dob')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('User Role')">
                                                <span class="text-danger">*</span>
                                            </x-input-label>
                                            <select class="form-control" id="tr_category" name="age_user_type" autofocus>
                                                <option value="" disabled selected>Select Category</option>

                                                @foreach ($user as $type)
                                                    <option value="{{ $type->age_user_type }}"
                                                        {{ old('age_user_type', $agency->age_user_type ?? '') == $type->age_user_type ? 'selected' : '' }}>
                                                        {{ $type->age_user_type }}
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
                                                placeholder="Enter Password" name="age_user_password" autofocus
                                                autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_password ?? '')" />

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
                    </div>

                    {{-- End  Dynamic Input Row --}}


                    {{-- Fourth Row --}}

                    <div class="row pxy-15 px-10">

                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('Emergency Contact Person')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <x-text-input type="text" class="form-control" id="tr_agent_id"
                                    placeholder="Contact Person Name" name="age_user_emergency_contact" autofocus
                                    autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_emergency_contact ?? '')" />

                                <x-input-error class="mt-2" :messages="$errors->get('age_user_emergency_contact')" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('Emergency Phone Number')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <x-text-input type="text" class="form-control" id="tr_agent_id"
                                    placeholder="Enter Emergency Phone" name="age_user_phone_number" autofocus
                                    autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_phone_number ?? '')" />

                                <x-input-error class="mt-2" :messages="$errors->get('age_user_phone_number')" />
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('Emergency Email Address')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <x-text-input type="email" class="form-control" id="tr_agent_id"
                                    placeholder="Enter Emergency Email Address" name="age_user_emergency_email" autofocus
                                    autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_emergency_email ?? '')" />

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
                                    placeholder="Select Agent" name="age_user_address" autofocus
                                    autocomplete="tr_agent_id" :value="old('age_user_id', $agency->age_user_address ?? '')" />

                                <x-input-error class="mt-2" :messages="$errors->get('age_user_address')" />
                            </div>
                        </div>

                    </div>

                    {{-- End Fourths Row --}}


                    {{-- Fifth Row --}}

                    <div class="row pxy-15 px-10">

                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('City')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <x-text-input type="date" class="form-control" id="tr_agent_id"
                                    placeholder="Enter City" name="age_user_city" autofocus autocomplete="tr_agent_id"
                                    :value="old('age_user_id', $agency->age_user_city ?? '')" />

                                <x-input-error class="mt-2" :messages="$errors->get('age_user_city')" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('state')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <select class="form-control" id="tr_category" name="age_user_state_type" autofocus>
                                    <option value="" disabled selected>Select Category</option>

                                    @foreach ($status as $state)
                                        <option value="{{ $state->age_user_state_type }}"
                                            {{ old('age_user_type', $agency->age_user_state_type ?? '') == $state->age_user_state_type ? 'selected' : '' }}>
                                            {{ $state->age_user_state_type }}
                                        </option>
                                    @endforeach

                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('age_user_state_type')" />
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_agent_id" :value="__('zip')"> <span
                                        class="text-danger">*</span></x-input-label>
                                <x-text-input type="text" class="form-control" id="tr_agent_id"
                                    placeholder="Enter Zip" name="age_user_zip" autofocus autocomplete="tr_agent_id"
                                    :value="old('age_user_id', $agency->age_user_zip ?? '')" />

                                <x-input-error class="mt-2" :messages="$errors->get('age_user_zip')" />
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
    @endsection
@endif

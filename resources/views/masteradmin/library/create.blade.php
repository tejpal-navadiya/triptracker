@extends('masteradmin.layouts.app')
<!DOCTYPE html>
<title>Add Library | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Add Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Add Library') }}</li>
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
                            <h3 class="card-title">Add Library Item</h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('library.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_name" :value="__('Category*')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <select class="form-control" id="tr_category" name="lib_category" autofocus>

                                                <option value="" disabled selected>Select Category</option>

                                                @foreach ($librarycategory as $category)
                                                    <option value="{{ $category->lib_cat_name }}">
                                                        {{ $category->lib_cat_name }}</option>
                                                @endforeach

                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Name')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Select Agent" name="lib_name" autofocus
                                                autocomplete="tr_agent_id" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_country" :value="__('Country')" />
                                            <select class="form-control select2" id="tr_country" name="lib_country"
                                                autofocus>
                                                <option value="" selected>Select Location</option>
                                                @foreach ($librarycurrency as $currency)
                                                    <option value="{{ $currency->id }}">{{ $currency->name }}
                                                        ({{ $currency->iso2 }})
                                                    </option>
                                                @endforeach
                                                <!-- Add more options as needed -->
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_country')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_currencys" :value="__('Currency')"> <span
                                                    class="text-danger">*</span></x-input-label>
                                            <select class="form-control select2"" id="tr_currency" name="lib_currency"
                                                autofocus>
                                                <option value="" selected>Select Currency</option>
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_currency')" />
                                        </div>
                                    </div>

                                    <!-- State Selection -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_state" :value="__('State')" />
                                            <select class="form-control" id="tr_state" name="lib_state" autofocus>
                                                <option value="" selected>Select State</option>
                                                <!-- States will be populated here based on the selected country -->
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('lib_state')" />
                                        </div>
                                    </div>

                                    <!-- City Selection -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="lib_city" :value="__('City')">
                                                <span class="text-danger">*</span>
                                            </x-input-label>
                                            <select class="form-control form-control select2" id="lib_city" name="lib_city"
                                                autofocus>
                                                <option value="" selected>Select City</option>
                                                <!-- Cities will be populated here based on the selected state -->
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('lib_city')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_email" :value="__('Zip')" />
                                            <input type="text" class="form-control" id="tr_zip" name="lib_zip"
                                                placeholder="Enter Zip" accept=".zip" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <x-input-label for="tr_basic_info" :value="__('Basic Information')" />
                                            <textarea class="form-control" id="tr_basic_info" name="lib_basic_information" placeholder="Enter Basic Information"></textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_basic_information')" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <x-input-label for="tr_sightseeing_info" :value="__('Sightseeing Information')" />
                                            <textarea class="form-control" id="tr_sightseeing_info" name="lib_sightseeing_information"
                                                placeholder="Enter Sightseeing Information"></textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_sightseeing_information')" />
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="lib_image" :value="__('Image Upload')" />
                                        <input type="file" class="form-control" id="lib_image" name="image[]"
                                            accept="image/*" multiple />
                                        <x-input-error class="mt-2" :messages="$errors->get('lib_image')" />
                                    </div>
                                </div>


                                <div class="col-md-12" id="dynamic_field">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
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

        <script src="{{ url('public/js/tinymce/tinymce.min.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            tinymce.init({
                selector: 'textarea', // This will apply TinyMCE to all textareas
                menubar: false,
                plugins: 'code table lists image',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
            });
        </script>



        <script>
            $(document).ready(function() {
                $('.select2').select2();
                $('#tr_country').change(function() {
                    var countryId = $(this).val();
                    // alert(countryId);
                    if (countryId) {
                        $.ajax({
                            url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/states/' +
                                countryId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#tr_state').empty();
                                $('#tr_state').append(
                                    '<option value="">Select a State...</option>');
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
                        $('#state').empty();
                        $('#state').append('<option value="">Select a State...</option>');
                    }
                });
            });
        </script>



        {{-- <script>
            $(document).ready(function() {
                $('#tr_country').change(function() {
                    var countryId = $(this).val();
                    if (countryId) {
                        $.ajax({
                            url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/currencies/' +
                                countryId,
                            type: 'GET',
                            success: function(currencies) {
                                $('#tr_currency').empty(); // Clear existing options
                                $('#tr_currency').append(
                                    '<option value="" selected>Select Currency</option>');
                                $.each(currencies, function(index, currency) {
                                    $('#tr_currency').append('<option value="' + currency
                                        .id + '">' + currency.currency + ' (' + currency
                                        .currency_symbol + ') - ' + currency
                                        .currency_name + '</option>');
                                });
                                $('#tr_currency')
                                    .select2(); // Re-initialize Select2 on the currency dropdown
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log('Error fetching currencies: ' + textStatus);
                            }
                        });
                    } else {
                        $('#tr_currency').empty(); // Clear the currency dropdown if no country is selected
                        $('#tr_currency').append('<option value="" selected>Select Currency</option>');
                    }
                });
            });
        </script> --}}


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
                // Initialize Select2 for both country and currency select elements
                $('#tr_country').select2();
                $('#tr_currency').select2();

                $('#tr_country').change(function() {
                    var countryId = $(this).val();
                    if (countryId) {
                        $.ajax({
                            url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/currencies/' +
                                countryId,
                            type: 'GET',
                            success: function(currencies) {
                                $('#tr_currency').empty(); // Clear existing options
                                $('#tr_currency').append(
                                    '<option value="" selected>Select Currency</option>');
                                $.each(currencies, function(index, currency) {
                                    $('#tr_currency').append('<option value="' + currency
                                        .id + '">' + currency.currency + ' (' +
                                        currency.currency_symbol + ') - ' + currency
                                        .currency_name + '</option>');
                                });

                                // Re-initialize Select2 after populating options
                                $('#tr_currency').select2({
                                    width: '100%' // Ensure it takes full width
                                });
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log('Error fetching currencies: ' + textStatus);
                            }
                        });
                    } else {
                        $('#tr_currency').empty(); // Clear the currency dropdown if no country is selected
                        $('#tr_currency').append('<option value="" selected>Select Currency</option>');

                        // Re-initialize Select2 after clearing options
                        $('#tr_currency').select2({
                            width: '100%'
                        });
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

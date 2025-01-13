@extends('masteradmin.layouts.app')
<!DOCTYPE html>
<title>Add Library | Trip Tracker</title>
@if (isset($access['add_library']) && $access['add_library'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                        <div class="d-flex">    
                            <h1 class="m-0">{{ __('Add Library Item') }}</h1>
                            <ol class="breadcrumb ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Add Library Item') }}</li>
                            </ol>
                            </div>
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
                        <form id="libraryForm" method="POST" action="{{ route('library.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_name">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="tr_category" name="lib_category" autofocus>
                                                <option value="" disabled {{ old('lib_category') ? '' : 'selected' }}>
                                                    Select Category</option>
                                                @foreach ($librarycategory as $category)
                                                    <option value="{{ $category->lib_cat_id }}"
                                                        {{ old('lib_category') === $category->lib_cat_name ? 'selected' : '' }}>
                                                        {{ $category->lib_cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_category')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lib_name">Name <span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="lib_name"
                                                placeholder="Enter Name" name="lib_name" autofocus autocomplete="lib_name"
                                                value="{{ old('lib_name') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_name')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tag_name">Tag<span class="text-danger"></span></label>
                                            <x-text-input type="text" class="form-control" id="tag_name"
                                                placeholder="Enter Tag" name="tag_name" autofocus autocomplete="tag_name"
                                                value="{{ old('tag_name') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tag_name')" />
                                        </div>
                                    </div>


                                </div>
                            </div>




                            <div class="row pxy-15 px-10">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-input-label for="tr_basic_info" :value="__('Basic Information')" />
                                        <textarea class="form-control" id="tr_basic_info" name="lib_basic_information" placeholder="Enter Basic Information">{{ old('lib_basic_information') }}</textarea>
                                        {{-- <x-input-error class="mt-2" :messages="$errors->get('lib_basic_information')" /> --}}
                                    </div>
                                </div>


                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="lib_image" :value="__('Image Upload')" />

                                    <input type="file" class="form-control" id="lib_image" name="lib_image[]" multiple />
                                    <x-input-error class="mt-2" :messages="$errors->get('lib_image')" />
                                    <label for="trvd_document">Only jpg, jpeg, png, and pdf files are allowed</label>

                                </div>
                            </div>





                            {{-- <div class="col-md-12" id="dynamic_field">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div> --}}

                    </div>

                    <div class="row py-20 px-10">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('library.index') }}" class="add_btn_br px-10">Cancel</a>
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

        <script src="{{ url('public/js/tinymce/tinymce.min.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <script>
            document.getElementById('libraryForm').addEventListener('submit', function(e) {
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

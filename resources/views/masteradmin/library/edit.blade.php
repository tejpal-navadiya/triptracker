@extends('masteradmin.layouts.app')
<!DOCTYPE html>
<title>Edit Library | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Edit Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Edit Library') }}</li>
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
                            <h3 class="card-title">Edit Library Item</h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('library.update',$library->lib_id) }}" enctype="multipart/form-data">
                            @csrf

                            @method('PUT') <!-- Spoof the PUT method -->

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_category" :value="__('Category*')" />
                                            <select class="form-control" id="tr_category" name="lib_category" autofocus>
                                                <option value="" disabled>Select Category</option>
                                    
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->lib_cat_id }}" {{ old('lib_category', $library->lib_category) == $category->lib_cat_id ? 'selected' : '' }}>
                                                        {{ $category->lib_cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_category')" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_agent_id" :value="__('Name')"> <span
                                                    class="text-danger">*</span></x-input-label>

                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Select Agent" name="lib_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('tr_name', $library->lib_name ?? '')" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_dob" :value="__('Country')" />
                                            <select class="form-control select2" id="tr_category" name="lib_country" autofocus>
                                                <option value="" disabled selected>Select Location</option>

                                                @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('lib_country', $library->lib_country ?? '') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }} ({{ $country->iso2 }})
                                                </option>
                                            @endforeach 
                                                <!-- Add more options as needed -->
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('tr_dob')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_currency" :value="__('Currency*')" />
                                            <select class="form-control select2" id="tr_currency" name="lib_currency" autofocus>
                                                <option value="" disabled>Select Currency</option>
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->id }}" {{ old('lib_currency', $library->lib_currency) == $currency->id ? 'selected' : '' }}>
                                                        {{ $currency->name }} ({{ $currency->currency_symbol }}) ({{ $currency->iso2 }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_currency')" />
                                        </div>
                                    </div>
                                    


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_age" :value="__('State')" />
                                            <select class="form-control select2" id="tr_state" name="lib_state" autofocus>
                                                <option value="" disabled selected>Select State</option>

                                                  @foreach ($states as $state)
                                                   <option value="{{ $state->id }}"
                                                     {{ old('lib_state', $library->lib_state ?? '') == $state->id ? 'selected' : '' }}>
                                                     {{ $state->name }} ({{ $state->iso2 }})
                                                   </option>
                                                @endforeach 
                                                <!-- Add more options as needed -->
                                            </select>
                                        </div>
                                    </div>


                                    {{-- index --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="lib_city" :value="__('City')"> <span class="text-danger">*</span></x-input-label>
                                            <select class="form-control form-control select2" id="lib_city" name="lib_city" autofocus>
                                                <option value="" selected>Select City</option>
                                                
                                                 @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('lib_city', $library->lib_city) == $city->id ? 'selected' : '' }}>
                                                   {{ $city->name }}
                                              </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_city')" />
                                        </div>
                                    </div>
                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <x-input-label for="tr_email" :value="__('Zip')" />
                                            <x-text-input type="text" class="form-control" id="tr_zip"
                                                placeholder="Enter Zip" name="lib_zip" autofocus autocomplete="tr_agent_id"
                                                :value="old('tr_name', $library->lib_zip ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Basic Information')" />
                                            <textarea class="form-control" id="tr_phone" name="lib_basic_information" placeholder="Enter Basic Information"
                                                autofocus>
                                             {{$library->lib_basic_information}}
                                            </textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Sightseeing Information')" />
                                            <textarea class="form-control" id="tr_num_people" name="lib_sightseeing_information"
                                                placeholder="Enter Sightseeing Information">
                                                {{$library->lib_sightseeing_information}}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-input-label for="lib_image" :value="__('Image Upload')" />
                                        
                                        <!-- File input for uploading an image -->
                                        <input type="file" class="form-control" id="lib_image" name="image" accept="image/*" />
                                        
                                        <!-- Display the uploaded image if it exists -->
                                        @if ($library->lib_image)
                                        <div class="mt-2">
                                            <p>Image URL: {{ asset('storage/masteradmin/library_image/' . $library->lib_image) }}</p>
                                            <img src="{{ url('storage/masteradmin/library_image/' . $library->lib_image) }}" alt="Uploaded Image" class="img-thumbnail" style="max-width: 100%; height: auto;">
                                        </div>
                                    @endif
                                    
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


<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    tinymce.init({
        selector: 'textarea', // This will apply TinyMCE to all textareas
        menubar: false,
        plugins: 'code table lists image',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
    });
    tinymce.init({
                selector: 'textarea', // Change this selector based on your needs
                menubar: false,
                plugins: 'lists link image table',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table',
            });
</script>


<script>
   $(document).ready(function() {
    $('#tr_country').change(function() {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/currencies/' + countryId,
                type: 'GET',
                success: function(currencies) {
                    $('#tr_currency').empty();
                    $('#tr_currency').append('<option value="" selected>Select Currency</option>');
                    $.each(currencies, function(index, currency) {
                        $('#tr_currency').append('<option value="' + currency.id + '">' + currency.currency + ' (' + currency.currency_symbol + ') - ' + currency.currency_name + '</option>');

                    });
                    $('#tr_currency').select2(); // Re-initialize Select2
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error fetching currencies: ' + textStatus);
                }
            });
            
            // Also load states related to the selected country if necessary
            $.ajax({
                url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/states/' + countryId,
                type: 'GET',
                success: function(states) {
                    $('#tr_state').empty();
                    $('#tr_state').append('<option value="" selected>Select State</option>');
                    $.each(states, function(index, state) {
                        $('#tr_state').append('<option value="' + state.id + '">' + state.name + '</option>');
                    });
                    $('#tr_state').select2(); // Re-initialize Select2
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error fetching states: ' + textStatus);
                }
            });
        } else {
            $('#tr_currency').empty();
            $('#tr_currency').append('<option value="" selected>Select Currency</option>');
            $('#tr_state').empty();
            $('#tr_state').append('<option value="" selected>Select State</option>');
            $('#lib_city').empty();
            $('#lib_city').append('<option value="" selected>Select City</option>');
        }
    });

    // Load cities when the state changes
    $('#tr_state').change(function() {
        var stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url: '{{ env('APP_URL') }}{{ config('global.businessAdminURL') }}/cities/' + stateId,
                type: 'GET',
                success: function(cities) {
                    $('#lib_city').empty();
                    $('#lib_city').append('<option value="" selected>Select City</option>');
                    $.each(cities, function(index, city) {
                        $('#lib_city').append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                    $('#lib_city').select2(); // Re-initialize Select2
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error fetching cities: ' + textStatus);
                }
            });
        } else {
            $('#lib_city').empty();
            $('#lib_city').append('<option value="" selected>Select City</option>');
        }
    });
});

</script>
    @endsection
@endif

@extends('masteradmin.layouts.app')
<title>Add Library | Trip Tracker</title>
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
                <form method="POST" action="{{ route('trip.store') }}">
                    @csrf
                    <div class="card-body2">
                        <div class="row pxy-15 px-10">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_name" :value="__('Category*')"> <span
                                            class="text-danger">*</span></x-input-label>

                                    <select class="form-control" id="tr_category" name="tr_category" autofocus>
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="Business">Business</option>
                                        <option value="Leisure">Leisure</option>
                                        <option value="Adventure">Adventure</option>
                                        <option value="Educational">Educational</option>
                                        <!-- Add more options as needed -->
                                    </select>

                                    <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_agent_id" :value="__('Name')"> <span
                                            class="text-danger">*</span></x-input-label>
                                    <x-text-input type="text" class="form-control" id="tr_agent_id"
                                        placeholder="Select Agent" name="tr_ag" autofocus autocomplete="tr_agent_id" />

                                    <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                </div>

                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_traveler_name" :value="__('Currency')"> <span
                                            class="text-danger">*</span></x-input-label>
                                    <select class="form-control" id="tr_category" name="tr_category" autofocus>
                                        <option value="" disabled selected>Select Currency</option>
                                        <option value="Business">Business</option>
                                        <option value="Leisure">Leisure</option>
                                        <option value="Adventure">Adventure</option>
                                        <option value="Educational">Educational</option>
                                        <!-- Add more options as needed -->
                                    </select>

                                    <x-input-error class="mt-2" :messages="$errors->get('tr_traveler_name')" />
                                </div>
                            </div>
                        </div>

                        <div class="row pxy-15 px-10">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_dob" :value="__('Country')" />
                                    <select class="form-control" id="tr_category" name="tr_category" autofocus>
                                        <option value="" disabled selected>Select Location</option>
                                        <option value="Business">Business</option>
                                        <option value="Leisure">Leisure</option>
                                        <option value="Adventure">Adventure</option>
                                        <option value="Educational">Educational</option>
                                        <!-- Add more options as needed -->
                                    </select>

                                    <x-input-error class="mt-2" :messages="$errors->get('tr_dob')" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_age" :value="__('State')" />
                                    <select class="form-control" id="tr_category" name="tr_category" autofocus>
                                        <option value="" disabled selected>Select State</option>
                                        <option value="Business">Business</option>
                                        <option value="Leisure">Leisure</option>
                                        <option value="Adventure">Adventure</option>
                                        <option value="Educational">Educational</option>
                                        <!-- Add more options as needed -->
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_number" :value="__('City')" />
                                    <x-text-input type="number" class="form-control" id="tr_number"
                                        placeholder="Enter Trip Number" name="tr_number" autofocus
                                        autocomplete="tr_number" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_number')" />
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_email" :value="__('Zip')" />
                                    <input type="file" class="form-control" id="tr_zip" name="tr_zip" accept=".zip" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
                                </div>
                            </div>
                        </div>


                        <div class="row pxy-15 px-10">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_phone" :value="__('Basic Information')" />
                                    <textarea class="form-control" id="tr_phone" name="tr_phone"
                                        placeholder="Enter Basic Information" autofocus></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label for="tr_num_people" :value="__('Sightseeing Information')" />
                                    <textarea class="form-control" id="tr_num_people" name="tr_num_people"
                                        placeholder="Enter Sightseeing Information" readonly></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />
                                </div>
                            </div>
                        </div>




                        <div class="col-md-4">
                            <div class="form-group">
                                <x-input-label for="tr_image" :value="__('Image Upload')" />
                                <input type="file" class="form-control" id="tr_image" name="tr_image"
                                    accept="image/*" />
                                <x-input-error class="mt-2" :messages="$errors->get('tr_image')" />
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
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


<script>
CKEDITOR.replace('tr_phone'); // Initialize CKEditor for Basic Information
CKEDITOR.replace('tr_num_people'); // Initialize CKEditor for Sightseeing Information
</script>


<script>
tinymce.init({
    selector: 'textarea', // Change this selector based on your needs
    menubar: false,
    plugins: 'lists link image table',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table',
});
</script>


<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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


    var birthdatedate = flatpickr("#birthdate_date", {
        locale: 'en',
        altInput: true,
        dateFormat: "m/d/Y",
        altFormat: "d/m/Y",
        allowInput: true,
    });

    document.getElementById('birthdate-hidden-icon').addEventListener('click', function() {
        birthdatedate.open();
    });

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
      <i class="fa fa-trash delete-item" id="${rowCount}"> Remove</i>
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
@endsection
@endif
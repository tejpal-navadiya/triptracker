@extends('masteradmin.layouts.app')
<title>Add Trip | Trip Tracker</title>
@if(isset($access['book_trip']) && $access['book_trip'])
  @section('content')
  <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center justify-content-between">
      <div class="col-auto">
        <h1 class="m-0">{{ __("Add Trip") }}</h1>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{ __("Add Trip") }}</li>
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
        <h3 class="card-title">Add Trip</h3>
      </div>
      <!-- /.card-header -->
      <form method="POST" action="{{ route('trip.store') }}">
        @csrf
        <div class="card-body2">
        <div class="row pxy-15 px-10">
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_name" :value="__('Name of Trip')"> <span
              class="text-danger">*</span></x-input-label>
            <x-text-input type="text" class="form-control" id="tr_name" placeholder="Enter Name of Trip"
            name="tr_name" required autofocus autocomplete="tr_name" />

            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_agent_id" :value="__('Agent Name')"> <span
              class="text-danger">*</span></x-input-label>
            <x-text-input type="text" class="form-control" id="tr_agent_id" placeholder="Select Agent"
            name="tr_agent_id" required autofocus autocomplete="tr_agent_id" />

            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_traveler_name" :value="__('Traveler Name')"> <span
              class="text-danger">*</span></x-input-label>
            <x-text-input type="text" class="form-control" id="tr_traveler_name" placeholder="Traveler Name"
            name="tr_traveler_name" required autofocus autocomplete="tr_traveler_name" />

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
              <input type="hidden" id="birthdate_hidden" value="" />
              </div>
            </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('tr_dob')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_age" :value="__('Age')" />
            <x-text-input type="text" class="form-control" id="tr_age" placeholder="Enter Age" name="tr_age"
            required autofocus autocomplete="users_cert_name" readonly />
            <x-input-error class="mt-2" :messages="$errors->get('tr_age')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_number" :value="__('Trip Number')" />
            <x-text-input type="text" class="form-control" id="tr_number" placeholder="Enter Trip Number"
            name="tr_number" required autofocus autocomplete="tr_number" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_number')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_email" :value="__('Email Address')" />
            <x-text-input type="email" class="form-control" id="tr_email" placeholder="Enter Email Address"
            name="tr_email" required autofocus autocomplete="tr_email" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_phone" :value="__('Phone Number')" />
            <x-text-input type="text" class="form-control" id="tr_phone" placeholder="Enter Phone Number"
            name="tr_phone" required autofocus autocomplete="tr_phone" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_num_people" :value="__('Number of People')" />
            <x-text-input type="text" class="form-control" id="tr_num_people" placeholder="Enter Number of People"
            name="tr_num_people" required autofocus autocomplete="tr_num_people" readonly />
            <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />
          </div>
          </div>

          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_start_date" :value="__('Start Date')" />
            <div class="input-group date" id="tr_start_date" data-target-input="nearest">

            <x-flatpickr id="completed_date" name="tr_start_date" placeholder="mm/dd/yyyy" />
            <div class="input-group-append">
              <div class="input-group-text" id="completed-date-icon">
              <i class="fa fa-calendar-alt"></i>
              <input type="hidden" id="tr_start_date_hidden" value="" />
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

            <x-flatpickr id="expiration_date" name="tr_end_date" placeholder="mm/dd/yyyy" />
            <div class="input-group-append">
              <div class="input-group-text" id="expiration-date-icon">
              <i class="fa fa-calendar-alt"></i>
              <input type="hidden" id="tr_end_date_hidden" value="" />
              </div>
            </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('tr_end_date')" />
          </div>
          </div>

          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_value_trip" :value="__('Value of trip')" />
            <x-text-input type="text" class="form-control" id="tr_value_trip" placeholder="Enter Value of trip"
            name="tr_value_trip" required autofocus autocomplete="tr_value_trip" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_value_trip')" />
          </div>
          </div>

          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_desc" :value="__('Description')" />
            <textarea type="text" class="form-control" id="tr_desc" placeholder="Enter Description"
            name="tr_desc" required autofocus autocomplete="tr_desc"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('tr_desc')" />
          </div>
          </div>

          <div class="col-md-12">
          <div class="form-group">
            <x-input-label for="tr_type_trip" :value="__('Type of Trip')" />
            <div>
            @foreach ($triptype as $value)
              <input class="checkbox-inputbox" type="checkbox" id="{{$value->ty_id}}" name="tr_type_trip[]" value="{{ $value->ty_name }}">
              <label for="{{ $value->ty_id }}">{{ $value->ty_name }}</label>
            @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('tr_type_trip')" />
          </div>
          </div>

          <div class="col-md-12">
          <button type="button" id="add" class="add_tripmembertbtn"><i class="fas fa-plus add_plus_icon"></i>Add
            Traveling Member</button>
          </div>
          <div class="col-md-12" id="dynamic_field">

          </div>

        </div>

        <div class="row py-20 px-10">
          <div class="col-md-12 text-center">
          <a href="{{route('trip.index')}}" class="add_btn_br px-10">Cancel</a>
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
    document.addEventListener('DOMContentLoaded', function () {

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

    document.getElementById('completed-date-icon').addEventListener('click', function () {
      fromdatepicker.open();
    });

    document.getElementById('expiration-date-icon').addEventListener('click', function () {
      todatepicker.open();
    });


    var birthdatedate = flatpickr("#birthdate_date", {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "d/m/Y",
      allowInput: true,
    });

    document.getElementById('birthdate-hidden-icon').addEventListener('click', function () {
      birthdatedate.open();
    });

    });

  </script>

  <script>

    $(document).ready(function () {
      var  rowCount = 0;

    $('#add').click(function () {
      // alert('add');
      rowCount++;
      $('#dynamic_field').append(`
     <div class="item-row row" id="row${rowCount}">
      <div class="col-md-3">
      <div class="form-group">
        <label for="trtm_name">Lead Traveler</label>
        <div class="d-flex">
         <input type="text" class="form-control" id="trtm_name" name="items[][trtm_name]" placeholder="Enter Lead Traveler">
        </div>
      </div>
      </div>

      <div class="col-md-3">
      <div class="form-group">
        <label for="trtm_dob">Birthdate</label>
        <div class="d-flex">
         <div class="input-group date" id="trtm_dob" data-target-input="nearest">
            <x-flatpickr id="traveler_date_${rowCount}" name="items[][trtm_dob]" placeholder="mm/dd/yyyy" />
            <div class="input-group-append">
            <div class="input-group-text" id="traveler-date-icon_${rowCount}">
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
        <input type="text" name="items[][trtm_age]" class="form-control" aria-describedby="inputGroupPrepend" placeholder="Enter Age" id="trtm_age_${rowCount}" readonly>
        </div>
      </div>
      </div>
      <div class="col-md-3">
      <i class="fa fa-trash delete-item" id="${rowCount}"> Remove</i>
      </div>

    </div>
    `);

      var numofpeople = document.querySelector('#tr_num_people');
      numofpeople.value = rowCount;

      var travelerdate = flatpickr(`#traveler_date_${rowCount}`, {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "d/m/Y",
      allowInput: true,
      });

      document.getElementById(`traveler-date-icon_${rowCount}`).addEventListener('click', function () {
      // alert('jhk');
      travelerdate.open();
      });


      var birthdateInput = document.querySelector(`#traveler_date_${rowCount}`);
      var ageInput = document.querySelector(`#trtm_age_${rowCount}`);

      birthdateInput.addEventListener('change', function () {
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


    $(document).on('click', '.delete-item', function () {
      var rowId = $(this).attr("id");
      $('#row' + rowId).remove();
      
      rowCount--;
      $('#tr_num_people').val(rowCount);
    });

    var birthdateInput = document.querySelector('#birthdate_date');
    var ageInput = document.querySelector('#tr_age');

    birthdateInput.addEventListener('change', function () {
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
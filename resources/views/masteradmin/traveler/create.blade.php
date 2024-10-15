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
        <h1 class="m-0">{{ __("Add Traveler") }}</h1>
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
        <h3 class="card-title">Add Traveler</h3>
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
            name="tr_name"  autofocus autocomplete="tr_name" />

            <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_agent_id" :value="__('Agent Name')"> <span
              class="text-danger">*</span></x-input-label>
            <x-text-input type="text" class="form-control" id="tr_agent_id" placeholder="Select Agent"
            name="tr_agent_id"  autofocus autocomplete="tr_agent_id" />

            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_traveler_name" :value="__('Traveler Name')"> <span
              class="text-danger">*</span></x-input-label>
            <x-text-input type="text" class="form-control" id="tr_traveler_name" placeholder="Traveler Name"
            name="tr_traveler_name"  autofocus autocomplete="tr_traveler_name" />

            <x-input-error class="mt-2" :messages="$errors->get('tr_traveler_name')" />
          </div>
          </div>
        </div>
        <div class="row pxy-15 px-10">
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
                    <x-input-label for="tr_num_people" :value="__('Number of People')" />
                    <x-text-input type="text" class="form-control" id="tr_num_people" placeholder="Enter Number of People"
                    name="tr_num_people"  autofocus autocomplete="tr_num_people" readonly />
                    <x-input-error class="mt-2" :messages="$errors->get('tr_num_people')" />
                </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_email" :value="__('Email Address')" />
            <x-text-input type="email" class="form-control" id="tr_email" placeholder="Enter Email Address"
            name="tr_email"  autofocus autocomplete="tr_email" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_email')" />
          </div>
          </div>
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_phone" :value="__('Phone Number')" />
            <x-text-input type="text" class="form-control" id="tr_phone" placeholder="Enter Phone Number"
            name="tr_phone"  autofocus autocomplete="tr_phone" />
            <x-input-error class="mt-2" :messages="$errors->get('tr_phone')" />
          </div>
          </div>
          
          <div class="col-md-4">
          <div class="form-group">
            <x-input-label for="tr_desc" :value="__('Description')" />
            <textarea type="text" class="form-control" id="tr_desc" placeholder="Enter Description"
            name="tr_desc"  autofocus autocomplete="tr_desc"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('tr_desc')" />
          </div>
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


    });

  </script>

  @endsection
@endif
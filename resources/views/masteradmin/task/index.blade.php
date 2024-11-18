@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
  @section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="m-0">Trip Detail</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Trip Information</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-auto"><button href="javascript:void(0)" id="createNewTask" class="reminder_btn">Add
                    Task</button></div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
    
        <div class="col-lg-12 fillter_box">
          <div class="row align-items-center justify-content-between">
              <div class="col-auto">
                  <p class="m-0 filter-text"><i class="fas fa-solid fa-filter"></i>Filters</p>
              </div><!-- /.col -->
              <div class="col-auto">
                  <p class="m-0 float-sm-right filter-text">Clear Filters<i class="fas fa-regular fa-circle-xmark"></i></p>
              </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="row">
              <div class="col-lg-3 col-1024 col-md-6 px-10">
                  <select id="trip_agent" class="form-control select2" style="width: 100%;" name="trip_agent">
                      <option value="" default >Choose Agent</option>
                      @foreach($agency_user as $value)
                      <option value="{{ $value->users_id }}">
                          {{ $value->users_first_name }} {{ $value->users_last_name }}
                      </option>
                      @endforeach
                  </select>
              </div>

              <div class="col-lg-3 col-1024 col-md-6 px-10">
                  <select id="trip_traveler" class="form-control select2" style="width: 100%;" name="trip_traveler">
                      <option value="" default >Choose Traveler</option>
                      @foreach($trip as $value)
                      <option value="{{ $value->tr_traveler_name }}">
                          {{ $value->tr_traveler_name }}
                      </option>
                      @endforeach
                  </select>
              </div>
       
              </div>
        </div>
        <div class="card-header d-flex p-0 justify-content-center tab_panal">
          <ul class="nav nav-pills p-2 tab_box">
            <li class="nav-item"><a class="nav-link active" href="#Traveleroverview" data-toggle="tab">View All Task</a></li>
            @if(isset($access['reminder_all_task']) && $access['reminder_all_task'])
            <li class="nav-item"><a class="nav-link" href="#Agentinfo" data-toggle="tab">View All Reminder Task</a></li>
            @endif
          </ul>
        </div><!-- /.card-header -->
          <div class="tab-content px-20">
            <div class="tab-pane active" id="Traveleroverview">
                @include('masteradmin.task.all-information')
            </div>
            <!-- /.tab-pane -->
            @if(isset($access['reminder_all_task']) && $access['reminder_all_task'])
            <div class="tab-pane" id="Agentinfo">
              @include('masteradmin.task.reminder-information')
            </div>
            @endif
          <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

  @endsection

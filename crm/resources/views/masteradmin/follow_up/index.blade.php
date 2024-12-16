@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if (isset($access['follow_up']) && $access['follow_up'])
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="m-0">Trip Follow Up (After Travel)</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">Trip Follow Up (After Travel)</li>
                    </ol>
                </div><!-- /.col -->
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
                        <p class="m-0 float-sm-right filter-text">Clear Filters<i
                                class="fas fa-regular fa-circle-xmark"></i></p>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="row">
                    <div class="col-lg-3 col-1024 col-md-6 px-10">
                        <select id="trip_agent" class="form-control select2" style="width: 100%;" name="trip_agent">
                            <option value="" default>Choose Agent</option>
                            @foreach ($agency as $value)
                                <option value="{{ $value->users_id }}">
                                    {{ $value->users_first_name }} {{ $value->users_last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-1024 col-md-6 px-10">
                        <select id="trip_traveler" class="form-control select2" style="width: 100%;"
                            name="trip_traveler">
                            <option value="" default>Choose Traveler</option>
                            @foreach ($traveller as $value)
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
                    <li class="nav-item">
                        <a class="nav-link active" href="#inprocessTrip" data-toggle="tab" data-tab="pending">In Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">Complete</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="tab-content px-20">
                <div class="tab-pane active" id="inprocessTrip">
                    @include('masteradmin.follow_up.pending-information')
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="completeTrip">
                    @include('masteradmin.follow_up.complete-information')
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
   
    // Initialize DataTables for Pending Trips
    let pendingDataTable, completedDatatable;

    setTimeout(function() {
        pendingDataTable = $('#pendingDataTable').DataTable({
            processing: true,
            serverSide: true,
            timeout: 10000,
            ajax: {
                url: "{{ route('masteradmin.trip.follow_up_trip') }}",
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val()
    },
                xhrFields: {
        withCredentials: true
    },
                error: function(xhr, status, error) {
                    // Handle unauthorized error (status 401)
                    if (xhr.status === 401) {
                        // Show a message to the user
                        alert('Session expired or unauthorized access. Please log in again.');
                    } else {
                        console.error('Error:', error);
                    }
                }
            },
            columns: [
                { data: 'tr_name', name: 'tr_name' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'tr_traveler_name', name: 'tr_traveler_name' },
                { data: 'tr_start_date', name: 'tr_start_date' },
                { data: 'task_status_name', name: 'task_status_name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }, 4000);

    setTimeout(function() {
        completedDatatable = $('#completedDatatable').DataTable({
            processing: true,
            serverSide: true,
            timeout: 20000,
            ajax: {
                url: "{{ route('masteradmin.trip.follow_up_complete_trip') }}",
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val()
    },
                xhrFields: {
                    withCredentials: true
                },
                error: function(xhr, status, error) {
                    // Handle unauthorized error (status 401)
                    if (xhr.status === 401) {
                        // Show a message to the user
                        alert('Session expired or unauthorized access. Please log in again.');
                    } else {
                        console.error('Error:', error);
                    }
                }
            },
            columns: [
                { data: 'tr_name', name: 'tr_name' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'tr_traveler_name', name: 'tr_traveler_name' },
                { data: 'trip_date', name: 'trip_date' },
                { data: 'complete_days', name: 'complete_days' },
                { data: 'task_status_name', name: 'task_status_name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }, 2000);

    // Handle filter changes and reload tables
    $('#trip_agent, #trip_traveler').on('change', function() {
        // $('#pendingDataTable').DataTable().ajax.reload();
        $('#completedDatatable').DataTable().ajax.reload();
    });

    // Clear filters and reload tables
    $('.filter-text').on('click', function() {
        $('#trip_agent, #trip_traveler').val('').trigger('change');
        $('#pendingDataTable').DataTable().ajax.reload();
        $('#completedDatatable').DataTable().ajax.reload();
    });
});

</script>

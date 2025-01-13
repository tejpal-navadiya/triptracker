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
                <div class="col">
                    <div class="d-flex">    
                        <h1 class="m-0">{{ __('Trip Follow Up (After Travel)') }}</h1>
                        <ol class="breadcrumb ml-auto">
                            <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                            <li class="breadcrumb-item active">{{ __('Trip Follow Up (After Travel)') }}</li>
                        </ol>
                    </div>
                <div class="col-auto">
                    <ol class="breadcrumb float-sm-right">
                        
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->

            
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content ">
        <div class="container-fluid">

            <div class="col-lg-12 fillter_box new_fillter_box1">
                <div class="row align-items-center justify-content-between d-none">
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
                                <option value="{{ $value->trtm_id }}">
                                    {{ $value->trtm_first_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 input-group date">
                    <input type="text" class="form-control" id="tr_number" name="tr_number"
                        placeholder="Trip Number" autocomplete="off" />
                        <input type="hidden" id="tr_id" name="tr_id">
                        <div id="autocomplete-list" class="list-group position-absolute" style="z-index: 1000;"></div>
                </div>
                </div>
              
            </div>
            <div class="card-header d-flex p-0 justify-content-center tab_panal">
                <ul class="nav nav-pills p-2 tab_box tab_box12">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inprocessTrip" data-toggle="tab" data-tab="pending">In Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">Complete</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="tab-content tab-content12">
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
        let pendingDataTable, completedDatatable;

        const $input = $("#tr_number");
        const $list = $("#autocomplete-list");
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let typingTimeout;  // Declare a variable to store the timeout I

        const $trIdInput = $("#tr_id");
        $input.on("input", function () {
        const query = $(this).val();

        if (query.length < 2) {
            $list.empty(); // Clear the list if the query is too short
            return;
        }
        // Clear any existing timeout to prevent multiple AJAX requests
        clearTimeout(typingTimeout);
        // Set a new timeout for 5 seconds (5000ms)
        typingTimeout = setTimeout(function () {
            $.ajax({
                url: "{{ route('trip.number.autocomplete') }}", // Use named route
                method: "GET",
                data: { query: query },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // Send the CSRF token in the header
                },
                success: function (data) {
                    $list.empty(); // Clear previous suggestions
                    if (data.length > 0) {
                        // Display matching results
                        data.forEach(function (traveler) {
                            const $item = $("<div>")
                                .addClass("list-group-item")
                                .text(traveler.tr_number)
                                .on("click", function () {
                                    // Set input values and other fields on click
                                    $input.val(traveler.tr_number);
                                    $trIdInput.val(traveler.tr_id);
                                    $list.empty(); // Clear suggestions
                                    pendingDataTable1();
                                    completedDatatable1();
                                });
                            $list.append($item); // Append the item to the list
                        });
                    } else {
                        // No results found, display "Add Item" button
                        const $addButton = $("<div>")
                            .addClass("list-group-item text-primary")
                            .text(`Not found Trip Number`);
                        $list.append($addButton);
                    }
                },
                error: function () {
                    console.error("Error fetching trip number");
                }
            });
        }, 1500); 
        });
        
    // Initialize DataTables for Pending Trips
    function pendingDataTable1() { pendingDataTable.ajax.reload(); }

    function completedDatatable1() { completedDatatable.ajax.reload(); }


    setTimeout(function() {
        pendingDataTable = $('#pendingDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.trip.follow_up_trip') }}",
                type: 'GET',
                data: function (d) {
                    d.trip_agent = $('#trip_agent').val(); // Fetch current filter values
                    d.trip_traveler = $('#trip_traveler').val();
                    d.tr_number = $('#tr_number').val();
                }
               
            },
            columns: [
                { data: 'tr_name', name: 'tr_name' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'trtm_first_name', name: 'trtm_first_name' },
                { data: 'tr_number', name: 'tr_number' },
                { data: 'tr_start_date', name: 'tr_start_date' },
                { data: 'task_status_name', name: 'task_status_name', orderable: false, searchable: false },
                { data: 'action',  name:'action', orderable: false, searchable: false }
            ]
        });
    }, 4000);

    setTimeout(function() {
        completedDatatable = $('#completedDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.trip.follow_up_complete_trip') }}",
                type: 'GET',
                data: function (d) {
                    d.trip_agent = $('#trip_agent').val(); // Fetch current filter values
                    d.trip_traveler = $('#trip_traveler').val();
                    d.tr_number = $('#tr_number').val();
                }
            },
            columns: [
                { data: 'tr_name', name: 'tr_name' },
                { data: 'agent_name', name: 'agent_name' },
                { data: 'trtm_first_name', name: 'trtm_first_name' },
                { data: 'tr_number', name: 'tr_number' },
                { data: 'trip_date', name: 'trip_date' },
                { data: 'complete_days', name: 'complete_days' },
                { data: 'task_status_name', name: 'task_status_name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
    }, 2000);

    // Handle filter changes and reload tables
    $('#trip_agent, #trip_traveler').on('change', function () {
            completedDatatable.ajax.reload(); // Reload the DataTable when filters are changed
            pendingDataTable.ajax.reload();
    });

    // Clear filters and reload tables
    $('.filter-text').on('click', function() {
        $('#trip_agent, #trip_traveler, #tr_number').val('').trigger('change');
        $('#pendingDataTable').DataTable().ajax.reload();
        $('#completedDatatable').DataTable().ajax.reload();
        pendingDataTable1();
        completedDatatable1();


    });
});

</script>

<style>

#autocomplete-list {
    margin-top: 47px;
    width: 100%;

    max-height: 200px;

    overflow-y: auto;

    border: 1px solid #ddd;

    background-color: white;

    display: block;

}

.list-group-item {

    padding: 10px;

    cursor: pointer;

}

.list-group-item:hover {

    background-color: #f8f9fa;

}

.text-muted {

    color: #6c757d;

}



</style>

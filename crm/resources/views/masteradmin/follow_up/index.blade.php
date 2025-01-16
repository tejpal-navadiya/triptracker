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
                <div class="row align-items-center justify-content-between ">
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
                <div class="col-lg-3 d-flex justify-content-end align-items-center">
                    <a id="listViewBtn" href="#list-info" class="btn btn-outline-secondary custom-margin me-2">
                        <i class="fas fa-list"></i>
                    </a>
                    <a id="gridViewBtn" href="#grid-info" class="btn btn-primary active ml-2">
                        <i class="fas fa-th-large"></i>
                    </a>
                </div>
                </div>
              
            </div>
            <div id="viewContainer">
                <div class="card-header d-flex p-0 justify-content-center tab_panal">
                    <ul class="nav nav-pills p-2 tab_box tab_box12">
                        <li class="nav-item">
                            <a class="nav-link active" href="#inprocessTrip" data-toggle="tab" data-tab="pending">Trip Traveling</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">Welcome Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">6 month Review</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#completeTrip" data-toggle="tab" data-tab="complete">1 year Review</a>
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

        if (query === "") {
            pendingDataTable1(); // Fetch all data if the input is cleared
            completedDatatable1(); // Fetch additional data if the input is cleared
        }

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
                                    fetchFilteredData();
                                    fetchFilteredData1();
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
        

        var trip_agent = "";
        var trip_traveler = "";




        $('#trip_agent').val(trip_agent);

        $('#trip_traveler').val(trip_traveler);
      
        $('.filter-text').on('click', function(e) {
            e.preventDefault();
            clearFilters();
        });


        function fetchFilteredData() {
            // var sdate = $('#from-datepicker').val(defaultStartDate);
            // alert(sdate);
            var formData = {
                tr_number: $input.val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: '{{ route('masteradmin.trip.follow_up_trip_details') }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filter_data').html(
                        response); // Update the results container with HTML content
                        $('#pendingDataTable').DataTable();
                        $('#completedDatatable').DataTable();
                        $('#oneYearDatatable').DataTable();
                        $('#sixMonthDatatable').DataTable();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    //alert('An error occurred while fetching data.');
                }
            });

        }

        function fetchFilteredData1() {
            var formData = {
                tr_number: $input.val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: "{{ route('follow-up-trip.gridView') }}",
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filter_grid_data').html(
                        response); // Update the results container with HTML content
           
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    //alert('An error occurred while fetching data.');
                }
            });

        }

        // Attach change event handlers to filter inputs
        $('#trip_agent, #trip_traveler').on('change keyup', function(e) {

            e.preventDefault();
            //   alert('hii');
            setTimeout(function() {
            fetchFilteredData();
            }, 1000);
            setTimeout(function() {
                fetchFilteredData1();
            }, 500);
        });


        function clearFilters() {
            // Clear filters
            $('#trip_agent').val('').trigger('change');
            $('#trip_traveler').val('').trigger('change');
            $('#tr_number').val('');
        }


    // Event Listener: Load List View when listViewBtn is clicked
$('#listViewBtn').click(function (e) {
    e.preventDefault();

    // Update button active states
    $('#listViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary active');
    $('#gridViewBtn').removeClass('btn-primary active').addClass('btn-outline-secondary');

    // Prepare the filter parameters
    var formData = {
        tr_number: $input.val(),
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val(),
        _token: '{{ csrf_token() }}'
    };

    // Load List View via AJAX
    $.ajax({
        url: '{{ route('masteradmin.trip.follow_up_trip_details') }}',
        data: formData,
        type: 'GET',
        success: function (response) {
            $('#viewContainer').html(response);

            // Initialize DataTable
            $('#pendingDataTable').DataTable();
            $('#completedDatatable').DataTable();
            $('#oneYearDatatable').DataTable();
            $('#sixMonthDatatable').DataTable();
            
            
        },
        error: function (xhr) {
            console.error('Error loading list view:', xhr);
        }
    });
});

// Event Listener: Load Grid View when gridViewBtn is clicked
$('#gridViewBtn').click(function (e) {
    e.preventDefault();

    // Update button active states
    $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');
    $('#listViewBtn').removeClass('btn-primary active').addClass('btn-outline-secondary');

    // Call the Grid View loader
    setTimeout(function () {
            loadGridView(); // Call the Grid View load function after the delay
        }, 1000);


});

// Set default active view to Grid View
setTimeout(function () {
        loadGridView(); // Call the Grid View load function after the delay
    }, 1000);


// Function to load Grid View
function loadGridView() {
    // alert('hii');
    // Update button active states
    $('#gridViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary active');
    $('#listViewBtn').removeClass('btn-primary').addClass('btn-outline-secondary');

    // Prepare the filter parameters
    var formData = {
        tr_number: $input.val(),
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val(),
        _token: '{{ csrf_token() }}'
    };

    // Load Grid View via AJAX
    $.ajax({
        url: "{{ route('follow-up-trip.gridView') }}",
        type: 'GET',
        data: formData,
        success: function (response) {
            $('#viewContainer').html(response); // Update container with grid view content
        },
        error: function (xhr) {
            console.error('Error loading grid view:', xhr);
        }
    });
}
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

@extends('masteradmin.layouts.app')
<title>Trip Details | Trip Tracker</title>
@if (isset($access['book_after_trip']) && $access['book_after_trip'])
    @section('content')
        <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                            <div class="d-flex">    
                                <h1 class="m-0">{{ __('Booked Trips (After Booked)') }}</h1>
                                <ol class="breadcrumb ml-auto">
                                    <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                    <li class="breadcrumb-item active">{{ __('Booked Trips (After Booked)') }}</li>
                                </ol>
                            </div>
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                               
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->

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
                            <div class="col-lg-2 col-1024 col-md-6 px-10">
                                <select id="trip_agent" class="form-control select2" style="width: 100%;" name="trip_agent">
                                    <option value="" default >Choose Agent</option>
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
                                    <option value="" default >Choose Traveler</option>
                                    @foreach ($trips_traveller as $value)
                                        <option value="{{ $value->trtm_id }}">
                                            {{ $value->trtm_first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-1024 col-md-6 px-10">
                                <select class="form-control form-select" style="width: 100%;" name="trip_status"
                                    id="trip_status">
                                    <option value="" default>Choose Status</option>
                                    @foreach ($trip_status as $value)
                                        <option value="{{ $value->tr_status_id }}">
                                            {{ $value->tr_status_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-5 col-1024 col-md-6 px-10 d-flex new-space-remove">
                                <div class="col-lg-4 input-group date">
                                    <x-flatpickr id="from-datepicker" placeholder="From" />
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="from-calendar-icon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>

                             

                                <div class="col-lg-4 input-group date">
                                    <x-flatpickr id="to-datepicker" placeholder="To" />
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="to-calendar-icon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>

                               
                                 <!-- Toggle Buttons for Views -->
                                 <div class="col-lg-4 d-flex justify-content-end align-items-center">
                                    <a id="listViewBtn" href="#list-info" class="btn btn-outline-secondary custom-margin me-2">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    <a id="gridViewBtn" href="#grid-info" class="btn btn-primary active ml-2">
                                        <i class="fas fa-th-large"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-3 input-group date px-10">
                                <div class="flatpickr-container">
                                    <input type="text" class="form-control" id="tr_number" name="tr_number"
                                        placeholder="Trip Number" autocomplete="off" />
                                    <input type="hidden" id="tr_id" name="tr_id">
                                    <div id="autocomplete-list" class="list-group position-absolute mt-0" style="z-index: 1000;"></div>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="clear-icon">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div>
                            </div>
                                                    
                        <!-- <div class="col-lg-4 input-group date">
                            <input type="text" class="form-control" id="tr_number" name="tr_number"
                                placeholder="Trip Number" autocomplete="off" />
                                <input type="hidden" id="tr_id" name="tr_id">
                                <div id="autocomplete-list" class="list-group position-absolute" style="z-index: 1000;"></div>
                        </div> -->

                        </div>
                       
                    
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <div id="viewContainer">
                <section class="content px-10">
                    <div class="container-fluid">
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @php
                                Session::forget('success');
                            @endphp
                        @endif

                        <!-- Main row -->
                        <div id="filters_data">
                            <div class="card px-20">
                                <div class="card-body1">
                                    <div class="col-md-12 table-responsive pad_table">
                                        <table id="bookafterDataTable" class="table table-hover text-nowrap data-table">
                                            <thead>
                                                <tr>
                                                    <th>Trip Name</th>
                                                    <th>Agent Name</th>
                                                    <th>Traveler Name</th>
                                                    <th>Price</th>
                                                    <th>Start to End Date</th>
                                                    <th class="sorting_disabled" data-orderable="false">Status</th>
                                                    <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($trip as $value)
                                                    <tr>
                                                        <td>{{ $value->tr_name ?? '' }}</td>
                                                        <td>{{ $value->users_first_name ?? '' }}
                                                            {{ $value->users_last_name ?? '' }}</td>
                                                        <td>{{ $value->trtm_first_name ?? '' }}</td>
                                                        <td>{{ $value->tr_value_trip ?? '' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($value->tr_start_date ?? '')->format('M d, Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($value->tr_end_date ?? '')->format('M d, Y') }}
                                                        </td>


                                                        <td>
                                                            @php
                                                                $statusName = $value->trip_status->tr_status_name ?? '';

                                                                $buttonColor = match (strtolower($statusName)) {
                                                                    'trip request' => '#DB9ACA',
                                                                    'trip proposal' => '#F6A96D',
                                                                    'trip modification' => '#FBC11E',
                                                                    'trip accepted' => '#28C76F',
                                                                    'trip sold' => '#C5A070',
                                                                    'trip lost' => '#F56B62',
                                                                    'trip completed' => '#F56B62',
                                                                    'trip pending' => '#F6A96D',
                                                                    'in process' => '#F6A96D',
                                                                };
                                                            @endphp

                                                            <button type="button" class="btn text-white"
                                                                style="background-color: {{ $buttonColor }};">
                                                                {{ $statusName }}
                                                            </button>
                                                        </td>


                                                    <td>
                                                    @if (isset($access['details_trip']) && $access['details_trip'])
            
                                                    <a href="{{ route('trip.view', $value->tr_id) }}"><i
                                                            class="fas fa-regular fa-eye edit_icon_grid"></i></a>
                                                    @endif


                                                    @if (isset($access['edit_trip']) && $access['edit_trip'])

                                                    <a href="{{ route('trip.edit', $value->tr_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>
                                                    @endif           
                                                    {{-- <a data-toggle="modal"
                                                                data-target="#delete-product-modal-{{ $value->sale_product_id }}"><i
                                                                    class="fas fa-solid fa-trash delete_icon_grid"></i></a> --}}

                                                    {{-- <div class="modal fade"
                                                                id="delete-product-modal-{{ $value->sale_product_id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                <div class="modal-dialog modal-sm modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <form id="delete-plan-form"
                                                                            action="{{ route('trip.destroy', $value->tr_id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <div class="modal-body pad-1 text-center">
                                                                                <i
                                                                                    class="fas fa-solid fa-trash delete_icon"></i>
                                                                                <p class="company_business_name px-10">
                                                                                    <b>Delete
                                                                                        Trip</b>
                                                                                </p>
                                                                                <p class="company_details_text">Are You Sure
                                                                                    You
                                                                                    Want to Delete This Trip?</p>
                                                                                <button type="button" class="add_btn px-15"
                                                                                    data-dismiss="modal">Cancel</button>
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="delete_btn px-15">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                </td>
                                                </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        </div><!-- /.card-body -->
        </div><!-- /.card-->
        </div>
        <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
                </section>
            </div>
            
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
@endsection
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>






<script>
    $(document).ready(function() {


        const $input = $("#tr_number");
        const $list = $("#autocomplete-list");
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let typingTimeout;  // Declare a variable to store the timeout I

        const $trIdInput = $("#tr_id");
        $input.on("input", function () {
        const query = $(this).val();
        if (query === "") {
            fetchFilteredData(); // Fetch all data if the input is cleared
            fetchFilteredData1(); // Fetch additional data if the input is cleared
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

        $('#bookafterDataTable').DataTable({
            "order": [],
            "ordering": false // Completely disable ordering
        });
        var defaultStartDate = "";
        var defaultEndDate = "";
        var trip_agent = "";
        var trip_traveler = "";
        var trip_status = "";


     $('#from-datepicker').val(defaultStartDate);

        $('#to-datepicker').val(defaultEndDate);

        $('#trip_agent').val(trip_agent);

        $('#trip_traveler').val(trip_traveler);

        // alert()

        $('#trip_status').val(trip_status);

        var fromdatepicker1 = flatpickr("#from-datepicker", {
            locale: 'en',
            altInput: true,
            dateFormat: "MM/DD/YYYY",
            altFormat: "MM/DD/YYYY",
            onChange: function(selectedDates, dateStr, instance) {

                fetchFilteredData();
                fetchFilteredData1();
                //alert('edate');
            },
            parseDate: (datestr, format) => {
                return moment(datestr, format, true).toDate();
            },
            formatDate: (date, format, locale) => {
                return moment(date).format(format);
            }
        });
        document.getElementById('from-calendar-icon').addEventListener('click', function() {
            fromdatepicker1.open();
        });

        var todatepicker1 = flatpickr("#to-datepicker", {
            locale: 'en',
            altInput: true,
            dateFormat: "MM/DD/YYYY",
            altFormat: "MM/DD/YYYY",
            onChange: function(selectedDates, dateStr, instance) {

                fetchFilteredData();

                fetchFilteredData1();
                //alert('edate');
            },
            parseDate: (datestr, format) => {
                return moment(datestr, format, true).toDate();
            },
            formatDate: (date, format, locale) => {
                return moment(date).format(format);
            }
        });
        document.getElementById('to-calendar-icon').addEventListener('click', function() {
            todatepicker1.open();
        });

        $('.filter-text').on('click', function(e) {
            e.preventDefault();
            clearFilters();
        });


        function fetchFilteredData() {
            // var sdate = $('#from-datepicker').val(defaultStartDate);
            // alert(sdate);
            var formData = {
                tr_number: $input.val(),
                start_date: $('#from-datepicker').val(),
                end_date: $('#to-datepicker').val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                trip_status: $('#trip_status').val(),
                _token: '{{ csrf_token() }}'
            };
            //alert('hii');
            //console.log(formData);

            $.ajax({
                url: '{{ route('masteradmin.trip.booked_after') }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filters_data').html(
                        response); 

                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    
                }
            });

        }

        function fetchFilteredData1() {
            var formData = {
                tr_number: $input.val(),
                start_date: $('#from-datepicker').val(),
                end_date: $('#to-datepicker').val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                trip_status: $('#trip_status').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: "{{ route('bookingtrip.gridView') }}",
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
        $('#trip_agent, #trip_traveler, #trip_status').on('change keyup', function(e) {

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
            $('#trip_status').val('').trigger('change');

            $('#tr_number').val('');

            // Clear datepicker fields
            const fromDatePicker = flatpickr("#from-datepicker", {
                locale: 'en',
                altInput: true,
                dateFormat: "MM/DD/YYYY",
                altFormat: "MM/DD/YYYY",
                parseDate: (datestr, format) => {
                    return moment(datestr, format, true).toDate();
                },
                formatDate: (date, format, locale) => {
                    return moment(date).format(format);
                }
            });
            fromDatePicker.clear(); // Clears the "from" datepicker

            const todatepicker = flatpickr("#to-datepicker", {
                locale: 'en',
                altInput: true,
                dateFormat: "MM/DD/YYYY",
                altFormat: "MM/DD/YYYY",
                parseDate: (datestr, format) => {
                    return moment(datestr, format, true).toDate();
                },
                formatDate: (date, format, locale) => {
                    return moment(date).format(format);
                }
            });

            todatepicker.clear();

           
        }

       

        $('#clear-icon').click(function() {
            // Clear the tr_number input field
            $('#tr_number').val('');
            fetchFilteredData();
            fetchFilteredData1();
 
        });

// Set default active view to Grid View
setTimeout(function () {
        loadGridView(); // Call the Grid View load function after the delay
    }, 1000);


// Function to load Grid View
function loadGridView() {
    // Update button active states
    $('#gridViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary active');
    $('#listViewBtn').removeClass('btn-primary').addClass('btn-outline-secondary');

    // Prepare the filter parameters
    var formData = {
        tr_number: $input.val(),
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val(),
        start_date: $('#from-datepicker').val(),
        end_date: $('#to-datepicker').val(),
        trip_status: $('#trip_status').val(),
        _token: '{{ csrf_token() }}'
    };

    // Load Grid View via AJAX
    $.ajax({
        url: "{{ route('bookingtrip.gridView') }}",
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
        start_date: $('#from-datepicker').val(),
        end_date: $('#to-datepicker').val(),
        trip_status: $('#trip_status').val(),
        _token: '{{ csrf_token() }}'
    };

    // Load List View via AJAX
    $.ajax({
        url: '{{ route('masteradmin.trip.booked_after') }}',
        data: formData,
        type: 'GET',
        success: function (response) {
            $('#viewContainer').html(response);

            // Initialize DataTable
            $('#listview4').DataTable();
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

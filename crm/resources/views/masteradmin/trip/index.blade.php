@extends('masteradmin.layouts.app')
<title>Trip Details | Trip Tracker</title>
@if (isset($access['view_trip']) && $access['view_trip'])
    @section('content')
        <link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
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
                                        <option value="{{ $value->trtm_id  ?? ''}}">
                                            {{ $value->trtm_first_name ?? '' }}
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

                        </div>
                    </div>
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Trip Workflow') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Trip Workflow') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_trip']) && $access['add_trip'])
                                    <a href="{{ route('trip.create') }}" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Trip</button></a>
                                @endif
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <div id="viewContainer">
            <!-- /.content-header -->
            <!-- Main content -->
          
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="Form" name="Form" class="mt-6 space-y-6" enctype="multipart/form-data">

                        <input type="hidden" name="role_id" id="role_id">
                        <ul id="update_msgList"></ul>
                        <div class="modal-body">
                            <div class="row pxy-15 px-10">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role_name">Role Name</label>
                                        <input type="text"
                                            class="form-control @error('role_name') is-invalid @enderror" id="role_name"
                                            name="role_name" placeholder="Enter Role Name"
                                            value="{{ old('role_name') }}" />
                                        @error('role_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                                <button type="submit" id="saveBtn" value="create"
                                    class="add_btn">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
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

        var defaultStartDate = "";
        var defaultEndDate = "";
        var trip_agent = "";
        var trip_traveler = "";
        var trip_status = "";


     $('#from-datepicker').val(defaultStartDate);

        $('#to-datepicker').val(defaultEndDate);

        $('#trip_agent').val(trip_agent);

        $('#trip_traveler').val(trip_traveler);

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
                start_date: $('#from-datepicker').val(),
                end_date: $('#to-datepicker').val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                trip_status: $('#trip_status').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: '{{ route('trip.index') }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filter_data').html(
                        response); // Update the results container with HTML content

                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    //alert('An error occurred while fetching data.');
                }
            });

        }

        function fetchFilteredData1() {
            var formData = {
                start_date: $('#from-datepicker').val(),
                end_date: $('#to-datepicker').val(),
                trip_agent: $('#trip_agent').val(),
                trip_traveler: $('#trip_traveler').val(),
                trip_status: $('#trip_status').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: "{{ route('trip.gridView') }}",
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

            fetchFilteredData();
            fetchFilteredData1();
        }

       

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
            trip_agent: $('#trip_agent').val(),
            trip_traveler: $('#trip_traveler').val(),
            start_date: $('#from-datepicker').val(),
            end_date: $('#to-datepicker').val(),
            trip_status: $('#trip_status').val(),
            _token: '{{ csrf_token() }}'
        };

        // Load Grid View via AJAX
        $.ajax({
            url: "{{ route('trip.gridView') }}", // Default route for grid view
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

    //Event Listener: Load List View when listViewBtn is clicked
    $('#listViewBtn').click(function (e) {
        e.preventDefault();

        // Update button active states
        $('#listViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary active');
        $('#gridViewBtn').removeClass('btn-primary active').addClass('btn-outline-secondary');

        // Prepare the filter parameters
        var formData = {
            trip_agent: $('#trip_agent').val(),
            trip_traveler: $('#trip_traveler').val(),
            start_date: $('#from-datepicker').val(),
            end_date: $('#to-datepicker').val(),
            trip_status: $('#trip_status').val(),
            _token: '{{ csrf_token() }}'
        };

        // Load List View via AJAX
        $.ajax({
            url: "{{ route('trip.listView') }}", // Route for list view
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


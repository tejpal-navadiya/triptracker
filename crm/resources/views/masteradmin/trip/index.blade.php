@extends('masteradmin.layouts.app')
<title>Trip Details | Trip Tracker</title>
@if (isset($access['workflow']) && $access['workflow'])
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
                                        <option value="{{ $value->tr_traveler_name }}">
                                            {{ $value->tr_traveler_name }}
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
                                @if (isset($access['book_trip']) && $access['book_trip'])
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
            <section class="content px-10" id="list-info" class="tab">
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
                    <div id="filter_data">
                        <div class="card px-20">
                            <div class="card-body1">
                                <div class="col-md-12 table-responsive pad_table">
                                    <table id="example1" class="table table-hover text-nowrap data-table">
                                        <thead>
                                            <tr>
                                                <th>Trip Name</th>
                                                <th>Agent Name</th>
                                                <th>Traveler Name</th>
                                                <th>Price</th>
                                                <th>Start to End Date</th>
                                                <th>Status</th>
                                                <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           

                                            @foreach ($trip as $value)
                                                <tr>
                                                    <td>{{ $value->tr_name ?? '' }}</td>
                                                    <td>{{ $value->users_first_name ?? '' }}
                                                        {{ $value->users_last_name ?? '' }}</td>
                                                    <td>{{ $value->tr_traveler_name ?? '' }}</td>
                                                    <td>{{ $value->tr_value_trip ?? '' }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($value->tr_start_date ?? '')->format('M d, Y') }}

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
                                                        <a href="{{ route('trip.view', $value->tr_id) }}"><i
                                                                class="fas fa-eye edit_icon_grid"></i></a>
                                                        <a href="{{ route('trip.edit', $value->tr_id) }}"><i
                                                                class="fas fa-pen edit_icon_grid"></i></a>
                                                        <a data-toggle="modal"
                                                            data-target="#delete-product-modal-{{ $value->tr_id }}"><i
                                                                class="fas fa-trash delete_icon_grid"></i></a>


                                                        <div class="modal fade"
                                                            id="delete-product-modal-{{ $value->tr_id }}"
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
                                                                            <p class="company_business_name px-10"><b>Delete
                                                                                    Trip</b></p>
                                                                            <p>Are you sure you want to delete this trip?
                                                                                    </p>
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
                                                        </div>
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
            fetchFilteredData();
            fetchFilteredData1();
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

       

            // Function to load list view
    $('#listViewBtn').click(function (e) {
        e.preventDefault();

        // Update button active states
        $('#listViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('#gridViewBtn').removeClass('btn-primary').addClass('btn-outline-secondary');

        var formData = {
        trip_agent: $('#trip_agent').val(),
        trip_traveler: $('#trip_traveler').val(),
        start_date: $('#from-datepicker').val(),
        end_date: $('#to-datepicker').val(),
        trip_status: $('#trip_status').val(), 
        _token: '{{ csrf_token() }}'
    };

        // Load list view via AJAX
        $.ajax({
            url: "{{ route('trip.listView') }}",
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


    $('#gridViewBtn').click(function (e) {
    e.preventDefault();

    // Update button active states
    $('#gridViewBtn').removeClass('btn-outline-secondary').addClass('btn-primary');
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

    // Load grid view via AJAX
    $.ajax({
        url: "{{ route('trip.gridView') }}",
        type: 'GET',
        data: formData, // Include filters in the request
        success: function (response) {
            $('#viewContainer').html(response); // Update the container with grid view content
        },
        error: function (xhr) {
            console.error('Error loading grid view:', xhr);
        }
    });
});



  
});

</script>


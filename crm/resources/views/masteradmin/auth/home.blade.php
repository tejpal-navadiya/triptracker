@extends('masteradmin.layouts.app')
<title>Dashboard | Trip Tracker</title>
@section('content')
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ url('public/dist/img/logo.png') }}" alt="Trip Tracker Logo">
</div>
<!-- Content Wrapper. Contains page content -->

<style>.container {
    margin-top: 20px;
}

.section-title {
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
}

.table th, .table td {
    vertical-align: middle;
}

.table .text-right {
    text-align: right;
}

.status-btn {
    padding: 4px 10px;
    border-radius: 15px;
    color: white;
}

.sent-status {
    background-color: #00C851;
}

.draft-status {
    background-color: #D980FA;
}

.paid-status {
    background-color: #33b5e5;
}

.statistics-card {
    border: 1px solid #e0e0e0;
    padding: 15px;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.statistics-card h6 {
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
}

.stats-list {
    list-style-type: none;
    padding: 0;
}

.stats-list li {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    margin-bottom: 10px;
}

.stats-list li span {
    font-weight: bold;
}
</style>
<div class="content-wrapper">

    @if(session('alert-configured-data'))
    <div class="alert alert-info" id="alertConfigured">
        {{ session('alert-configured-data') }}
    </div>
    @endif
    <!-- Main content -->

    
    @if (session('beforshowModal'))
    <div class="alert alert-info" id="subscriptionStatus">
       {{ session('beforshowModal') }}
    </div>
    {{ session()->forget('beforshowModal') }}
    @endif


    <section class="content">
        <div class="container-fluid">
            <!-- @if (session('alert-configured-data')) -->
            <div class="modal fade" id="configured-modal" tabindex="-1" role="dialog"
            aria-labelledby="configuredModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                        <p><strong>
                            <div class="alert alert-info">
                                {{ session('alert-configured-data') }}
                            </div>
                        </strong></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                        // $('#configured-modal').modal('show');

                        // setTimeout(function () {
                        //     $('#configured-modal').modal('hide');
                        // }, 5000);
                        setTimeout(function () {
                            $('#alertConfigured').hide();
                        }, 5000);

                        setTimeout(function () {
                            $('#subscriptionStatus').hide();
                        }, 6000);
                    });
                </script>
                <!-- @endif -->
                <!-- Small boxes (Stat box) -->

                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>

        <!-- add by dx -->
        <section class="content">
          <div class="container-fluid">
            <div class="dadh_bord_heding">Analytics</div>
            <!-- Small boxes (Stat box) -->
            <div class="row px-20">

              <!-- ./col -->
              <div class="col-lg-2 col-md-6 col-mdash-box">
                <!-- small box -->
                <div class="small-box bg-customers">
                  <img src="{{url('public/dist/img/customer.png')}}" alt="customer_img" class="small_box_icon">
                  <p class="total_text">Total Trips</p>
                  <h3 class="customer_total">{{$totalTrips}}</h3>
              </div>
          </div>
          <!-- ./col --> 
          <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-vendors">
              <img src="{{url('public/dist/img/vendor.png')}}" alt="vendor" class="small_box_icon">
              <p class="total_text">Total Trip Accept</p>
              <h3 class="customer_total vendor_total">{{$acceptTrips}}</h3>
          </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-2 col-md-6 col-mdash-box">
        <!-- small box -->
        <div class="small-box bg-invoices">
          <img src="{{url('public/dist/img/invoice.png')}}" alt="invoice" class="small_box_icon">
          <p class="total_text">In Progress Trips</p>
          <h3 class="customer_total invoice_total">{{$inProgressTrips}}</h3>
      </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-2 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-bills">
      <img src="{{url('public/dist/img/bill.png')}}" alt="bill" class="small_box_icon">
      <p class="total_text">Completed Trips</p>
      <h3 class="customer_total bill_total">{{$totalcompletedTrips}}</h3>
  </div>
</div>
<!-- ./col -->
<!-- ./col -->
@if ($user->role_id == 0)
<div class="col-lg-2 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-customers">
      <img src="{{url('public/dist/img/customer.png')}}" alt="customer_img" class="small_box_icon">
      <p class="total_text">Total Users</p>
      <h3 class="customer_total">{{$totalUserCount}}</h3>
  </div>
</div>
@endif
<!-- ./col --> 
<div class="col-lg-2 col-md-6 col-mdash-box">
    <!-- small box -->
    <div class="small-box bg-vendors">
      <img src="{{url('public/dist/img/vendor.png')}}" alt="vendor" class="small_box_icon">
      <p class="total_text">Total Travelers</p>
      <h3 class="customer_total vendor_total">{{$totalTrips}}</h3>
  </div>
</div>
<!-- ./col -->
          <!-- <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box --
            <div class="small-box bg-invoices">
              <img src="{{url('public/dist/img/invoice.png')}}" alt="invoice" class="small_box_icon">
              <p class="total_text">Total Suppliers</p>
              <h3 class="customer_total invoice_total">0</h3>
            </div>
        </div> -->
        <!-- ./col -->
    </div>


    <!-- /.row -->
    <!-- Main row -->

    
            <div class="row">
                <div class="col-md-6">
                <div class="card">
        <div class="card-header">            
                    <h6>Trip Completed ({{$totalcompletedTrips}})</h6>
                    <canvas id="monthlyTripChart"></canvas>            
                </div>
            </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
        <div class="card-header">
                    <div>
                        <h6>Trip Request vs Booked</h6>
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>


            </div>
        </div>
        </div>
        <div class="row">
                <div class="col-md-12">
        <div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">View All Reminder Task</h3>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="exampledashboard" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Trip Name</th>
                            <th>Agent Name</th>
                            <th>Traveler Name</th>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</div>





    </div>


</section>


</div>


<!-- barchart -->





<!-- end barchart -->




<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

       // datatable list
        var table = $('#exampledashboard').DataTable();
        table.destroy();
        setTimeout(function(){
        //list
        table = $('#exampledashboard').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.dashboardtask.incomplete') }}",
                type: 'GET',
            },
            debug: true,
            columns: [{
                    data: 'trip_name',
                    name: 'trip_name'
                },
                {
                    data: 'agent_name',
                    name: 'agent_name'
                },
                {
                    data: 'traveler_name',
                    name: 'traveler_name'
                },
                {
                    data: 'trvt_name',
                    name: 'trvt_name'
                },
                {
                    data: 'task_cat_name',
                    name: 'task_cat_name'
                },
                {
                    data: 'trvt_due_date',
                    name: 'trvt_due_date'
                },
                {
                    data: 'trvt_priority',
                    name: 'trvt_priority'
                },
                {
                    data: 'task_status_name',
                    name: 'task_status_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    },1000);

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var fromdatepicker = flatpickr("#create_date", {
          locale: 'en',
          altInput: true,
          dateFormat: "m/d/Y",
          altFormat: "m/d/Y",
          allowInput: true,
      });

        var todatepicker = flatpickr("#due_date", {
          locale: 'en',
          altInput: true,
          dateFormat: "m/d/Y",
          altFormat: "m/d/Y",
          allowInput: true,
      });

        document.getElementById('create-date-icon').addEventListener('click', function () {
          fromdatepicker.open();
      });

        document.getElementById('due-date-icon').addEventListener('click', function () {
          todatepicker.open();
      });


    });


</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Monthly data passed from the backend
        const monthlyData = @json($monthlyData);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const ctx = document.getElementById('monthlyTripChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Completed Trips',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Trip Completion by Month'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Trips'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });
    });

    
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data from controller
        const requestPercentage = {{ $requestPercentage }};
        const bookedPercentage = {{ $bookedPercentage }};

        // Create chart
        const ctx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Requested Trips', 'Accepted Trips'],
                datasets: [{
                    data: [requestPercentage, bookedPercentage],
                    backgroundColor: ['#FF6384', '#36A2EB'], // Colors
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>


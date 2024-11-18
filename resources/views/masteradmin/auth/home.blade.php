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
          <div class="col-lg-4 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box">
              <h2 class="welcome_text">Welcome </h2> 
              <!-- <p>Lorem Ipsum is Simply Dummy Text<br>of the Printing and Typesetting<br>Industry.</p> -->
              <img src="{{url('public/dist/img/welcome_img.png')}}" alt="welcome_img" class="welcome_img">
            </div>
          </div>
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
              <h3 class="customer_total bill_total">{{$completedTrips}}</h3>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <div class="row px-20">
        
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
       
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- end by dx -->
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
<p>{{ session('showModal') }}</p>
@if (session()->has('showModal'))
    <!-- Bootstrap Modal -->
    <div class="modal fade show" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true" style="display: block;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Notice</h5>
                </div>
                <div class="modal-body">
                    <p>{{ session('showModal') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary">Purchase Plan</a>
                </div>
            </div>
        </div>
    </div>

@endif
@endsection
<!DOCTYPE html>
@extends('layouts.app')
<title>Dashboard | Profityo</title>
@section('content')
  <!-- /.Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="dadh_bord_heding">Dashboard</div>
        <!-- Small boxes (Stat box) -->
        <div class="row px-20">
          <div class="col-lg-4 col-md-6">
            <!-- small box -->
            <div class="small-box">
              <h2 class="welcome_text">Welcome Super Admin</h2>
              <p>Lorem Ipsum is Simply Dummy Text<br>of the Printing and Typesetting<br>Industry.</p>
              <img src="{{url('public/dist/img/welcome_img.png')}}" alt="welcome_img" class="welcome_img">
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-customers">
              <div class="dash_board_icon" style="--bgcolor: #FF4E80"><i class="nav-icon fas fa-regular fa-building"></i></div>
              <p class="total_text">Total Business</p>
              <h3 class="customer_total">{{ $totalBusinesses }}</h3>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-vendors">
              <div class="dash_board_icon" style="--bgcolor: #EC9053"><i class="nav-icon fas fa-regular fa-building"></i></div>
              <p class="total_text">Active Business</p>
              <h3 class="customer_total vendor_total">{{ $activeBusinesses }}</h3>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6">
            <!-- small box -->
            <div class="small-box bg-invoices">
              <div class="dash_board_icon" style="--bgcolor: #22EB45"><i class="nav-icon fas fa-regular fa-building"></i></div>
              <p class="total_text">Inactive Business</p>
              <h3 class="customer_total invoice_total">{{ $inactiveBusinesses }}</h3>
            </div>
          </div>
          <!-- ./col -->
          <!-- <div class="col-lg-2 col-md-6">
            <!-- small box --
            <div class="small-box bg-bills">
              <div class="dash_board_icon" style="--bgcolor: #AF70FF"><i class="nav-icon fas fa-regular fa-building"></i></div>
              <p class="total_text">Delete Business</p>
              <h3 class="customer_total bill_total">0</h3>
            </div>
          </div> -->
          <!-- ./col -->
        </div>
        <!-- /.row -->
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
@endsection
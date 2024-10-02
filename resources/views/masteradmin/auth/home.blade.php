@extends('masteradmin.layouts.app')
<title>Dashboard | Trip Tracker</title>
@section('content')
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{url('public/dist/img/logo.png')}}" alt="Trip Tracker Logo">
  </div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="dadh_bord_heding">Dashboard</div>
        <!-- Small boxes (Stat box) -->
        <div class="row px-20">
          <div class="col-lg-4 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box">
              <h2 class="welcome_text">Welcome Riley Mckee</h2>
              <p>Lorem Ipsum is Simply Dummy Text<br>of the Printing and Typesetting<br>Industry.</p>
              <img src="{{url('public/dist/img/welcome_img.png')}}" alt="welcome_img" class="welcome_img">
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-customers">
              <img src="{{url('public/dist/img/customer.png')}}" alt="customer_img" class="small_box_icon">
              <p class="total_text">Total Company</p>
              <h3 class="customer_total">25</h3>
            </div>
          </div>
          <!-- ./col --> 
          <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-vendors">
              <img src="{{url('public/dist/img/vendor.png')}}" alt="vendor" class="small_box_icon">
              <p class="total_text">Total Company</p>
              <h3 class="customer_total vendor_total">25</h3>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-invoices">
              <img src="{{url('public/dist/img/invoice.png')}}" alt="invoice" class="small_box_icon">
              <p class="total_text">Total Company</p>
              <h3 class="customer_total invoice_total">25</h3>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-md-6 col-mdash-box">
            <!-- small box -->
            <div class="small-box bg-bills">
              <img src="{{url('public/dist/img/bill.png')}}" alt="bill" class="small_box_icon">
              <p class="total_text">Total Company</p>
              <h3 class="customer_total bill_total">25</h3>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><img src="{{url('public/dist/img/dot.png')}}" class="dot_img"> Account Balance</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <div class="col-md-12 table-responsive pad_table">
                  <table class="table table-hover text-nowrap dashboard_table">
                    <thead>
                      <tr>
                        <th>BANK</th>
                        <th>HOLDER NAME</th>
                        <th class="text-right">BALANCE</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Round Bank</td>
                        <td>Mariko Cherry</td>
                        <td class="text-right">$111,201.65</td>
                      </tr>
                      <tr>
                        <td>Cobiz Bank</td>
                        <td>Axel Peters</td>
                        <td class="text-right">$12,201.65</td>
                      </tr>
                      <tr>
                        <td>US Bank, NA</td>
                        <td>Ursa Briggs</td>
                        <td class="text-right">$1,022.00</td>
                      </tr>
                      <tr>
                        <td>Charity Bank</td>
                        <td>Arsenio Macias</td>
                        <td class="text-right">$122,201.65</td>
                      </tr>
                      <tr>
                        <td>Caldwell Bank</td>
                        <td>Emerson Evans</td>
                        <td class="text-right">$233,201.65</td>
                      </tr>
                      <tr>
                        <td>Cobiz Bank</td>
                        <td>Joy Mcdonald</td>
                        <td class="text-right">$111,201.65</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /Account Balance -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><img src="{{url('public/dist/img/dot.png')}}" class="dot_img"> Latest Income</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <div class="col-md-12 table-responsive pad_table">
                  <table class="table table-hover text-nowrap dashboard_table">
                    <thead>
                      <tr>
                        <th>DATE</th>
                        <th>CUSTOMER</th>
                        <th class="text-right">AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Jan 28, 2023</td>
                        <td>Lamar Mitchell</td>
                        <td class="text-right">$100.00</td>
                      </tr>
                      <tr>
                        <td>Jan 20, 2023</td>
                        <td>Nina Aguirre</td>
                        <td class="text-right">$100.00</td>
                      </tr>
                      <tr>
                        <td>Jan 14, 2023</td>
                        <td>Lee Winters</td>
                        <td class="text-right">$75.00</td>
                      </tr>
                      <tr>
                        <td>Jan 10, 2023</td>
                        <td>Whoopi Burks</td>
                        <td class="text-right">$250.00</td>
                      </tr>
                      <tr>
                        <td>Jan 08, 2023</td>
                        <td>Candace Pugh</td>
                        <td class="text-right">$4000.00</td>
                      </tr>
                      <tr>
                        <td>Jan 08, 2023</td>
                        <td>Candace Pugh</td>
                        <td class="text-right">$4000.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
             <!-- /Latest Income -->
             <div class="card">
              <div class="card-header">
                <h3 class="card-title"><img src="{{url('public/dist/img/dot.png')}}" class="dot_img"> Latest Expense</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <div class="col-md-12 table-responsive pad_table"></div>
                  <table class="table table-hover text-nowrap dashboard_table">
                    <thead>
                      <tr>
                        <th>DATE</th>
                        <th>CUSTOMER</th>
                        <th class="text-right">AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Jan 28, 2023</td>
                        <td>Lamar Mitchell</td>
                        <td class="text-right">$100.00</td>
                      </tr>
                      <tr>
                        <td>Jan 20, 2023</td>
                        <td>Nina Aguirre</td>
                        <td class="text-right">$100.00</td>
                      </tr>
                      <tr>
                        <td>Jan 14, 2023</td>
                        <td>Lee Winters</td>
                        <td class="text-right">$75.00</td>
                      </tr>
                      <tr>
                        <td>Jan 10, 2023</td>
                        <td>Whoopi Burks</td>
                        <td class="text-right">$250.00</td>
                      </tr>
                      <tr>
                        <td>Jan 08, 2023</td>
                        <td>Candace Pugh</td>
                        <td class="text-right">$4000.00</td>
                      </tr>
                      <tr>
                        <td>Jan 08, 2023</td>
                        <td>Candace Pugh</td>
                        <td class="text-right">$4000.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
             <!-- /Latest Expense -->
          </section>
          <!-- /.Left col -->
          <section class="col-lg-6 connectedSortable">
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
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
</div>
<!-- ./wrapper -->
<p>{{ session('showModal') }}</p>
@if (session()->has('showModal'))
 <!-- Bootstrap Modal -->
    <div class="modal fade show" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="display: block;">
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

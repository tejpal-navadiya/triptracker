@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if(isset($access['book_trip']) && $access['book_trip'])
  @section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="m-0">Trip Detail</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Trip Information</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Basic Information</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <p class="company_business_name">{{ $trip->tr_name ?? ''}}</p>
                <p class="company_details_text">{{ \Carbon\Carbon::parse($trip->tr_start_date ?? '')->format('M d, Y') }} - {{ \Carbon\Carbon::parse($trip->tr_end_date ?? '')->format('M d, Y') }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card-header d-flex p-0 justify-content-center tab_panal">
          <ul class="nav nav-pills p-2 tab_box">
            <li class="nav-item"><a class="nav-link active" href="#Traveleroverview" data-toggle="tab">Traveler Information</a></li>
            <li class="nav-item"><a class="nav-link" href="#Agentinfo" data-toggle="tab">Agent Information</a></li>
            <li class="nav-item"><a class="nav-link" href="#Tasksinfo" data-toggle="tab">Tasks</a></li>
            <li class="nav-item"><a class="nav-link" href="#Documentsinfo" data-toggle="tab">Documents</a></li>
            <li class="nav-item"><a class="nav-link" href="#Emailsinfo" data-toggle="tab">Related Emails</a></li>
          </ul>
        </div><!-- /.card-header -->
          <div class="tab-content px-20">
            <div class="tab-pane active" id="Traveleroverview">
                @include('masteradmin.trip.traveler-information')
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="Agentinfo">
              <div class="card">
                <div class="col-lg-12 card-body3">
                  <div class="row align-items-center">
                    <div class="col-lg-3 col-md-6 col-invoice-box">
                      <div class="invoice_count_box_br1">
                        <div class="invoice_count_box in-primary">
                          <div class="in_contact">
                            <div class="invoice_icon_box"><img src="dist/img/invoice_report.svg" class="invoice_report_icon icon_cr1"></div>
                            <div class="mar_15">
                              <p class="in_contact_title">Total Unpaid</p>
                              <p class="in_contact_total text_color1">$88.50</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-invoice-box">
                      <div class="invoice_count_box_br4">
                        <div class="invoice_count_box in-info">
                          <div class="in_contact">
                            <div class="invoice_icon_box"><img src="dist/img/invoice_report.svg" class="invoice_report_icon icon_cr4"></div>
                            <div class="mar_15">
                              <p class="in_contact_title">Overdue</p>
                              <p class="in_contact_total text_color4">$88.50</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-invoice-box">
                      <div class="invoice_count_box_br2">
                        <div class="invoice_count_box in-secondary">
                          <div class="in_contact">
                            <div class="invoice_icon_box"><img src="dist/img/invoice_report.svg" class="invoice_report_icon icon_cr2"></div>
                            <div class="mar_15">
                              <p class="in_contact_title">Average Time To Pay</p>
                              <p class="in_contact_total text_color2">0 Days</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-invoice-box">
                      <div class="invoice_count_box_br3">
                        <div class="invoice_count_box in-success">
                          <div class="in_contact">
                            <div class="invoice_icon_box"><img src="dist/img/invoice_report.svg" class="invoice_report_icon icon_cr3"></div>
                            <div class="mar_15">
                              <p class="in_contact_title">Not Yet Due</p>
                              <p class="in_contact_total text_color3">$0.00</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 pad-4">
                  <div class="row justify-content-between">
                    <div class="col-md-3 px-10">
                      <select class="form-control form-select" style="width: 100%;">
                        <option default>All statuses</option>
                        <option>Draft</option>
                        <option>Expired</option>
                        <option>Converted</option>
                        <option>Saved</option>
                        <option>Sent</option>
                        <option>Viewed</option>
                      </select>
                    </div>
                    <div class="col-md-3 px-10 d-flex">
                      <div class="input-group date" id="fromdate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" placeholder="From" data-target="#fromdate"/>
                        <div class="input-group-append" data-target="#fromdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                      </div>
                      <div class="input-group date" id="todate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" placeholder="To" data-target="#todate"/>
                        <div class="input-group-append" data-target="#todate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 px-10">
                      <div class="input-group">
                        <input type="search" class="form-control" placeholder="Enter Invoice #">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto"><h3 class="card-title">Invoices</h3></div>
                    <div class="col-auto"><a href="new-invoice.html"><button class="reminder_btn">Create invoice</button></a></div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body1">
                  <div class="col-md-12 table-responsive pad_table">
                    <table id="example4" class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Customer</th>
                          <th>Number</th>
                          <th>Date</th>
                          <th>Due</th>
                          <th>Amount Due</th>
                          <th>Status</th>
                          <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Lamar Mitchell</td>
                          <td>2</td>
                          <td>2024-04-04</td>
                          <td>20 Days Ago</td>
                          <td>$12.50</td>
                          <td><span class="status_btn Paid_status">Paid</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                                <span class="d-block invoice_underline">View</span>
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="view-invoice.html" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  <a href="edit-invoice.html" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send Invoice
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteinvoice">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>
                        <tr>
                          <td>Lamar Mitchell</td>
                          <td>2</td>
                          <td>2024-04-04</td>
                          <td>20 Days Ago</td>
                          <td>$12.50</td>
                          <td><span class="status_btn">Draft</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                                <span class="d-block invoice_underline">Approve</span>
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="view-invoice.html" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  <a href="edit-invoice.html" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send Invoice
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteinvoice">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>
                        <tr>
                          <td>Lamar Mitchell</td>
                          <td>2</td>
                          <td>2024-04-04</td>
                          <td>20 Days Ago</td>
                          <td>$12.50</td>
                          <td><span class="status_btn Paid_status">Paid</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                                <span class="d-block invoice_underline">View</span>
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="view-invoice.html" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  <a href="edit-invoice.html" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send Invoice
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteinvoice">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>
                        <tr>
                          <td>Lamar Mitchell</td>
                          <td>2</td>
                          <td>2024-04-04</td>
                          <td>20 Days Ago</td>
                          <td>$12.50</td>
                          <td><span class="status_btn overdue_status">Overdue</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                                <span class="d-block invoice_underline" data-toggle="modal" data-target="#recordpaymentpopup">Record Payment</span>
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="view-invoice.html" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  <a href="edit-invoice.html" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send Invoice
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteinvoice">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>
                        <tr>
                          <td>Lamar Mitchell</td>
                          <td>2</td>
                          <td>2024-04-04</td>
                          <td>20 Days Ago</td>
                          <td>$12.50</td>
                          <td><span class="status_btn">Draft</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                                <span class="d-block invoice_underline">Approve</span>
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="view-invoice.html" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  <a href="edit-invoice.html" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send Invoice
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  <a href="#" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteinvoice">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="Tasksinfo">
              <div class="card">
                <div class="card-header">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto"><h3 class="card-title">Activity</h3></div>
                    <div class="col-auto"><a href="new-invoice.html"><button class="reminder_btn">Create invoice</button></a></div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="input-group">
                        <input type="search" class="form-control" placeholder="Search by Description">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 d-flex">
                      <div class="input-group date" id="fromdate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" placeholder="From" data-target="#fromdate"/>
                        <div class="input-group-append" data-target="#fromdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                      </div>
                      <div class="input-group date" id="todate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" placeholder="To" data-target="#todate"/>
                        <div class="input-group-append" data-target="#todate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body2">
                  <div class="row">
                    <div class="col-md-12" id="accordion">
                      <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                        <div class="card-header accordion-button">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <P class="mb-0">Apr 19</P>
                            </div>
                            <div class="col-auto align-items-center d-flex">
                              <img src="dist/img/send.svg" class="send_icon">
                              <p class="invoiceid_text mar_15 mb-0">Estimate #2 for $13.50</p>
                            </div>
                            <div class="col-auto">
                              <button class="status_btn mar_15">Sent</button>
                            </div>
                          </div>
                        </div>
                      </a>
                      <div id="collapseOne" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                          <div class="row justify-content-between">
                            <div class="col-auto">
                              <table class="table estimate_detail_table">
                                <div>
                                  <td><strong>Invoice Date</strong></td>
                                  <td>2024-04-04</td>
                                </tr>
                                <tr>
                                  <td><strong>Due Date</strong></td>
                                  <td>Within 30 Days</td>
                                </tr>
                                <tr>
                                  <td><strong>P.O/S.O</strong></td>
                                  <td>adasd</td>
                                </tr>
                                <tr>
                                  <td><strong>Items</strong></td>
                                  <td>1</td>
                                </tr>
                                <tr>
                                  <td><strong>Total</strong></td>
                                  <td>$13.50</td>
                                </tr>
                              </table>
                            </div>
                            <div class="col-auto">
                              <a href="#"><button class="add_btn_br">View related events</button></a>
                              <a href="view-estimate.html"><button class="add_btn">view Estimate</button></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12" id="accordion">
                      <a class="d-block w-100" data-toggle="collapse" href="#collapsetwo">
                        <div class="card-header accordion-button">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <P class="mb-0">Apr 19</P>
                            </div>
                            <div class="col-auto align-items-center d-flex">
                              <img src="dist/img/send.svg" class="send_icon">
                              <p class="invoiceid_text mar_15 mb-0">Estimate #2 for $13.50</p>
                            </div>
                            <div class="col-auto">
                              <button class="status_btn mar_15">Sent</button>
                            </div>
                          </div>
                        </div>
                      </a>
                      <div id="collapsetwo" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                          <div class="row justify-content-between">
                            <div class="col-auto">
                              <table class="table estimate_detail_table">
                                <div>
                                  <td><strong>Invoice Date</strong></td>
                                  <td>2024-04-04</td>
                                </tr>
                                <tr>
                                  <td><strong>Due Date</strong></td>
                                  <td>Within 30 Days</td>
                                </tr>
                                <tr>
                                  <td><strong>P.O/S.O</strong></td>
                                  <td>adasd</td>
                                </tr>
                                <tr>
                                  <td><strong>Items</strong></td>
                                  <td>1</td>
                                </tr>
                                <tr>
                                  <td><strong>Total</strong></td>
                                  <td>$13.50</td>
                                </tr>
                              </table>
                            </div>
                            <div class="col-auto">
                              <a href="#"><button class="add_btn_br">View related events</button></a>
                              <a href="view-estimate.html"><button class="add_btn">view Estimate</button></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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
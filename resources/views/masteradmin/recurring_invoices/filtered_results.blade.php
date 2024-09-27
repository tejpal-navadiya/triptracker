<div class="card-header d-flex p-0 justify-content-center px-20 tab_panal">
    <ul class="nav nav-pills p-2 tab_box">
        <li class="nav-item"><a class="nav-link active" href="#activerecurringinvoice" data-toggle="tab">Active <span class="badge badge-toes">{{ count($activereInvoices) }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#draftrecurringinvoice" data-toggle="tab">Draft <span class="badge badge-toes">{{ count($draftreInvoices) }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#allrecurringinvoice" data-toggle="tab">All Recurring Invoices</a></li>
    </ul>
</div><!-- /.card-header -->
<div class="card px-20">
    <div class="card-body1">
        <div class="tab-content">
            <div class="tab-pane active" id="activerecurringinvoice">
                <div class="col-md-12 table-responsive pad_table">
                <table id="example1" class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Schedule</th>
                        <th>Previous Invoice</th>
                        <th>Next Invoice</th>
                        <th>Status</th>
                        <th>Invoice Amount</th>
                        <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($activereInvoices) > 0)
                    @foreach ($activereInvoices as $value)
                    <tr id="invoices-row-active-{{ $value->sale_re_inv_id }}">
                        <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                        <td>Repeat Monthly on The 1st<br>First invoice: 2024-05-01, Ends: 2024-05-01</td>
                        <td>-</td>
                        <td>{{ \Carbon\Carbon::parse($value->sale_re_inv_date)->format('M d, Y') }}</td>
                        <td><span class="status_btn active_status">{{ $value->sale_status }}</span></td>
                        <td>{{ $value->sale_re_inv_final_amount }}</td>
                        <td>
                        <ul class="navbar-nav ml-auto float-sm-right">
                            <li class="nav-item dropdown">
                            <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                            @if(isset($access['view_recurring_invoices']) && $access['view_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.view', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-eye mr-2"></i> View
                                </a>
                                @endif
                                @if(isset($access['update_recurring_invoices']) && $access['update_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.edit', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                </a>
                                @endif
                                <a href="{{ route('business.recurring_invoices.duplicate', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                </a>
                            </div>
                            </li>
                        </ul>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <th colspan="6">No Data found</th>
                    </tr>
                    @endif
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="draftrecurringinvoice">
                <div class="col-md-12 table-responsive pad_table">
                <table id="example5" class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Schedule</th>
                        <th>Previous Invoice</th>
                        <th>Next Invoice</th>
                        <th>Status</th>
                        <th>Invoice Amount</th>
                        <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($draftreInvoices) > 0)
                    @foreach ($draftreInvoices as $value)
                    <tr id="invoices-row-draft-{{ $value->sale_re_inv_id }}">
                        <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                        <td>Repeat Monthly on The 1st<br>First invoice: 2024-05-01, Ends: 2024-05-01</td>
                        <td>-</td>
                        <td>{{ \Carbon\Carbon::parse($value->sale_re_inv_date)->format('M d, Y') }}</td>
                        <td><span class="status_btn active_status">{{ $value->sale_status }}</span></td>
                        <td>{{ $value->sale_re_inv_final_amount }}</td>
                        <td>
                        <ul class="navbar-nav ml-auto float-sm-right">
                            <li class="nav-item dropdown">
                            <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                            @if(isset($access['view_recurring_invoices']) && $access['view_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.view', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-eye mr-2"></i> View
                                </a>
                                @endif
                                @if(isset($access['update_recurring_invoices']) && $access['update_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.edit', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                </a>
                                @endif
                                <a href="{{ route('business.recurring_invoices.duplicate', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                </a>
                            </div>
                            </li>
                        </ul>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <th colspan="6">No Data found</th>
                    </tr>
                    @endif
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="allrecurringinvoice">
                <div class="col-md-12 table-responsive pad_table">
                <table id="example4" class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Schedule</th>
                        <th>Previous Invoice</th>
                        <th>Next Invoice</th>
                        <th>Status</th>
                        <th>Invoice Amount</th>
                        <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($allreInvoices) > 0)
                    @foreach ($allreInvoices as $value)
                    <tr id="invoices-row-all-{{ $value->sale_re_inv_id }}">
                        <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                        <td>Repeat Monthly on The 1st<br>First invoice: 2024-05-01, Ends: 2024-05-01</td>
                        <td>-</td>
                        <td>{{ \Carbon\Carbon::parse($value->sale_re_inv_date)->format('M d, Y') }}</td>
                        <td><span class="status_btn active_status">{{ $value->sale_status }}</span></td>
                        <td>{{ $value->sale_re_inv_final_amount }}</td>
                        <td>
                        <ul class="navbar-nav ml-auto float-sm-right">
                            <li class="nav-item dropdown">
                            <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                            @if(isset($access['view_recurring_invoices']) && $access['view_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.view', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-eye mr-2"></i> View
                                </a>
                                @endif
                                @if(isset($access['update_recurring_invoices']) && $access['update_recurring_invoices'] == 1) 
                                <a href="{{ route('business.recurring_invoices.edit', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                </a>
                                @endif
                                <a href="{{ route('business.recurring_invoices.duplicate', $value->sale_re_inv_id) }}" class="dropdown-item">
                                <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                </a>
                            </div>
                            </li>
                        </ul>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <th colspan="6">No Data found</th>
                    </tr>
                    @endif
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.tab-pane -->
        <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->
</div><!-- /.card-->
<script>
  $(function () {
    $('#example1').DataTable({
      paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true
    });
  });
</script>
<script>
  $(function () {
    $('#example5').DataTable({
      paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true
    });
  });
</script>
<script>
  $(function () {
    $('#example4').DataTable({
      paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true
    });
  });
</script>
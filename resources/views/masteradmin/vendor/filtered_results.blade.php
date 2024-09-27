<div class="card px-20">
    <div class="card-body1">
        <div class="col-md-12 table-responsive pad_table">
        <table id="example4" class="table table-hover text-nowrap">
            <thead>
            <tr>
                <th>Vendors</th>
                <th>Number</th>
                <th>Date</th>
                <th>Due Date</th>
                <th>Amount Due</th>
                <th>Status</th>
                <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if (count($bills) > 0)
            @foreach ($bills as $value)
            <tr id="row-bill-{{ $value->sale_bill_id }}">
                <td>{{ $value->vendor->purchases_vendor_name }}</td>
                <td>{{ $value->sale_bill_number }}</td>
                <td>{{ \Carbon\Carbon::parse($value->sale_bill_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($value->sale_bill_valid_date)->format('M d, Y') }}</td>
                <td>{{ $value->sale_bill_due_amount }}</td>
                <td><span class="status_btn Paid_status">{{ $value->sale_status }}</span></td>
                <td>
                <ul class="navbar-nav ml-auto float-right">
                    <li class="nav-item dropdown d-flex align-items-center">
                    <a class="d-block invoice_underline" data-toggle="modal" data-target="#recordpaymentpopup">Record a payment</a>
                    <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                        <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('business.bill.view',$value->sale_bill_id) }}" class="dropdown-item">
                        <i class="fas fa-regular fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('business.bill.edit',$value->sale_bill_id) }}" class="dropdown-item">
                        <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                        </a>
                        <a href="{{ route('business.bill.duplicate',$value->sale_bill_id) }}" class="dropdown-item">
                        <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                        </a>
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deletebill_{{ $value->sale_bill_id }}">
                        <i class="fas fa-solid fa-trash mr-2"></i> Delete
                        </a>


                    </div>
                    </li>
                </ul>
                </td>

                <div class="modal fade" id="deletebill_{{ $value->sale_bill_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <form method="POST" action="{{ route('business.bill.destroy', ['id' => $value->sale_bill_id]) }}" id="delete-form-{{ $value->sale_bill_id }}" data-id="{{ $value->sale_bill_id }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body pad-1 text-center">
                        <i class="fas fa-solid fa-trash delete_icon"></i>
                        <p class="company_business_name px-10"><b>Delete Bill</b></p>
                        <p class="company_details_text px-10">Delete Bill {{ $value->sale_bill_id }}</p>
                        <p class="company_details_text">Are You Sure You Want to Delete This Bill?</p>
                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                        <button type="button" class="delete_btn px-15" data-id="{{ $value->sale_bill_id }}">Delete</button>
                    </div>
                    </form>
                    </div>
                </div>
                </div>

            </tr>

            @endforeach    
            @else
                <tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No records found</td></tr>
            @endif
            </tbody>
        </table>
        </div>
    </div><!-- /.card-body -->
</div><!-- /.card-->
<script>

$(function () {

  $("#example4").DataTable({
      "stateSave": true, 
      "stateDuration": -1, 
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
  });

});

</script>
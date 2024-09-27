
<div class="card-header d-flex p-0 justify-content-center px-20 tab_panal">
        <ul class="nav nav-pills p-2 tab_box">
          <li class="nav-item"><a class="nav-link active" href="#activeestimate" data-toggle="tab">Active <span
                class="badge badge-toes">{{ count($activeEstimates) }}</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#draftestimate" data-toggle="tab">Draft <span
                class="badge badge-toes">{{ count($draftEstimates) }}</span></a></li>
          <li class="nav-item"><a class="nav-link" href="#allestimate" data-toggle="tab">All</a></li>
        </ul>
      </div><!-- /.card-header -->
      <div class="card px-20">
        <div class="card-body1">
          <div class="tab-content">
            <div class="tab-pane active" id="activeestimate">
              <div class="col-md-12 table-responsive pad_table">
                <table id="example1" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Number</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($activeEstimates) > 0)
                      @foreach ($activeEstimates as $value)
                        <tr id="estimate-row-approve-{{ $value->sale_estim_id }}">
                          <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                          <td>{{ $value->sale_estim_number }}</td>
                          <td>{{ \Carbon\Carbon::parse($value->sale_estim_date)->format('M d, Y') }}</td>
                          <td>{{ $value->sale_estim_final_amount }}</td>
                          <td><span class="status_btn">{{ $value->sale_status }}</span></td>
                          <!-- Actions Dropdown -->
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                              @php
                                    $nextStatus = '';
                                    if($value->sale_status == 'Draft') {
                                        $nextStatus = 'Approve';
                                    } elseif($value->sale_status == 'Saved') {
                                        $nextStatus = 'Send';
                                    } elseif($value->sale_status == 'Sent') {
                                        $nextStatus = 'Convert to Invoice';
                                    } elseif($value->sale_status == 'Converted') {
                                        $nextStatus = 'Duplicate';
                                    }else{
                                      $nextStatus = 'Duplicate';
                                    }
                                @endphp

                                @if($nextStatus)
                                <a href="javascript:void(0);" onclick="updateStatus({{ $value->sale_estim_id }}, '{{ $nextStatus }}')" >
                                    {{ $nextStatus }}
                                </a>
                                @else
                                    <span class="d-block">Unknown Status</span>
                                @endif
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="{{ route('business.estimates.view', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  @if(isset($access['update_estimates']) && $access['update_estimates']) 
                                  <a href="{{ route('business.estimates.edit', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  @endif
                                  <a href="{{ route('business.estimates.duplicate', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a target="_blank" href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id,'print' => 'true']) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="{{ route('business.estimates.viewInvoice', [$value->sale_estim_id]) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-invoice mr-2"></i> Convert To Invoice
                                  </a>
                                  <a href="javascript:void(0);" onclick="sendEstimate('{{ route('business.estimate.send', [$value->sale_estim_id, $user_id]) }}', {{ $value->sale_estim_id }})"  class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send
                                  </a>
                                  <a href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id, 'download' => 'true']) }}"  class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  @if(isset($access['delete_estimates']) && $access['delete_estimates']) 
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteestimateapprove-{{ $value->sale_estim_id }}">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                  @endif
                                </div>
                              </li>
                            </ul>
                          </td>
                       
                        </tr>
                        
                        <div class="modal fade" id="deleteestimateapprove-{{ $value->sale_estim_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                      <div class="modal-content">
                      <form method="POST" action="{{ route('business.estimates.destroy', ['id' => $value->sale_estim_id]) }}" id="delete-form-{{ $value->sale_estim_id }}" data-id="{{ $value->sale_estim_id }}">
                            @csrf
                            @method('DELETE')
                        <div class="modal-body pad-1 text-center">
                          <i class="fas fa-solid fa-trash delete_icon"></i>
                          <p class="company_business_name px-10"><b>Delete Customer</b></p>
                          <p class="company_details_text">Are You Sure You Want to Delete This Customer?</p>
                         
                            <!-- <input type="hidden" name="sale_cus_id" id="customer-id"> -->
                          <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                          <button type="button" class="delete_btn px-15" data-id="{{ $value->sale_estim_id }}">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>

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

            <div class="tab-pane" id="draftestimate">
              <div class="col-md-12 table-responsive pad_table">
                <table id="example5" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Number</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($draftEstimates) > 0)
                      @foreach ($draftEstimates as $value)
                        <tr id="estimate-row-draft-{{ $value->sale_estim_id }}">
                          <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                          <td>{{ $value->sale_estim_number }}</td>
                          <td>{{ \Carbon\Carbon::parse($value->sale_estim_date)->format('M d, Y') }}</td>
                          <td>{{ $value->sale_estim_final_amount }}</td>
                          <td><span class="status_btn">{{ $value->sale_status }}</span></td>
                          <!-- Actions Dropdown -->
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                              @php
                                    $nextStatus = '';
                                    if($value->sale_status == 'Draft') {
                                        $nextStatus = 'Approve';
                                    } elseif($value->sale_status == 'Saved') {
                                        $nextStatus = 'Send';
                                    } elseif($value->sale_status == 'Sent') {
                                        $nextStatus = 'Convert to Invoice';
                                    } elseif($value->sale_status == 'Converted') {
                                        $nextStatus = 'Duplicate';
                                    }else{
                                      $nextStatus = 'Duplicate';
                                    }
                                @endphp

                                @if($nextStatus)
                                <a href="javascript:void(0);" onclick="updateStatus({{ $value->sale_estim_id }}, '{{ $nextStatus }}')" >
                                    {{ $nextStatus }}
                                </a>
                                @else
                                    <span class="d-block">Unknown Status</span>
                                @endif
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a href="{{ route('business.estimates.view', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  @if(isset($access['update_estimates']) && $access['update_estimates']) 
                                  <a href="{{ route('business.estimates.edit', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  @endif
                                  <a href="{{ route('business.estimates.duplicate', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a target="_blank" href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id,'print' => 'true']) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="{{ route('business.estimates.viewInvoice', [$value->sale_estim_id]) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-invoice mr-2"></i> Convert To Invoice
                                  </a>
                                  <a href="javascript:void(0);" onclick="sendEstimate('{{ route('business.estimate.send', [$value->sale_estim_id, $user_id]) }}', {{ $value->sale_estim_id }})"  class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send
                                  </a>
                                  <a href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id, 'download' => 'true']) }}"  class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  @if(isset($access['delete_estimates']) && $access['delete_estimates']) 
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteestimatedraft-{{ $value->sale_estim_id }}">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                  @endif
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>

                        <div class="modal fade" id="deleteestimatedraft-{{ $value->sale_estim_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <form method="POST" action="{{ route('business.estimates.destroy', ['id' => $value->sale_estim_id]) }}" id="delete-form-{{ $value->sale_estim_id }}" data-id="{{ $value->sale_estim_id }}">
                              @csrf
                              @method('DELETE')
                          <div class="modal-body pad-1 text-center">
                            <i class="fas fa-solid fa-trash delete_icon"></i>
                            <p class="company_business_name px-10"><b>Delete Customer</b></p>
                            <p class="company_details_text">Are You Sure You Want to Delete This Customer?</p>
                          
                              <!-- <input type="hidden" name="sale_cus_id" id="customer-id"> -->
                            <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                            <button type="button" class="delete_btn px-15" data-id="{{ $value->sale_estim_id }}">Delete</button>
                            </form>
                          </div>
                        </div>
                      </div>


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

            <div class="tab-pane" id="allestimate">
              <div class="col-md-12 table-responsive pad_table">
                <table id="example4" class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Number</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($allEstimates) > 0)
                      @foreach ($allEstimates as $value)
                        <tr id="estimate-row-{{ $value->sale_estim_id }}">
                          <td>{{ $value->customer->sale_cus_first_name }} {{ $value->customer->sale_cus_last_name }}</td>
                          <td>{{ $value->sale_estim_number }}</td>
                          <td>{{ \Carbon\Carbon::parse($value->sale_estim_date)->format('M d, Y') }}</td>
                          <td>{{ $value->sale_estim_final_amount }}</td>
                          <td><span class="status_btn">{{ $value->sale_status }}</span></td>
                          <td>
                            <ul class="navbar-nav ml-auto float-sm-right">
                              <li class="nav-item dropdown d-flex align-items-center">
                             
                                @php
                                    $nextStatus = '';
                                    if($value->sale_status == 'Draft') {
                                        $nextStatus = 'Approve';
                                    } elseif($value->sale_status == 'Saved') {
                                        $nextStatus = 'Send';
                                    } elseif($value->sale_status == 'Sent') {
                                        $nextStatus = 'Convert to Invoice';
                                    } elseif($value->sale_status == 'Converted') {
                                        $nextStatus = 'Duplicate';
                                    }else{
                                      $nextStatus = 'Duplicate';
                                    }
                                @endphp

                                @if($nextStatus)
                                <a href="javascript:void(0);" onclick="updateStatus({{ $value->sale_estim_id }}, '{{ $nextStatus }}')" >
                                    {{ $nextStatus }}
                                </a>
                                @else
                                    <span class="d-block">Unknown Status</span>
                                @endif
                                <a class="nav-link user_nav" data-toggle="dropdown" href="#">
                                  <span class="action_btn"><i class="fas fa-solid fa-chevron-down"></i></span>
                                </a>
                               
                                <div class="dropdown-menu dropdown-menu-right">
                                  @if(isset($access['view_estimates']) && $access['view_estimates']) 
                                  <a href="{{ route('business.estimates.view', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-eye mr-2"></i> View
                                  </a>
                                  @endif
                                  @if(isset($access['update_estimates']) && $access['update_estimates']) 
                                  <a href="{{ route('business.estimates.edit', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-pen-to-square mr-2"></i> Edit
                                  </a>
                                  @endif
                                  <a href="{{ route('business.estimates.duplicate', $value->sale_estim_id) }}" class="dropdown-item">
                                    <i class="fas fa-regular fa-copy mr-2"></i> Duplicate
                                  </a>
                                  <a target="_blank" href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id,'print' => 'true']) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-print mr-2"></i> Print
                                  </a>
                                  <a href="{{ route('business.estimates.viewInvoice', [$value->sale_estim_id]) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-invoice mr-2"></i> Convert To Invoice
                                  </a>
                                  <a href="javascript:void(0);"  data-id="{{ $value->sale_estim_id }}" onclick="sendEstimate('{{ route('business.estimate.send', [$value->sale_estim_id, $user_id]) }}', {{ $value->sale_estim_id }})"  class="dropdown-item">
                                    <i class="fas fa-regular fa-paper-plane mr-2"></i> Send
                                  </a>
                                  <a href="{{ route('business.estimate.sendviews', [ $value->sale_estim_id, $user_id, 'download' => 'true']) }}" class="dropdown-item">
                                    <i class="fas fa-solid fa-file-pdf mr-2"></i> Export As Pdf
                                  </a>
                                  @if(isset($access['delete_estimates']) && $access['delete_estimates']) 
                                  <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteestimatall-{{ $value->sale_estim_id }}">
                                    <i class="fas fa-solid fa-trash mr-2"></i> Delete
                                  </a>
                                  @endif
                                </div>
                              </li>
                            </ul>
                          </td>
                        </tr>

                        <div class="modal fade" id="deleteestimatall-{{ $value->sale_estim_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                      <div class="modal-content">
                      <form method="POST" action="{{ route('business.estimates.destroy', ['id' => $value->sale_estim_id]) }}" id="delete-form-{{ $value->sale_estim_id }}" data-id="{{ $value->sale_estim_id }}">
                            @csrf
                            @method('DELETE')
                        <div class="modal-body pad-1 text-center">
                          <i class="fas fa-solid fa-trash delete_icon"></i>
                          <p class="company_business_name px-10"><b>Delete Customer</b></p>
                          <p class="company_details_text">Are You Sure You Want to Delete This Customer?</p>
                         
                            <!-- <input type="hidden" name="sale_cus_id" id="customer-id"> -->
                          <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                          <button type="button" class="delete_btn px-15" data-id="{{ $value->sale_estim_id }}">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>

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
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div><!-- /.card-->

      <script>

$(function () {

  $("#example1").DataTable({
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

<script>

$(function () {

  $("#example5").DataTable({
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
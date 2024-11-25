@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
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
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Trip Information</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-auto"><button href="javascript:void(0)" id="createNewTask3" class="reminder_btn">Add
                    Task</button></div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
    
        <div class="col-lg-12 fillter_box">
          <div class="row align-items-center justify-content-between">
              <div class="col-auto">
                  <p class="m-0 filter-text"><i class="fas fa-solid fa-filter"></i>Filters</p>
              </div><!-- /.col -->
              <div class="col-auto">
                  <p class="m-0 float-sm-right filter-text">Clear Filters<i class="fas fa-regular fa-circle-xmark"></i></p>
              </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="row">
              <div class="col-lg-3 col-1024 col-md-6 px-10">
                  <select id="trip_agent" class="form-control select2" style="width: 100%;" name="trip_agent">
                      <option value="" default >Choose Agent</option>
                      @foreach($agency_user as $value)
                      <option value="{{ $value->users_id }}">
                          {{ $value->users_first_name }} {{ $value->users_last_name }}
                      </option>
                      @endforeach
                  </select>
              </div>

              <div class="col-lg-3 col-1024 col-md-6 px-10">
                  <select id="trip_traveler" class="form-control select2" style="width: 100%;" name="trip_traveler">
                      <option value="" default >Choose Traveler</option>
                      @foreach($trip as $value)
                      <option value="{{ $value->tr_traveler_name }}">
                          {{ $value->tr_traveler_name }}
                      </option>
                      @endforeach
                  </select>
              </div>
       
              </div>
        </div>
        <div class="card-header d-flex p-0 justify-content-center tab_panal">
          <ul class="nav nav-pills p-2 tab_box">
            <li class="nav-item"><a class="nav-link active" href="#Traveleroverview" data-toggle="tab">View All Task</a></li>
            @if(isset($access['reminder_all_task']) && $access['reminder_all_task'])
            <li class="nav-item"><a class="nav-link" href="#Agentinfo" data-toggle="tab">View All Reminder Task</a></li>
            @endif
          </ul>
        </div><!-- /.card-header -->
          <div class="tab-content px-20">
            <div class="tab-pane active" id="Traveleroverview">
                @include('masteradmin.task.all-information')
            </div>
            <!-- /.tab-pane -->
            @if(isset($access['reminder_all_task']) && $access['reminder_all_task'])
            <div class="tab-pane" id="Agentinfo">
              @include('masteradmin.task.reminder-information')
            </div>
            @endif
          <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->


  <div class="modal fade" id="ajaxModelTask3" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingTask3"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form id="FormTask3" name="FormTask3" class="mt-6 space-y-6" enctype="multipart/form-data">

                <input type="hidden" name="trvt_id" id="trvt_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="tr_id">Trip Name</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="tr_id"
                                        name="tr_id">
                                        <option value="" default>Select Trip Name</option>
                                        @foreach ($trip as $tripvalue)
                                            <option value="{{ $tripvalue->tr_id }}">{{ $tripvalue->tr_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('tr_name')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_name">Task</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trvt_name1" name="trvt_name"
                                        placeholder="Enter Task">
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_name')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_agent_id">Assign Agent</label>
                                <div class="d-flex">
                                    <select id="trvt_agent_id1" style="width: 100%;" name="trvt_agent_id"
                                        class="form-control select2">
                                        <option value="">Select Agent</option>
                                        @foreach ($agency_user as $value)
                                            <option value="{{ $value->users_id }}">
                                                {{ $value->users_first_name ?? '' }} {{ $value->users_last_name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_category">Category<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_category1"
                                        name="trvt_category">
                                        <option value="" default>Select Category</option>
                                        @foreach ($taskCategory as $taskcat)
                                            <option value="{{ $taskcat->task_cat_id }}">{{ $taskcat->task_cat_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_category')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_priority">Priority</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_priority1"
                                        name="trvt_priority">
                                        <option value="" default>Select Priority</option>
                                        <option value="Medium">Medium</option>
                                        <option value="High">High</option>
                                        <option value="Low">Low</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_priority')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_date">Create Date</label>
                                <div class="d-flex">
                                    <div class="input-group date" id="trvt_date1" data-target-input="nearest">
                                        <x-flatpickr id="create_date" name="trvt_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="create-date-icon">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_date_hidden" />
                                            </div>
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_date')" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_due_date">Due Date</label>
                                <div class="d-flex">
                                    <div class="input-group date" id="trvt_due_date1" data-target-input="nearest">
                                        <x-flatpickr id="due_date" name="trvt_due_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="due-date-icon">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_due_date_hidden" />
                                            </div>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_due_date')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_document">Upload Documents</label>
                                <div class="d-flex">
                                    <x-text-input type="file" name="trvt_document" id="trvt_document1" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvt_document')" />
                                <p id="task_document"></p>
                                <label for="trvt_document">Only jpg, jpeg, png, and pdf files are allowed</label>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusField1" style="display: none;"> <!-- Initially hidden -->
                            <div class="form-group">
                                <label for="trvt_category">Status<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_status"
                                        name="status">
                                        <option value="0" default>Select Status</option>
                                        @foreach ($taskstatus as $value)
                                            <option value="{{ $value->ts_status_id }}">
                                                {{ $value->ts_status_name }}
                                            </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvt_status')" />
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtnTask3" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="task-success-modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="task-success-message1">Data has been successfully inserted!</p>
                <button type="button" class="add_btn px-15" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

           //datatable list
           var allTableList = $('#example11').DataTable();
         allTableList.destroy();

        //list
        setTimeout(function(){

        allTableList = $('#example11').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.task.all') }}",
                type: 'GET',
                data: function(d) {
                    d.trip_agent = $('#trip_agent').val(); 
                    d.trip_traveler = $('#trip_traveler').val(); 
                    d._token = '{{ csrf_token() }}';
                }
            },
            columns: [
                {
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


        //create task
        $('#createNewTask3').click(function() {
            // alert('hii');
            $('#saveBtnTask3').val("create-product");
            $('#trvt_id').val('');
            $('#FormTask3')[0].reset();
            $('#modelHeadingTask3').html("Add Task");
            $('body').addClass('modal-open');
            $('#task_document').html('');
            

            $('#statusField').hide(); // Hide status field during add

            var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask3'));
            editModal.show();
        });

          //insert/update data
          $('#saveBtnTask3').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
        

            var formData = new FormData($('#FormTask3')[0]);
            formData.append('_token', "{{ csrf_token() }}");

            var url = '';
            var method = 'POST'; // Default to POST for new tasks
            var tasksuccessMessage = '';
            if ($('#trvt_id').val() === '') {
                // Create new task
                url = "{{  route('masteradmin.taskdetails.store') }}";
                formData.append('_method', 'POST');
                tasksuccessMessage = 'Data has been successfully inserted!'; 
            } else {
                // Update existing task
                var trvt_id = $('#trvt_id').val();
                var url = "{{ route('masteradmin.taskdetails.update', ':trvt_id') }}";
                url = url.replace(':trvt_id', trvt_id);
                formData.append('_method', 'PATCH');
                tasksuccessMessage = 'Data has been successfully updated!';
            }

            $.ajax({
                data: formData,
                url: url,
                type: method,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    allTableList.draw();
                    $('#task-success-message1').text(tasksuccessMessage);
                    $('#task-success-modal1').modal('show');
                    $('#example15').DataTable().ajax.reload();
                    $('#ajaxModelTask3').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelTask3').css('display', 'none');
                    $('#saveBtnTask3').html('Save');
                    $('#FormTask3')[0].reset();
                    $('#FormTask')[0].reset();


                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtnTask3').html('Save Changes');
                }
            });
        });

      



    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

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

        document.getElementById('create-date-icon').addEventListener('click', function() {
            fromdatepicker.open();
        });

        document.getElementById('due-date-icon').addEventListener('click', function() {
            todatepicker.open();
        });


    });
</script>


  @endsection
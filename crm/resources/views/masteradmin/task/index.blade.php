@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if (isset($access['task_details']) && $access['task_details'])
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
                      @foreach($trips_traveller as $value)
                      <option value="{{ $value->trtm_id }}">
                          {{ $value->trtm_first_name }}
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
                                            <option value="{{ $value->users_id }}" {{ old('tr_agent_id', $user->users_id ?? '') == $value->users_id ? 'selected' : '' }}>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_note">Notes </label>
                                <div class="d-flex">
                                    <textarea type="text" class="form-control" id="trvt_note" placeholder="Enter Notes" name="trvt_note"
                                    autofocus autocomplete="trvt_note">{{ old('trvt_note') }}</textarea>
                                </div>
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
                    $('#example11').DataTable().ajax.reload();
                    $('#example15').DataTable().ajax.reload();
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message1').text(tasksuccessMessage);
                    $('#task-success-modal1').modal('show');
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

      
  //edit popup open
  $(document).on('click', '.editTask', function(e) {
            e.preventDefault();


            var id = $(this).data('id');
            var url = "{{ route('masteradmin.taskdetails.editTask', ['id' => ':id']) }}";
            if (url) {
                url = url.replace(':id', id);
            // alert(id);
             $.get(url, function(data) {


                    // console.log(data);
                    $('#modelHeadingTask').html("Edit Task");
                    $('#saveBtnTask').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask'));
                    editModal.show();

                    $('#trvt_id').val(data.trvt_id);
                    $('#trvt_name').val(data.trvt_name);
                    $('#tr_id').val(data.tr_id).trigger('change.select2');

                    $('#trvt_agent_id').val(data.trvt_agent_id).trigger('change.select2');
                    $('#trvt_category').val(data.trvt_category).trigger('change.select2');
                    $('#trvt_date').val(data.trvt_date);
                    $('#trvt_due_date').val(data.trvt_due_date);
                    $('#trvt_note').val(data.trvt_note);

                    $('#trvt_date_hidden').val(data.trvt_date);
                    $('#trvt_due_date_hidden').val(data.trvt_due_date);

                    $('#trvt_priority').val(data.trvt_priority).trigger('change.select2');

                    $('#task_document').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    if (data.trvt_document) {
                        $('#task_document').append(
                            '<a href="' + baseUrl + '/tasks/' + data
                            .trvt_document + '" target="_blank">' +
                            data.trvt_document +
                            '</a>'
                        );
                    }
                    
                    $('#statusField').show();
                    $('#trvt_status').val(data.status).trigger(
                        'change.select2'); // set the selected status
                    var trvt_date_hidden = document.getElementById('trvt_date_hidden');
                    var trvt_due_date_hidden = document.getElementById('trvt_due_date_hidden');

                    if (trvt_date_hidden && trvt_due_date_hidden) {
                        var completed_date = flatpickr("#create_date", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_date_hidden.value || null,
                        });

                        var todatepicker = flatpickr("#due_date", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_due_date_hidden.value || null,
                        });

                        document.getElementById('create-date-icon').addEventListener('click',
                            function() {
                                fromdatepicker.open();
                            });

                        document.getElementById('due-date-icon').addEventListener('click',
                            function() {
                                todatepicker.open();
                            });
                    }

                });
            }
        });

        //delete record
        $('body').on('click', '.deleteTaskbtn', function(e) {
            e.preventDefault();
            //  alert(trtm_id);
             var trvt_id = $(this).data("id");
            
                if (trvt_id) {
                    var url = "{{ route('masteradmin.taskdetails.destroy', ':trvt_id') }}";
                    url = url.replace(':trvt_id', trvt_id);
          
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(data) {
                    // alert(data.success);
                    $('#example11').DataTable().ajax.reload();
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message').text('Data has been successfully Deleted!');
                    $('#task-success-modal').modal('show');
                    $('.ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask').css('display', 'none');

                    $('#example15').DataTable().ajax.reload();

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
        });

      

        $('#trip_agent, #trip_traveler').on('change', function() {
            $('#allTaskDataTable').DataTable().ajax.reload();
        });

        $('.filter-text').on('click', function() {
            $('#trip_agent').val('').trigger('change'); 
            $('#trip_traveler').val('').trigger('change');
            $('#allTaskDataTable').DataTable().ajax.reload();
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

<script>
    $(document).ready(function() {
       
        //datatable list
        
        var table = $('#example15').DataTable();
        table.destroy();

        //list
        setTimeout(function(){
            table = $('#example15').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.task.incomplete') }}",
                type: 'GET',
                data: function(d) {
                    d.trip_agent = $('#trip_agent').val(); 
                    d.trip_traveler = $('#trip_traveler').val(); 
                    d._token = '{{ csrf_token() }}';
                }
            },
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
        },2000);
        

        setTimeout(function(){
        //insert/update data
        $('#saveBtnTaskReminder').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            var formData = new FormData($('#FormTask1')[0]);
            formData.append('_token', "{{ csrf_token() }}");

            var url = '';
            var method = 'POST';
            var tasksuccessMessage = '';
            
            if ($('#trvt_idReminder').val() === '') {
                url = "{{  route('masteradmin.taskdetails.store') }}";
                tasksuccessMessage = 'Data has been successfully inserted!'; 
            } else {
                var trvt_id = $('#trvt_idReminder').val();
              //  alert(trvt_id);
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
                    $('#example11').DataTable().ajax.reload();
                    $('#example15').DataTable().ajax.reload();
                    $('#task-success-message2').text(tasksuccessMessage);
                    $('#task-success-modal2').modal('show');
                    $('#ajaxModelTask1').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelTask1').css('display', 'none');
                    $('#saveBtnTaskReminder').html('Save');
                    $('#FormTask1')[0].reset();
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtnTaskReminder').html('Save Changes');
                }
            });
        });
        },3000);
        //edit popup open
        $(document).on('click', '.editTaskreminder', function(e) {
            e.preventDefault();

        
            var id = $(this).data('id');
            // alert(id);
            var url;

            var url = "{{ route('masteradmin.taskdetails.editTask', ['id' => ':id']) }}";
            url = url.replace(':id', id);
            if (url) {
                url = url.replace(':id', id);
            // alert(id);
             $.get(url, function(data) {

                    // console.log(data);
                    $('#modelHeadingTaskReminder').html("Edit Task");
                    $('#saveBtnTaskReminder').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask1'));
                    editModal.show();

                    $('#trvt_idReminder').val(data.trvt_id);
                    $('#trvt_nameReminder').val(data.trvt_name);
                    
                    $('#trvt_agent_idReminder').val(data.trvt_agent_id).trigger('change.select2');
                    
                    $('#trvt_categoryReminder').val(data.trvt_category).trigger('change.select2');
                    $('#trvt_datereminder').val(data.trvt_date);
                    $('#trvt_due_dateReminder').val(data.trvt_due_date);
                    $('#trvt_noteReminder').val(data.trvt_note);
                    $('#trvt_date_hiddenReminder').val(data.trvt_date);
                    $('#trvt_due_date_hiddenReminder').val(data.trvt_due_date);

                    $('#trvt_priorityReminder').val(data.trvt_priority).trigger('change.select2');

                    $('#task_documentReminder').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    if (data.trvt_document) {
                        $('#task_documentReminder').append(
                            '<a href="' + baseUrl + '/tasks/' + data
                            .trvt_document + '" target="_blank">' +
                            data.trvt_document +
                            '</a>'
                        );
                    }

                    
                    $('#tr_idReminder').val(data.tr_id).trigger('change.select2');

                    $('#statusFieldReminder').show();
                    $('#trvt_statusReminder').val(data.status).trigger(
                        'change.select2'); // set the selected status

                    var trvt_date_hidden = document.getElementById('trvt_date_hiddenReminder');
                    var trvt_due_date_hidden = document.getElementById('trvt_due_date_hiddenReminder');

                    if (trvt_date_hidden && trvt_due_date_hidden) {
                        var completed_date = flatpickr("#create_dateReminder", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_date_hidden.value || null,
                        });

                        var todatepicker = flatpickr("#due_dateReminder", {
                            locale: 'en',
                            altInput: true,
                            dateFormat: "m/d/Y",
                            altFormat: "m/d/Y",
                            allowInput: true,
                            defaultDate: trvt_due_date_hidden.value || null,
                        });

                        document.getElementById('create-date-iconReminder').addEventListener('click',
                            function() {
                                fromdatepicker.open();
                            });

                        document.getElementById('due-date-iconReminder').addEventListener('click',
                            function() {
                                todatepicker.open();
                            });
                    }


                });
            }
        });

        //delete record
        $('body').on('click', '.deleteTaskbtnreminder', function(e) {
            e.preventDefault();
            //  alert(trtm_id);
             var trvt_id = $(this).data("id");
            
                if (trvt_id) {
                    var url = "{{ route('masteradmin.taskdetails.destroy', ':trvt_id') }}";
                    url = url.replace(':trvt_id', trvt_id);
          
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(data) {
                    $('#example11').DataTable().ajax.reload();
                    $('#example15').DataTable().ajax.reload();
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message2').text('Data has been successfully Deleted!');
                    $('#task-success-modal2').modal('show');
                    $('.ajaxModelTask1').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask1').css('display', 'none');

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
                }
        });

      

        $('#trip_agent, #trip_traveler').on('change', function() {
            $('#example15').DataTable().ajax.reload();
        });

        $('.filter-text').on('click', function() {
            $('#trip_agent').val('').trigger('change'); 
            $('#trip_traveler').val('').trigger('change');
            $('#example15').DataTable().ajax.reload();
        });

    });
</script>

  @endsection
@endif
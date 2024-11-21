<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
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
                <table id="example15" class="table table-hover text-nowrap">
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
<?php //dd($task->trip->tr_id); ?>
<div class="modal fade" id="ajaxModelTask1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingTaskReminder"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form id="FormTask1" name="FormTask1" class="mt-6 space-y-6" enctype="multipart/form-data">

                <input type="hidden" name="trvt_id" id="trvt_idReminder">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
               

                    <div class="row pxy-15 px-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tr_idReminder">Trip Name<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="tr_idReminder"
                                        name="tr_id">
                                        <option default>Select Trip Name</option>
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
                                    <input type="text" class="form-control" id="trvt_nameReminder" name="trvt_name"
                                        placeholder="Enter Task">
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_name')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_agent_id">Assign Agent</label>
                                <div class="d-flex">
                                    <select id="trvt_agent_idReminder" style="width: 100%;" name="trvt_agent_id"
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
                                    <select class="form-control select2" style="width: 100%;" id="trvt_categoryReminder"
                                        name="trvt_category">
                                        <option default>Select Category</option>
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
                                    <select class="form-control select2" style="width: 100%;" id="trvt_priorityReminder"
                                        name="trvt_priority">
                                        <option default>Select Priority</option>
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
                                    <div class="input-group date" id="trvt_datereminder" data-target-input="nearest">
                                        <x-flatpickr id="create_dateReminder" name="trvt_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="create-date-iconReminder">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_date_hiddenReminder" />
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
                                    <div class="input-group date" id="trvt_due_dateReminder" data-target-input="nearest">
                                        <x-flatpickr id="due_dateReminder" name="trvt_due_date" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="due-date-iconReminder">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trvt_due_date_hiddenReminder" />
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
                                    <x-text-input type="file" name="trvt_document" id="trvt_document" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvt_document')" />
                                <p id="task_documentReminder"></p>
                                <label for="trvt_document">Only jpg, jpeg, png, and pdf files are allowed</label>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusFieldReminder" style="display: none;"> <!-- Initially hidden -->
                            <div class="form-group">
                                <label for="trvt_category">Status<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_statusReminder"
                                        name="status">
                                        <option default>Select Status</option>
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
                        <button type="submit" id="saveBtnTaskReminder" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="task-success-modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="task-success-message2">Data has been successfully inserted!</p>
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
        var table = $('#example15').DataTable();
        table.destroy();

        //list
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
                    table.draw();
                    $('#task-success-message2').text(tasksuccessMessage);
                    $('#task-success-modal2').modal('show');
                    $('#example15').DataTable().ajax.reload();
                    $('#ajaxModelTask1').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelTask1').css('display', 'none');
                    $('#saveBtnTaskReminder').html('Save');
                    $('#FormTask1')[0].reset();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtnTaskReminder').html('Save Changes');
                }
            });
        });

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
                    table.draw();
                    $('#task-success-message2').text('Data has been successfully Deleted!');
                    $('#task-success-modal2').modal('show');
                    $('.ajaxModelTask1').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask1').css('display', 'none');

                    // table.draw();
                    $('#example15').DataTable().ajax.reload();

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
                }
        });

      

        $('#trip_agent, #trip_traveler').on('change', function() {
            table.draw();
        });

        $('.filter-text').on('click', function() {
            $('#trip_agent').val('').trigger('change'); 
            $('#trip_traveler').val('').trigger('change');
            table.draw();
        });

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

    var fromdatepicker = flatpickr("#create_dateReminder", {
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

    document.getElementById('create-date-iconReminder').addEventListener('click', function () {
      fromdatepicker.open();
    });

    document.getElementById('due-date-iconReminder').addEventListener('click', function () {
      todatepicker.open();
    });


    });

  </script>
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
                <h4 class="modal-title" id="modelHeadingTask"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form id="FormTask" name="FormTask" class="mt-6 space-y-6" enctype="multipart/form-data">

                <input type="hidden" name="trvt_id" id="trvt_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_name">Task</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trvt_name" name="trvt_name"
                                        placeholder="Enter Task">
                                    <x-input-error class="mt-2" :messages="$errors->get('trvt_name')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trvt_agent_id">Assign Agent</label>
                                <div class="d-flex">
                                    <select id="trvt_agent_id" style="width: 100%;" name="trvt_agent_id"
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
                                    <select class="form-control select2" style="width: 100%;" id="trvt_category"
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
                                    <select class="form-control select2" style="width: 100%;" id="trvt_priority"
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
                                    <div class="input-group date" id="trvt_date" data-target-input="nearest">
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
                                    <div class="input-group date" id="trvt_due_date" data-target-input="nearest">
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
                                    <x-text-input type="file" name="trvt_document" id="trvt_document" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvt_document')" />
                                <p id="task_document"></p>
                                <label for="trvt_document">Only jpg, jpeg, png, and pdf files are allowed</label>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusField" style="display: none;"> <!-- Initially hidden -->
                            <div class="form-group">
                                <label for="trvt_category">Status<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_status"
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
                        <button type="submit" id="saveBtnTask" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
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
        $('#saveBtnTask').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            var formData = new FormData($('#FormTask')[0]);
            formData.append('_token', "{{ csrf_token() }}");

            var url = '';
            var method = 'POST';

            
            if ($('#trvt_id').val() === '') {
                url = "{{ $task && $task->trip ? route('masteradmin.task.store', $task->trip->tr_id) : '' }}";
            } else {
                var trvt_id = $('#trvt_id').val();
                url = "{{ $task && $task->trip ? route('masteradmin.task.update', [$task->trip->tr_id, ':trvt_id']) : '' }}";
                url = url.replace(':trvt_id', trvt_id);
                formData.append('_method', 'PATCH');
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
                    $('#example15').DataTable().ajax.reload();
                    $('#ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelTask').css('display', 'none');
                    $('#saveBtnTask').html('Save');
                    $('#FormTask')[0].reset();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtnTask').html('Save Changes');
                }
            });
        });

        //edit popup open
        $(document).on('click', '.editTask', function(e) {
            e.preventDefault();

        
            var id = $(this).data('id');
            var url = "{{ $task && $task->trip ? route('masteradmin.task.edit', ['id' => ':id', 'trip_id' => $task->trip->tr_id]) : '' }}";
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
                    $('#trvt_agent_id').val(data.trvt_agent_id).trigger('change.select2');
                    $('#trvt_category').val(data.trvt_category).trigger('change.select2');
                    $('#trvt_date').val(data.trvt_date);
                    $('#trvt_due_date').val(data.trvt_due_date);

                    $('#trvt_date_hidden').val(data.trvt_date);
                    $('#trvt_due_date_hidden').val(data.trvt_due_date);

                    $('#trvt_priority').val(data.trvt_priority).trigger('change.select2');

                    $('#task_document').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    if (data.trvt_document) {
                        $('#task_document').append(
                            '<a href="' + baseUrl + '{{ $userFolder }}/task_image/' + data
                            .trvt_document + '" target="_blank">' +
                            data.trvt_document +
                            '</a>'
                        );
                    }

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
            var trvt_id = $(this).data("id");
            //  alert(trtm_id);
             var trvt_id = $(this).data("id");
            var trip_id = "{{ $task && $task->trip ? $task->trip->tr_id : '' }}";
            
                if (trip_id) {
                var url = "{{ route('masteradmin.task.destroy', [':trip_id', ':trvt_id']) }}";
                url = url.replace(':trip_id', trip_id).replace(':trvt_id', trvt_id);
          
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function(data) {
                    alert(data.success);

                    $('.ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask').css('display', 'none');

                    table.draw();
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

    var fromdatepicker = flatpickr("#create_date", {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "d/m/Y",
      allowInput: true,
    });

    var todatepicker = flatpickr("#due_date", {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "d/m/Y",
      allowInput: true,
    });

    document.getElementById('create-date-icon').addEventListener('click', function () {
      fromdatepicker.open();
    });

    document.getElementById('due-date-icon').addEventListener('click', function () {
      todatepicker.open();
    });


    });

  </script>
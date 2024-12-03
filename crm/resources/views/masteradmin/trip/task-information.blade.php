<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Task</h3>
            </div>
            <div class="col-auto"><button href="javascript:void(0)" id="createNewTask" class="reminder_btn">Add
                    Task</button></div>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="TaskDataTable" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Create Date</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($task) > 0)
                    @foreach ($task as $taskvalue)
                    <tr>
                        <td>
                            <span data-toggle="tooltip" data-placement="top" title="{{$taskvalue->trvt_name}}">{{ \Illuminate\Support\Str::limit(strip_tags($taskvalue->trvt_name), 30, '...') }}</span>
                        </td>
                        <td>{{ $taskvalue->tripCategory->task_cat_name ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($taskvalue->trvt_date)->format('M d, Y')  ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($taskvalue->trvt_due_date)->format('M d, Y')  ?? '' }}</td>
                        <td>{{ $taskvalue->trvt_priority ?? '' }}</td>
                        <td>{{ $taskvalue->taskstatus->ts_status_name ?? '' }}</td>
                        <td>
                            <a data-id="{{$taskvalue->trvt_id}}" data-toggle="tooltip" data-original-title="Edit Role" class="editTask"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>
                            
                            <a data-toggle="modal" data-target="#delete-role-modal-{{$taskvalue->trvt_id}}">
                                <i class="fas fa-trash delete_icon_grid"></i>
                                <div class="modal fade" id="delete-role-modal-{{$taskvalue->trvt_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body pad-1 text-center">
                                                <i class="fas fa-solid fa-trash delete_icon"></i>
                                                <p class="company_business_name px-10"><b>Delete Task </b></p>
                                                <p class="company_details_text px-10">Are You Sure You Want to Delete This Task ?</p>
                                                <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="delete_btn px-15 deleteTaskbtn" data-id="{{$taskvalue->trvt_id}}">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr class="text-center"><td >No data found!</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModelTask" aria-hidden="true">
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
                                        class="form-control select2" disabled>
                                        <option value="">Select Agent</option>
                                        @foreach ($agency_user as $value)
                                            <option value="{{ $value->users_id }}"  {{ old('tr_agent_id', $trip->tr_agent_id ?? '') == $value->users_id ? 'selected' : '' }}>
                                                {{ $value->users_first_name ?? '' }} {{ $value->users_last_name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="selected_agent_id" name="selected_agent_id" value="{{ old('tr_agent_id', $trip->tr_agent_id ?? '') }}">

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

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trvt_note">Notes </label>
                                <div class="d-flex">
                                    <textarea type="text" class="form-control" id="trvt_note" placeholder="Enter Description or Notes" name="trvt_note"
                                    autofocus autocomplete="trvt_note"> {{ old('trvt_note') }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusField" style="display: none;"> <!-- Initially hidden -->
                            <div class="form-group">
                                <label for="trvt_category">Status<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvt_status"
                                        name="status">
                                        <option value="" default>Select Status</option>
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

<!-- Success Modal -->
<div class="modal fade" id="task-success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="task-success-message">Data has been successfully inserted!</p>
                <button type="button" class="add_btn px-15" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php

// dd($trip_id);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

<script>
    var TaskDataTable;

 

    $(document).ready(function() {
        
    //     function initializeTaskDataTable() {
    // if ($.fn.dataTable.isDataTable('#TaskDataTable')) {
    //     TaskDataTable.clear().draw();
    // }else{
    // Initialize DataTable
    setTimeout(function(){
            TaskDataTable = $('#TaskDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('masteradmin.task.index', $trip_id) }}",
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    }
                },
                columns: [
                    { data: 'trvt_name', name: 'trvt_name' },
                    { data: 'trvt_category', name: 'trvt_category' },
                    { data: 'trvt_date', name: 'trvt_date' },
                    { data: 'trvt_due_date', name: 'trvt_due_date' },
                    { data: 'trvt_priority', name: 'trvt_priority' },
                    { data: 'status_name', name: 'status_name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
    
},2000);  
 
    // }

        //create task
        $('#createNewTask').click(function() {
            $('#saveBtnTask').val("create-product");
            $('#trvt_id').val('');
            $('#FormTask')[0].reset();
            $('#modelHeadingTask').html("Add Task");
            $('body').addClass('modal-open');
            $('#task_document').html('');

            $('#statusField').hide(); // Hide status field during add

            var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask'));
            editModal.show();
        });

        //insert/update data
        $('#saveBtnTask').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            var formData = new FormData($('#FormTask')[0]);
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('trvt_agent_id', $('#selected_agent_id').val());
            

            var url = '';
            var method = 'POST'; // Default to POST for new tasks
            var tasksuccessMessage = '';
            if ($('#trvt_id').val() === '') {
                // Create new task
                url = "{{ route('masteradmin.task.store', $trip_id) }}";
                formData.append('_method', 'POST');
                tasksuccessMessage = 'Data has been successfully inserted!'; 
            } else {
                // Update existing task
                var trvt_id = $('#trvt_id').val();
                url = "{{ route('masteradmin.task.update', [$trip_id, ':trvt_id']) }}";
                url = url.replace(':trvt_id', trvt_id);
                formData.append('_method', 'PATCH');
                tasksuccessMessage = 'Data has been successfully updated!';
            }

            $.ajax({
                data: formData,
                url: url,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {

                    // TaskDataTable.ajax.reload();
                    $('#TaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message').text(tasksuccessMessage);
                    
                    $('#task-success-modal').modal('show');

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
            // alert(id);
            $.get("{{ route('masteradmin.task.edit', ['id' => 'id', 'trip_id' => $trip_id]) }}"
                .replace('id', id).replace('{{ $trip_id }}', '{{ $trip_id }}'),
                function(data) {

                    // console.log(data);
                    $('#modelHeadingTask').html("Edit Task");
                    $('#saveBtnTask').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById(
                        'ajaxModelTask'));
                    editModal.show();

                    $('#trvt_id').val(data.trvt_id);
                    $('#trvt_name').val(data.trvt_name);
                    $('#trvt_agent_id').val(data.trvt_agent_id).trigger('change.select2');
                    $('#trvt_category').val(data.trvt_category).trigger('change.select2');
                    $('#trvt_date').val(data.trvt_date);
                    $('#trvt_due_date').val(data.trvt_due_date);
                    $('#trvt_note').val(data.trvt_note);


                    // Show status field during edit
                    $('#statusField').show();
                    $('#trvt_status').val(data.status).trigger(
                        'change.select2'); // set the selected status



                    $('#trvt_date_hidden').val(data.trvt_date);
                    $('#trvt_due_date_hidden').val(data.trvt_due_date);

                    $('#trvt_priority').val(data.trvt_priority).trigger('change.select2');

                    $('#task_document').html('');
                    var baseUrl = "{{ config('app.image_url') }}";
                    if (data.trvt_document) {
                        $('#task_document').append(
                            '<a href="' + baseUrl + '/tasks/' +
                            data
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
        });

        //delete record
        $('body').on('click', '.deleteTaskbtn', function(e) {
            e.preventDefault();
            var trvt_id = $(this).data("id");
            //  alert(trtm_id);
            var url = "{{ route('masteradmin.task.destroy', [$trip_id, ':trvt_id']) }}";
            url = url.replace(':trvt_id', trvt_id);
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function(data) {
                    // alert(data.success);
                    $('#TaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message').text('Data has been successfully Deleted!');
                    
                    $('#task-success-modal').modal('show');


                    $('.ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask').css('display', 'none');


                },
                error: function(data) {
                    console.log('Error:', data);
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

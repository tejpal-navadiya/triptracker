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
                <table id="example11" class="table table-hover text-nowrap">
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
                                        class="form-control select2">
                                        <option disabled selected>Select Agent</option>
                                        @foreach ($agency_user as $value)
                                            <option value="{{ $value->users_id }}">
                                                {{ $value->users_first_name }}
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
                                    <x-text-input type="file" name="trvt_document" id="trvt_document"
                                        accept="image/*" class="" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvt_document')" />
                                <p id="task_document"></p>
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
        var table = $('#example11').DataTable();
        table.destroy();

        //list
        table = $('#example11').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.task.index', $trip->tr_id) }}",
                type: 'GET',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [{
                    data: 'trvt_name',
                    name: 'trvt_name'
                },
                {
                    data: 'trvt_category',
                    name: 'trvt_category'
                },
                {
                    data: 'trvt_date',
                    name: 'trvt_date'
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
                    data: 'status_name',
                    name: 'status_name',

                    // render: function(data, type, row) {
                    //     let statusIds = data.split(
                    //         ','); // Split the status string into an array
                    //     let buttons = '';

                    //     // Generate buttons based on status IDs
                    //     if (statusIds.includes('1')) {
                    //         buttons +=
                    //             '<button class="btn btn-info btn-sm">Task Request</button>';
                    //     }
                    //     if (statusIds.includes('2')) {
                    //         buttons +=
                    //             '<button class="btn btn-success btn-sm">Task Proposal</button>';
                    //     }
                    //     if (statusIds.includes('3')) {
                    //         buttons +=
                    //             '<button class="btn btn-warning btn-sm">Task Modification</button>';
                    //     }
                    //     if (statusIds.includes('4')) {
                    //         buttons +=
                    //             '<button class="btn btn-success btn-sm">Task Accepted</button>';
                    //     }
                    //     if (statusIds.includes('5')) {
                    //         buttons +=
                    //             '<button class="btn btn-secondary btn-sm">Task Sold</button>';
                    //     }
                    //     if (statusIds.includes('6')) {
                    //         buttons +=
                    //             '<button class="btn btn-danger btn-sm">Task Lost</button>';
                    //     }
                    //     // Add more conditions as needed for different status IDs

                    //     // Return the buttons HTML
                    //     return buttons ||
                    //         '<span>No Status</span>'; // Fallback if no button is created
                    // }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ]
        });

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

            var url = '';
            var method = 'POST'; // Default to POST for new tasks

            if ($('#trvt_id').val() === '') {
                // Create new task
                url = "{{ route('masteradmin.task.store', $trip->tr_id) }}";
                formData.append('_method', 'POST');
            } else {
                // Update existing task
                var trvt_id = $('#trvt_id').val();
                url = "{{ route('masteradmin.task.update', [$trip->tr_id, ':trvt_id']) }}";
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
            $.get("{{ route('masteradmin.task.edit', ['id' => 'id', 'trip_id' => $trip->tr_id]) }}"
                .replace('id', id).replace('{{ $trip->tr_id }}', '{{ $trip->tr_id }}'),
                function(data) {

                    // console.log(data);
                    $('#modelHeadingTask').html("Edit Task");
                    $('#saveBtnTask').val("edit-user");

                    var editModal = new bootstrap.Modal(document.getElementById(
                        'ajaxModelTask'));
                    editModal.show();

                    $('#trvt_id').val(data.trvt_id);
                    $('#trvt_name').val(data.trvt_name);
                    $('#trvt_agent_id').val(data.trvt_agent_id);
                    $('#trvt_category').val(data.trvt_category).trigger('change.select2');
                    $('#trvt_date').val(data.trvt_date);
                    $('#trvt_due_date').val(data.trvt_due_date);


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
                            '<a href="' + baseUrl + '{{ $userFolder }}/task_image/' +
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
            var url = "{{ route('masteradmin.task.destroy', [$trip->tr_id, ':trvt_id']) }}";
            url = url.replace(':trvt_id', trvt_id);
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

        document.getElementById('create-date-icon').addEventListener('click', function() {
            fromdatepicker.open();
        });

        document.getElementById('due-date-icon').addEventListener('click', function() {
            todatepicker.open();
        });


    });
</script>

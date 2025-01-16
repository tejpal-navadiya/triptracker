<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">View All Task</h3>
            </div>
        </div>
        <!-- /.card-header -->
       
            <div class="card-body">
                <div class="col-md-12 table-responsive pad_table">
                    <table id="allTaskDataTable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Trip Name</th>
                                <th>Trip Number</th>
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
                        <tbody id="filter_data">

                        </tbody>
                    </table>
                </div>

            </div>
      
    </div>
</div>
<?php //dd($task->trip->tr_id);
?>
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
                                <label for="tr_id">Trip Name</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="tr_id"
                                        name="tr_id">
                                        <option  value="" default>Select Trip Name</option>
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
                                    <select class="form-control select2" style="width: 100%;" id="trvt_priority"
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
                                    <textarea type="text" class="form-control" id="trvt_note" placeholder="Enter Notes" name="trvt_note"
                                    autofocus autocomplete="trvt_note">{{ old('trvt_note') }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6" id="statusField" style="display: none;"> <!-- Initially hidden -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function() {
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

    //     function initializeTaskDataTable() {
    // if ($.fn.dataTable.isDataTable('#allTaskDataTable')) {
    //     allTable.clear().draw();
    // }else{
        //datatable list
        // var allTable;
        //datatable list
       

        //}
   // }
        //create task
        $('#createNewTask').click(function() {
            $('#saveBtnTask').val("create-product");
            $('#trvt_id').val('');
            $('#FormTask')[0].reset();
            $('#modelHeadingTask').html("Add Task");
            $('body').addClass('modal-open');
            $('#task_document').html('');
            $('#trvt_category').val('').trigger('change.select2');
            $('#statusField').hide(); // Hide status field during add

            var editModal = new bootstrap.Modal(document.getElementById('ajaxModelTask'));
            editModal.show();
        });
        
        setTimeout(function(){
        //insert/update data
        $('#saveBtnTask').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
        

            var formData = new FormData($('#FormTask')[0]);
            // formData.append('_token', "{{ csrf_token() }}");

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
                // alert(trvt_id);
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
                    $('#allTaskDataTable').DataTable().ajax.reload();
                    $('#task-success-message').text(tasksuccessMessage);
                    $('#task-success-modal').modal('show');
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
        },2000);

        
      
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




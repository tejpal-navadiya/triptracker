<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Document</h3>
            </div>
            <div class="col-auto"><button href="javascript:void(0)" id="createNewDocument" class="reminder_btn">Add Document</button></div>
        </div>
    <!-- /.card-header -->
        <div class="card-body">
            <div class="col-md-12 table-responsive pad_table">
                <table id="example13" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Type</th>
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
<div class="modal fade" id="ajaxModelDocument" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modelHeadingDocument"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="FormDocument" name="FormDocument" class="mt-6 space-y-6" enctype="multipart/form-data">
                
                <input type="hidden" name="trvd_id" id="trvd_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trvd_name">Documents Type</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvd_name" name="trvd_name" >
                                        <option default>Select Documents Type</option>
                                        @foreach ($documentType as $type)
                                        <option value="{{ $type->docty_id }}">{{  $type->docty_name }} </option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvd_name')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trvm_id">Traveler<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trvm_id" name="trvm_id" >
                                        <option default>Select Traveler</option>
                                        @foreach ($tripTraveling as $member)
                                        <option value="{{ $member->trtm_id }}">{{  $member->trtm_first_name }} {{  $member->trtm_middle_name }} {{  $member->trtm_last_name }}</option>
                                        @endforeach
                                        <x-input-error class="mt-2" :messages="$errors->get('trvm_id')" />
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                            <label for="trvd_document">Upload Documents</label>
                                <div class="d-flex">
                                    <x-text-input type="file" name="trvd_document" id="trvd_document" accept="image/*" class="" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('trvd_document')" />
                                <p id="task_document"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtnDocument" value="create" class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

     

        var table12 = $('#example13').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('masteradmin.document.index', $trip->tr_id) }}",
                type: 'GET',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {data: 'trvd_document', name: 'trvd_document'},
                {data: 'trvd_name', name: 'trvd_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        //create task
        $('#createNewDocument').click(function () {
            $('#saveBtnDocument').val("create-product");
            $('#trvd_id').val('');
            $('#FormDocument')[0].reset();
            $('#modelHeadingDocument').html("Add Document");
            $('body').addClass('modal-open');
            $('#task_document').html('');
            var editModal = new bootstrap.Modal(document.getElementById('ajaxModelDocument'));
            editModal.show();
        });

         //insert/update data
         $('#saveBtnDocument').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            
               var formData = new FormData($('#FormDocument')[0]);
               formData.append('_token', "{{ csrf_token() }}");

                var url = '';
                var method = 'POST';  // Default to POST for new tasks

                if ($('#trvd_id').val() === '') {
                    // Create new
                    url = "{{ route('masteradmin.document.store', $trip->tr_id) }}";
                    formData.append('_method', 'POST');
                } else {
                    // Update existing
                    var trvd_id = $('#trvd_id').val();
                    url = "{{ route('masteradmin.task.update', [$trip->tr_id, ':trvd_id']) }}";
                    url = url.replace(':trvd_id', trvd_id);
                    formData.append('_method', 'PATCH');
                }
                        
            $.ajax({
                data: formData,
                url: url,
                type: method,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    table.draw();
                    $('#ajaxModelDocument').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModelDocument').css('display', 'none');
                    $('#saveBtnDocument').html('Save');
                    $('#FormDocument')[0].reset();
                    
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#FormDocument').html('Save Changes');
                }
            });
        });

        //edit popup open
        $(document).on('click', '.editTask', function (e) {
            e.preventDefault();
        
    
            var id = $(this).data('id'); 
            // alert(id);
            $.get("{{ route('masteradmin.task.edit', ['id' => 'id', 'trip_id' => $trip->tr_id]) }}".replace('id', id).replace('{{$trip->tr_id}}', '{{ $trip->tr_id }}'), function (data) {

                // console.log(data);
                $('#modelHeadingTask').html("Edit Task");
                $('#saveBtnTask').val("edit-user");

                var editModal = new bootstrap.Modal(document.getElementById('ajaxModelDocument'));
                editModal.show();

                $('#trvt_id').val(data.trvt_id);
                $('#trvt_name').val(data.trvt_name);
                $('#trvt_agent_id').val(data.trvt_agent_id);
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
                        '<a href="' + baseUrl + '{{ $userFolder }}/task_image/' + data.trvt_document + '" target="_blank">' +
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

                document.getElementById('create-date-icon').addEventListener('click', function () {
                fromdatepicker.open();
                });

                document.getElementById('due-date-icon').addEventListener('click', function () {
                todatepicker.open();
                });
            }
           
            
            });
        });

        //delete record
        $('body').on('click', '.deleteTaskbtn', function (e) {
            e.preventDefault();
            var trvt_id = $(this).data("id");
            //  alert(trtm_id);
            var url = "{{ route('masteradmin.task.destroy', [$trip->tr_id, ':trvt_id']) }}";
            url = url.replace(':trvt_id', trvt_id);
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                success: function (data) {
                    alert(data.success);
                  
                    $('.ajaxModelTask').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.ajaxModelTask').css('display', 'none');
                    
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }); 
    });
    
</script>

<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Traveling Member Information</h3>
            </div>
            <div class="col-auto"><button href="javascript:void(0)" id="createNew" class="reminder_btn">Add Traveling Member</button></div>
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
                        <tr>
                            <td>Hotel Booking</td>
                            <td>Hotel</td>
                            <td>May 1, 2024</td>
                            <td>Apr 1, 2024</td>
                            <td>Medium</td>
                            <td>Incomplete</td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Form" name="Form" class="mt-6 space-y-6" enctype="multipart/form-data">
                
                <input type="hidden" name="trtm_id" id="trtm_id">
                <ul id="update_msgList"></ul>
                <div class="modal-body">
                    <div class="row pxy-15 px-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="trtm_type_hidden" class="trtm_type_hidden" id="trtm_type_hidden" value="" />
                                <input type="radio" class="trtm_type" id="trtm_type_family" name="trtm_type" value="1" ><label for="trtm_type_family">Family Member</label>
                                <input type="radio" class="trtm_type" id="trtm_type_trip" name="trtm_type" value="2"><label for="trtm_type_trip">Trip Member</label>
                                @error('role_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trtm_first_name">First Name<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_first_name" name="trtm_first_name" placeholder="Enter First Name" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                            <label for="trtm_middle_name">Middle name</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_middle_name" name="trtm_middle_name" placeholder="Enter Middle name">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trtm_last_name">Last Name</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_last_name" name="trtm_last_name" placeholder="Enter Last Name" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                            <label for="trtm_nick_name">Nickname</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_nick_name" name="trtm_nick_name" placeholder="Enter Nickname" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                            <label for="trtm_relationship">Relationship<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_relationship" name="trtm_relationship" placeholder="Enter Relationship" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trtm_gender">Gender<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trtm_gender" name="trtm_gender" >
                                        <option default>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trtm_dob">Birthdate</label>
                                <div class="d-flex">
                                    <div class="input-group date" id="trtm_dob" data-target-input="nearest">
                                        <x-flatpickr id="traveler_date" name="trtm_dob" placeholder="mm/dd/yyyy" />
                                        <div class="input-group-append">
                                            <div class="input-group-text" id="traveler-date-icon">
                                                <i class="fa fa-calendar-alt"></i>
                                                <input type="hidden" id="trtm_dob_hidden"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="trtm_age">Age</label>
                                <div class="d-flex">
                                    <input type="text" name="trtm_age" class="form-control" aria-describedby="inputGroupPrepend" placeholder="Enter Age" id="trtm_age"  readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtn" value="create" class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function () {
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
                url: "{{ route('masteradmin.family-member.index', $trip->tr_id) }}",
                type: 'GET',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {
                    data: null,
                    name: 'trtm_full_name',
                    render: function(data, type, row) {
                        return row.trtm_first_name + ' ' + (row.trtm_middle_name ? row.trtm_middle_name : '') + ' ' + row.trtm_last_name;
                    }
                },
                {data: 'trtm_relationship', name: 'trtm_relationship'},
                {data: 'trtm_age', name: 'trtm_age'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
</script>
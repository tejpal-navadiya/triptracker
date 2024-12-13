<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">
<div class="card">
    <div class="col-lg-4 card-body3">
        <div class="card-body">
            <p class="company_business_name">Name :{{ $trip->tr_traveler_name ?? '' }}</p>
            <p class="company_business_name">Email Address : {{ $trip->tr_email ?? '' }}</p>
            <!-- <p class="company_business_name">Total Person : {{ $trip->tr_num_people ?? '' }}</p> -->
            <p class="company_business_name">Phone Number : {{ $trip->tr_phone ?? '' }}</p>
            <p class="company_business_name">Address : {{ $trip->city_name ?? '' }}{{ $trip->city_name && ($trip->state_name || $trip->country_name || $trip->tr_zip) ? ', ' : '' }}
                                                    {{ $trip->state_name ?? '' }}{{ $trip->state_name && ($trip->country_name || $trip->tr_zip) ? ', ' : '' }}
                                                    {{ $trip->country_name ?? '' }}{{ $trip->country_name && $trip->tr_zip ? ' ' : '' }}
                                                    {{ $trip->tr_zip ?? '' }}</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="card-title">Traveler Information</h3>
            </div>
            <div class="col-auto"><button href="javascript:void(0)" id="createNew" class="reminder_btn">Add Traveler</button></div>
        </div>
        <!-- /.card-header -->
        <div class="card-body1">
            <div class="col-md-12 table-responsive pad_table">
                
                <table id="tableTraveler" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>Age</th>
                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    @if (count($member) > 0)
                    @foreach ($member as $value)
                    <tr>
                        <td>{{ $value->trtm_first_name }}</td>
                        <td>{{ $value->trtm_relationship }}</td>
                        <td>{{ $value->trtm_age }}</td>

                        <td>
                            <a data-id="{{ $value->trtm_id}}" data-toggle="tooltip" data-original-title="Edit Role" class="editMember"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>

                            
                            <a data-toggle="modal" data-target="#delete-role-modal-{{$value->trtm_id}}">
                                         <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-{{$value->trtm_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                         <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                         <p class="company_business_name px-10"><b>Delete Traveling Member </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Traveling Member ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteMemberbtn" data-id="{{$value->trtm_id}}">Delete</button>
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
                        <div class="col-md-12 mb-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="hidden" name="trtm_type_hidden" class="trtm_type_hidden"
                                    id="trtm_type_hidden" value="" />
                                <input type="radio" class="trtm_type custom-control-input" id="trtm_type_family" name="trtm_type"
                                    value="1"><label for="trtm_type_family" class="custom-control-label">Family Member</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="trtm_type custom-control-input" id="trtm_type_trip" name="trtm_type"
                                    value="2"><label for="trtm_type_trip" class="custom-control-label">Trip Member</label>
                                @error('role_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trtm_first_name">First Name<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_first_name"
                                        name="trtm_first_name" placeholder="Enter First Name">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="trtm_middle_name">Middle name</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_middle_name"
                                        name="trtm_middle_name" placeholder="Enter Middle name">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trtm_last_name">Last Name</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_last_name" name="trtm_last_name"
                                        placeholder="Enter Last Name">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 family-member-field">
                            <div class="form-group">
                                <label for="trtm_nick_name">Nickname</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_nick_name"
                                        name="trtm_nick_name" placeholder="Enter Nickname">
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="trtm_relationship">Relationship<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="trtm_relationship"
                                        name="trtm_relationship" placeholder="Enter Relationship">
                                </div>
                            </div>
                        </div> -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trtm_relationship">Relationship</label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trtm_relationship"
                                        name="trtm_relationship">
                                        <option value="" default>Select Relationship</option>
                                        @foreach ($travelingrelationship as $value)
                                            <option value="{{ $value->rel_id }}">
                                                {{ $value->rel_name }}
                                            </option>
                                        @endforeach
                                        <div class="invalid-feedback" id="trtm_relationship_error" ></div>
                                    </select>
                                </div>
                            </div>
                        </div>               

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trtm_gender">Gender<span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select class="form-control select2" style="width: 100%;" id="trtm_gender"
                                        name="trtm_gender">
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
                                                <input type="hidden" id="trtm_dob_hidden" />
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
                                    <input type="text" name="trtm_age" class="form-control"
                                        aria-describedby="inputGroupPrepend" placeholder="Enter Age" id="trtm_age"
                                        readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveBtn" value="create"
                            class="add_btn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body pad-1 text-center">
                <i class="fas fa-check-circle success_icon"></i>
                <p class="company_business_name px-10"><b>Success!</b></p>
                <p class="company_details_text px-10" id="success-message">Data has been successfully inserted!</p>
                <button type="button" class="add_btn px-15" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function() {
        // $('#tableTraveler').DataTable();
        // return;
    });
     $(document).ready(function() {
        var tableTraveler;

        // function initializeDataTable() {
        //     if ($.fn.dataTable.isDataTable('#tableTraveler')) {
        //         tableTraveler.clear().draw();

        //     } else {

        setTimeout(function(){
                tableTraveler = $('#tableTraveler').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('masteradmin.family-member.index', $trip_id) }}",
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    columns: [
                        { data: 'trtm_full_name', name: 'trtm_full_name' },
                        { data: 'trtm_relationship', name: 'trtm_relationship' },
                        { data: 'trtm_age', name: 'trtm_age' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            },1000);  
            
   
        //create popup
        $('#createNew').click(function() {
            $('#saveBtn').val("create-product");
            $('#trtm_id').val('');
            $('#Form')[0].reset();
            $('#modelHeading').html("Add Traveler");
            $('body').addClass('modal-open');
            var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
            editModal.show();
        });

        //insert/update data
        $('#saveBtn').click(function(e) {

            e.preventDefault();
            $(this).html('Sending..');

            var url = '';
            var method = '';
            var successMessage = '';
            
            if ($('#trtm_id').val() === '') {
                // Add new data
                url = "{{ route('masteradmin.family-member.store', $trip_id) }}";
                method = "POST";
                successMessage = 'Data has been successfully inserted!'; 

            } else {
                var trtm_id = $('#trtm_id').val();
                var trip_id = '{{ $trip_id }}'; 
                var url =
                    "{{ route('masteradmin.family-member.update', [$trip_id, ':trtm_id']) }}";
                url = url.replace(':trtm_id', trtm_id);

                method = "PATCH";
                successMessage = 'Data has been successfully updated!';
            }

            $.ajax({
                data: $('#Form').serialize(),
                url: url,
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                dataType: 'json',
                success: function(data) {
                    if (data && data.success) {
                    $('#tableTraveler').DataTable().ajax.reload();
                    
                    // tableTraveler.ajax.reload();
                    $('#success-message').text(successMessage);
                    $('#success-modal').modal('show');
                    $('#ajaxModel').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModel').css('display', 'none');
                    $('#saveBtn').html('Save');
                    $('#Form')[0].reset();
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        //edit popup
        $('body').on('click', '.editMember', function() {
            var id = $(this).data('id');
            // alert(id);
            $.get("{{ route('masteradmin.family-member.edit', ['id' => 'id', 'trip_id' => $trip_id]) }}"
                .replace('id', id).replace('{{ $trip_id }}', '{{ $trip_id }}'),
                function(data) {
                    // console.log(data);
                    $('#modelHeading').html("Edit Traveler");
                    $('#saveBtn').val("edit-user");
                    var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
                    editModal.show();
                    $('#trtm_id').val(data.trtm_id);
                    $('#trtm_first_name').val(data.trtm_first_name);
                    $('#trtm_middle_name').val(data.trtm_middle_name);
                    $('#trtm_last_name').val(data.trtm_last_name);
                    $('#trtm_nick_name').val(data.trtm_nick_name);
                    $('#trtm_relationship').val(data.trtm_relationship);
                    $('#trtm_gender').val(data.trtm_gender).trigger('change.select2');
                    $('#trtm_dob_hidden').val(data.trtm_dob);
                    $('#trtm_age').val(data.trtm_age);
                    $('#trtm_type_hidden').val(data.trtm_type);
                    $(`input[name="trtm_type"][value="${data.trtm_type}"]`).prop('checked', true);

                    $('#trtm_type_hidden').val(data.trtm_type);
                    if ($('#trtm_type_hidden').val() == 1) {
                        $(`.family-member-field`).show();
                        $(`.trip-member-field`).hide();
                    } else if ($('#trtm_type_hidden').val() == 2) {
                        $(`.family-member-field`).hide();
                        $(`.trip-member-field`).show();
                    }

                    var trtm_dob = flatpickr("#traveler_date", {
                        locale: 'en',
                        altInput: true,
                        dateFormat: "m/d/Y",
                        altFormat: "m/d/Y",
                        allowInput: true,
                        defaultDate: trtm_dob_hidden.value || null,
                    });

                    document.getElementById('traveler-date-icon').addEventListener('click',
                        function() {
                            fromdatepicker.open();
                    });

                });
        });

        //delete record
        $('body').on('click', '.deleteMemberbtn', function(e) {
            e.preventDefault();
            var trtm_id = $(this).data("id");
            //  alert(trtm_id);
            var url = "{{ route('masteradmin.family-member.destroy', [$trip_id, ':trtm_id']) }}";
            url = url.replace(':trtm_id', trtm_id);
            // alert(url);
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function(data) {
                                       
                    $('#success-message').text('Data has been successfully Deleted!');
                    
                    $('#tableTraveler').DataTable().ajax.reload();
                    $('#success-modal').modal('show');

                    $('.modal').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('.modal').css('display', 'none');

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    });
</script>
<script>
    $('.family-member-field').hide();
    $('.trip-member-field').hide();

    $(document).on('change', '.trtm_type', function() {
        //   var rowId = $(this).closest('.item-row').attr('id').replace('row', '');
        if ($(this).val() == 1) {
            $(`.family-member-field`).show();
            $(`.trip-member-field`).hide();
        } else if ($(this).val() == 2) {
            $(`.family-member-field`).hide();
            $(`.trip-member-field`).show();
        }
    });

    // var numofpeople = document.querySelector('#tr_num_people');
    //   numofpeople.value = rowCount;

    var travelerdates = flatpickr(`#traveler_date`, {
        locale: 'en',
        altInput: true,
        dateFormat: "m/d/Y",
        altFormat: "m/d/Y",
        allowInput: true,
    });

    document.getElementById(`traveler-date-icon`).addEventListener('click', function() {
        // alert('jhk');
        travelerdates.open();
    });


    var birthdateInput = document.querySelector(`#traveler_date`);
    var ageInput = document.querySelector(`#trtm_age`);

    birthdateInput.addEventListener('change', function() {
        var birthdate = new Date(birthdateInput.value);
        var today = new Date();
        var age = today.getFullYear() - birthdate.getFullYear();
        var m = today.getMonth() - birthdate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        if (age < 0) {
            ageInput.value = 0;
            // alert("Invalid birthdate. Please enter a valid birthdate.");
        } else {
            ageInput.value = age;
        }
    });
</script>

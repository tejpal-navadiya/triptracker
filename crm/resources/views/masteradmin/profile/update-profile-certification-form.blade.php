<div class="card-header">
    <h3 class="card-title">{{ __('Certifications') }}</h3>
</div>
<?php //dd($user); ?>
<link rel="stylesheet" href="{{ url('public/vendor/flatpickr/css/flatpickr.css') }}">

@if (session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ __('Profile Updated successfully.') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div id="success_message"></div>
<div class="card-body2">
    <div id="CertificationList">
    </div>
   <div class="text-center py-3">
   @if (isset($access['add_certifications']) && $access['add_certifications'])
    <a class="btn add_btn" href="javascript:void(0)" id="createNewCertification"><i class="fas fa-plus add_plus_icon"></i> Add Certification</a>
    @endif
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
                <form id="certForm" name="certForm" class="mt-6 space-y-6" enctype="multipart/form-data">
                    
                    <input type="hidden" name="users_cert_id" id="users_cert_id">
                    <ul id="update_msgList"></ul>
                    <div class="modal-body">
                        <div class="row pxy-15 px-10">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-input-label for="users_cert_name" :value="__('Certification Name')"> <span
                                            class="text-danger">*</span></x-input-label>
                                    <x-text-input type="text" class="form-control" id="users_cert_name"
                                        placeholder="Enter Certification Name" name="users_cert_name" required autofocus
                                        autocomplete="users_cert_name"  />
                                        
                                    <x-input-error class="mt-2" :messages="$errors->get('users_cert_name')" />
                                </div>
                            </div>
                        </div>
                        <div class="row pxy-15 px-10">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_cert_person_name" :value="__('Person Name')" />
                                    <x-text-input type="text" class="form-control" id="users_cert_person_name"
                                        placeholder="Enter Person Name" name="users_cert_person_name" required autofocus
                                        autocomplete="users_cert_person_name" :value="old('users_cert_person_name', $user->users_cert_person_name ?? '')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_cert_person_name')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_cert_completed_date" :value="__('Date Completed')" />
                                    <div class="input-group date" id="users_cert_completed_date" data-target-input="nearest">
                                    
                                    <x-flatpickr id="completed_date" name="users_cert_completed_date" placeholder="Select a date" />
                                    <div class="input-group-append">
                                    <div class="input-group-text" id="completed-date-icon">
                                    <i class="fa fa-calendar-alt"></i>
                                    <input type="hidden" id="users_cert_completed_date_hidden" value="" />
                                    </div>
                                    </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('users_cert_completed_date')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_cert_expiration" :value="__('Expiration date')" />
                                    <div class="input-group date" id="users_cert_expiration" data-target-input="nearest">
                                    
                                    <x-flatpickr id="expiration_date" name="users_cert_expiration" placeholder="Select a date" />
                                    <div class="input-group-append">
                                    <div class="input-group-text" id="expiration-date-icon">
                                    <i class="fa fa-calendar-alt"></i>
                                    <input type="hidden" id="users_cert_expiration_date_hidden" value="" />
                                    </div>
                                    </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('users_cert_expiration')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_cert_desc" :value="__('Descriptions')" />
                                    <textarea type="number" class="form-control" id="users_cert_desc"
                                        placeholder="Select Descriptions" name="users_cert_desc" required autofocus
                                        autocomplete="users_cert_desc" :value="old('users_cert_desc', $user->users_cert_desc ?? '')" ></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('users_cert_desc')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="image" :value="__('Document Upload')" />
                                    <x-text-input type="file" name="image" id="image" accept="image/*" class="" />
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                    <p id="users_cert_document"></p>
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
</div>
 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script>
    $(document).ready(function () {
        fetchUserCertification();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //listing data
        function fetchUserCertification() {
            $.ajax({
                type: "GET",
                url: "{{ route('user-certification.index') }}",
                dataType: "json",
                success: function (response) {
                    // alert(response);
                    var baseUrl = "{{ config('app.image_url') }}";
                //    alert(baseUrl);
                    $('#CertificationList').empty();
                //    console.log(response);
                   if (response.users_certification && response.users_certification.length > 0) {
                    // Clear any existing data before appending new data
                  
                   
                    // Loop through each user in the response
                    response.users_certification.forEach(function(user, index) {
                       
                        // Dynamically generate HTML for each certification
                        let certHtml = `
                            <div class="row pad-5">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <strong>Certification Name :</strong>
                                        <p id="users_cert_name_${index}">${user.users_cert_name ?? ' - '}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Person Name :</strong>
                                        <p id="users_cert_person_name_${index}">${user.users_cert_person_name ?? ' - '}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Issue Date :</strong>
                                        <p id="users_cert_completed_date_${index}">${user.users_cert_completed_date ?? ' - '}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Expiration date :</strong>
                                        <p id="users_cert_expiration_${index}">${user.users_cert_expiration ?? ' - '}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Descriptions :</strong>
                                        <p id="users_cert_desc_${index}">${user.users_cert_desc ?? ' - '}</p>
                                    </div>
                                   <div class="col-md-6">
                                        <strong>Document :</strong>
                                        ${user.users_cert_document ? 
                                            `<a href="${baseUrl}/certification_images/${user.users_cert_document}" target="_blank">
                                                <div title="${user.users_cert_document}" class="ptm pbm">
                                                    ${user.users_cert_document}
                                                </div>
                                            </a>` : 
                                            '<div class="ptm pbm">-</div>'}
                                    </div>
                                    <div class="col-md-6">
                                        @if (isset($access['edit_certifications']) && $access['edit_certifications'])
                                        <button type="button" value="${user.users_cert_id}" class="btn btn-primary editCertbtn btn-sm">Edit</button>
                                        @endif
                                        @if (isset($access['delete_certifications']) && $access['delete_certifications'])
                                        <button type="button" value="${user.users_cert_id}" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletesubscription-plans_${user.users_cert_id}">Delete</button>
                                        @endif
                                        <!-- Modal for confirmation -->
                                        <div class="modal fade" id="deletesubscription-plans_${user.users_cert_id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_details_text px-10">Are You Sure you Want to Delete This Certification?</p>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger confirm-delete deleteCertbtn" data-id="${user.users_cert_id}" data-dismiss="modal">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        `;

                        // Append the generated HTML to the container
                        $('#CertificationList').append(certHtml);
                    });
                    }else{
                        $('#CertificationList').append(`
                            <div class="row pad-5">
                                <div class="col-md-12 text-center">
                                    <p>No certifications found.</p>
                                </div>
                            </div>
                        `);
                    }
                }
            });
        }

        //create popup
        $('#createNewCertification').click(function (e) {
            e.preventDefault();
            $('#saveBtn').val("create-product");
            $('#users_cert_id').val('');
            $('#certForm')[0].reset();
            $('#modelHeading').html("Add Certification");
            $('body').addClass('modal-open');
            $('#users_cert_document').html('');
            var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
            editModal.show();
        });

        //insert or update data
        $('#saveBtn').click(function (e) {
            $(this).html('Sending..');
            e.preventDefault();

            var formData = new FormData($('#certForm')[0]);
           formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                data: formData,
                url: "{{ route('user-certification.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    // Hide the modal 
                    $('#ajaxModel').modal('hide');
                    $('.modal-backdrop').hide();
                    $('body').removeClass('modal-open');
                    $('#ajaxModel').css('display', 'none');

                    fetchUserCertification();

                    $('#saveBtn').html('Save');
                    $('#certForm')[0].reset();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save '); 
                }
            });
        });
                //edit popup open
        $('body').on('click', '.editCertbtn', function (e) {
            e.preventDefault();
            var fromdatepicker = flatpickr("#completed_date", {
                locale: 'en',
                altInput: true,
                dateFormat: "m/d/Y",
                altFormat: "m/d/Y",
                allowInput: true,
            });

    
            var users_id = $(this).val(); 

            $.get("{{ route('user-certification.index') }}" + '/' + users_id + '/edit', function (data) {
                // console.log(data);
                $('#modelHeading').html("Edit Certification");
                $('#saveBtn').val("edit-user");

                $('#users_cert_id').val(data.users_cert_id);
                $('#users_cert_name').val(data.users_cert_name);
                $('#users_cert_person_name').val(data.users_cert_person_name);
                $('#users_cert_completed_date_hidden').val(data.users_cert_completed_date);
                $('#users_cert_expiration_date_hidden').val(data.users_cert_expiration);
             
                $('#users_cert_desc').val(data.users_cert_desc);
                $('#users_cert_document').html('');
                var baseUrl = "{{ config('app.image_url') }}";
                if (data.users_cert_document) {
                    $('#users_cert_document').append(
                        '<a href="' + baseUrl + '/certification_images/' + data.users_cert_document + '" target="_blank">' +
                        data.users_cert_document + 
                        '</a>'
                    );
                }
                                
                var completed_date_hidde = document.getElementById('users_cert_completed_date_hidden');
                var expiration_date = document.getElementById('users_cert_expiration_date_hidden');

                if (completed_date_hidde && expiration_date) {
                var completed_date = flatpickr("#completed_date", {
                locale: 'en',
                altInput: true,
                dateFormat: "m/d/Y",
                altFormat: "m/d/Y",
                allowInput: true,
                defaultDate: completed_date_hidde.value || null,
                });

                var todatepicker = flatpickr("#expiration_date", {
                locale: 'en',
                altInput: true,
                dateFormat: "m/d/Y",
                altFormat: "m/d/Y",
                allowInput: true,
                defaultDate: expiration_date.value || null,
                });

                document.getElementById('completed-date-icon').addEventListener('click', function () {
                fromdatepicker.open();
                });

                document.getElementById('expiration-date-icon').addEventListener('click', function () {
                todatepicker.open();
                });
            }
                var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
                editModal.show();
            
            });
        });

        //delete record
        $('body').on('click', '.deleteCertbtn', function (e) {
            e.preventDefault();
            var users_id = $(this).data("id");
            // alert(users_id);
            $.ajax({
                type: "DELETE",
                url: "{{ route('user-certification.store') }}"+'/'+users_id,
                success: function (data) {
                    alert(data.success);
                    fetchUserCertification();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });       
        
    });
    

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

    var fromdatepicker = flatpickr("#completed_date", {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "m/d/Y",
      allowInput: true,
    });

    var todatepicker = flatpickr("#expiration_date", {
      locale: 'en',
      altInput: true,
      dateFormat: "m/d/Y",
      altFormat: "m/d/Y",
      allowInput: true,
    });

    document.getElementById('completed-date-icon').addEventListener('click', function () {
      fromdatepicker.open();
    });

    document.getElementById('expiration-date-icon').addEventListener('click', function () {
      todatepicker.open();
    });


    });

  </script>


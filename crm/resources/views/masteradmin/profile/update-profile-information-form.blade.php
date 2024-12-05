<div class="card-header">
    <h3 class="card-title">{{ __('Personal Information') }}</h3>
</div>
<?php //dd($user); ?>

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
    <div class="row pad-5">
        <div class="col-md-6">
            <div class="form-group">
                <strong>First Name :</strong>
                <p id="users_first_name">{{$user->users_first_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Last Name :</strong>
                <p id="users_last_name">{{$user->users_last_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Email Address :</strong>
                <p id="users_email">{{$user->users_email ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Phone :</strong>
                <p id="users_phone">{{$user->users_phone ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Bio :</strong>
                <p id="users_bio">{{ $user->users_bio ?? ' - ' }}</p>
            </div>
        </div>


    </div>
    <div class="row py-20 px-10">
        <div class="col-md-12 text-center" id="edit-link-container">
            <!-- Edit link will be inserted here -->
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ __('Edit Personal Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('masteradmin.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('patch')
                    <ul id="update_msgList"></ul>
                    <input type="hidden" id="users_id" />
                    <div class="modal-body">
                        <div class="row pxy-15 px-10">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-input-label for="users_first_name" :value="__('First Name')"> <span
                                            class="text-danger">*</span></x-input-label>
                                    <x-text-input type="text" class="form-control" id="userss_first_name"
                                        placeholder="Enter First Name" name="users_first_name" required autofocus
                                        autocomplete="users_first_name"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_first_name')" />
                                </div>
                            </div>
                        </div>
                        <div class="row pxy-15 px-10">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_last_name" :value="__('Last Name')" />
                                    <x-text-input type="text" class="form-control" id="userss_last_name"
                                        placeholder="Enter Last Name" name="users_last_name" required autofocus
                                        autocomplete="users_last_name" :value="old('users_last_name', $user->users_last_name ?? '')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_last_name')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_email" :value="__('Email Address')" />
                                    <x-text-input type="email" class="form-control" id="userss_email"
                                        placeholder="Enter Email Address" name="users_email" required autofocus
                                        autocomplete="users_email" :value="old('users_email', $user->users_email ?? '')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_email')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_phone" :value="__('Phone')" />
                                    <x-text-input type="number" class="form-control" id="userss_phone"
                                        placeholder="Enter Phone" name="users_phone" required autofocus
                                        autocomplete="users_phone" :value="old('users_phone', $user->users_phone ?? '')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_phone')" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-input-label for="users_bio" :value="__('Bio')" />
                                    <x-text-input type="text" class="form-control" id="userss_bio"
                                        placeholder="Enter Bio" name="users_bio" required autofocus
                                        autocomplete="users_bio" :value="old('users_bio', $user->users_bio ?? '')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('users_bio')" />
                                </div>
                            </div>
                            
                          
                        </div>
                       
                        <div class="modal-footer">
                            <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="add_btn update_users">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        
       // alert('hiii');
        fetchUser();

        function fetchUser() {

            // alert('hii');
            $.ajax({
                type: "GET",
                url: "{{ route('masteradmin.profile.fetchUser') }}", 
                dataType: "json",
                success: function (response) {
                   // console.log(response);
                    if(response.users) {
                        // Set the user data in your HTML
                        $('p#users_first_name').text(response.users.users_first_name ?? ' - ');
                        $('p#users_last_name').text(response.users.users_last_name ?? ' - ');
                        $('p#users_email').text(response.users.users_email ?? ' - ');
                        $('p#users_phone').text(response.users.users_phone ?? ' - ');
                        $('p#users_bio').text(response.users.users_bio ?? ' - ');
                        $('div#edit-link-container').html(`
                            <button type="button" value="${response.users.users_id}" id="openModal" class="btn btn-primary editbtn btn-sm">Edit</button>
                        `);
                        
                    } else {
                        // Handle case if no user data is found (optional)
                        // console.log('No user data found');
                    }
                }
            });
        }
      
        $(document).on('click', '.editbtn', function (e) {
            e.preventDefault();
            var stud_id = $(this).val();
            // alert(stud_id);
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            var editStudentUrl = "{{ route('masteradmin.profile.edits', ['id' => ':id']) }}";
            var url = editStudentUrl.replace(':id', stud_id);
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModal').modal('hide');
                    } else {
                        // console.log(response.student.name);
                        $('#userss_first_name').val(response.users.users_first_name);
                        $('#userss_last_name').val(response.users.users_last_name);
                        $('#userss_email').val(response.users.users_email);
                        $('#userss_phone').val(response.users.users_phone);
                        $('#userss_bio').val(response.users.users_bio);
                        $('#users_id').val(stud_id);
                    }
                }
            });
            $('.btn-close').find('input').val('');

        });

        $(document).on('click', '.update_users', function (e) {
            e.preventDefault();

            $(this).text('Updating..');
            var id = $('#users_id').val();

            var data = {
                'users_first_name': $('#userss_first_name').val(),
                'users_last_name': $('#userss_last_name').val(),
                'users_email': $('#userss_email').val(),
                'users_phone': $('#userss_phone').val(),
                'users_bio': $('#userss_bio').val(),
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PATCH",
                url: "{{ route('masteradmin.profile.update') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status == 400) {
                        // Display validation errors
                        $('#update_msgList').html("").addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#update_msgList').append('<li>' + err_value + '</li>');
                        });
                        $('.update_users').text('Update');
                    } else {
                        $('#update_msgList').html("");
                        $('#success_message').addClass('alert alert-success').text(response.message);

                        $('#editModal').find('input').val('');
                        $('#editModal').find('textarea').val('');

                        $('#editModal').modal('hide');
                        $('.modal-backdrop').hide();
                        $('body').removeClass('modal-open');
                        $('#editModal').css('display', 'none');

                        $('#profileForm')[0].reset();

                        $('.update_users').text('Update');

                        fetchUser();  
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors
                    console.error('AJAX error:', status, error);
                    $('.update_users').text('Update'); // Reset the button text in case of failure
                }
            });
        });

    });

</script>


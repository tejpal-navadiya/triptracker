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
                <strong>Agency Name :</strong>
                <?php //dd($agencyuser); ?>
                <p id="agencyuser_agencies_name">{{ $agencyuser->user_agencies_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Host Of Franchise Name :</strong>
                <p id="agencyuser_franchise_name">{{ $agencyuser->user_franchise_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Consortia Name :</strong>
                <p id="agencyuser_consortia_name">{{$agencyuser->user_consortia_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>First Name :</strong>
                <p id="agencyuser_first_name">{{$agencyuser->user_first_name ?? ' - '}}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Last Name :</strong>
                <p id="agencyuser_last_name">{{ $agencyuser->user_last_name ?? ' - ' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <strong>Business Email Address  :</strong>
                <p id="agencyuser_email">{{ $agencyuser->user_email ?? ' - ' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <strong>Personal Email Address :</strong>
                <p id="agencyuser_personal_email">{{ $agencyuser->user_personal_email ?? ' - ' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <strong>Business Phone :</strong>
                <p id="agencyuser_business_phone">{{ $agencyuser->user_business_phone ?? ' - ' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Personal Phone :</strong>
                <p id="agencyuser_personal_phone">{{ $agencyuser->user_personal_phone ?? ' - ' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>IATA or CLIA Number  :</strong>
                <p id="agencyuser_iata_clia_number">{{ $agencyuser->user_iata_clia_number ?? ' - ' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Personal CLIA Number :</strong>
                <p id="agencyuser_clia_number">{{ $agencyuser->user_clia_number ?? ' - ' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong> Personal IATA Number :</strong>
                <p id="agencyuser_iata_number">{{ $agencyuser->user_iata_number ?? ' - ' }}</p>
            </div>
        </div>
       

    </div>
    <div class="row py-20 px-10">
        <div class="col-md-12 text-center" id="agency-edit-link-container">
            <!-- Edit link will be inserted here -->
        </div>
    </div>
    <div class="modal fade" id="editModalAgency" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ __('Edit Agency Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('masteradmin.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('patch')
                    <ul id="update_msgList"></ul>
                    <input type="hidden" id="user_id" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Agency Name</label>
                                <span class="text-danger">*</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_agencies_name') is-invalid @enderror"
                                        id="userss_agencies_name" name="user_agencies_name" placeholder="Enter Agencies Name *"
                                        value="{{ old('user_agencies_name') }}">
                                </div>
                                @error('user_agencies_name')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Host Of Franchise Name</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_franchise_name') is-invalid @enderror"
                                        id="userss_franchise_name" name="user_franchise_name" placeholder="Enter Host of Franchise Name"
                                        value="{{ old('user_franchise_name') }}">
                                </div>
                                @error('user_franchise_name')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Consortia Name</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_consortia_name') is-invalid @enderror"
                                        id="userss_consortia_name" name="user_consortia_name" placeholder="Enter Consortia Name"
                                        value="{{ old('user_consortia_name') }}">
                                </div>
                                @error('user_consortia_name')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">First Name</label>
                                <span class="text-danger">*</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_first_name') is-invalid @enderror"
                                        id="userss_first_name" name="user_first_name" placeholder="Enter First Name*"
                                        value="{{ old('user_first_name') }}">
                                </div>
                                @error('user_first_name')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Last Name</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_last_name') is-invalid @enderror"
                                        id="userss_last_name" name="user_last_name" placeholder="Enter Last Name"
                                        value="{{ old('user_last_name') }}">
                                </div>
                                @error('user_last_name')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Business Email Address</label>
                                <span class="text-danger">*</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-envelope"></span>
                                        </div>
                                    </div>
                                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" id="userss_email"
                                        name="user_email" placeholder="Business Email Address *" value="{{ old('user_email') }}">
                                </div>
                                @error('user_email')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_personal_email" class="form-label">Personal Email Address</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-envelope"></span>
                                        </div>
                                    </div>
                                    <input type="email" class="form-control @error('user_personal_email') is-invalid @enderror" id="userss_personal_email"
                                        name="user_personal_email" placeholder="Personal Email Address" value="{{ old('user_personal_email') }}">
                                </div>
                                @error('user_personal_email')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_business_phone" class="form-label">Business Phone</label>
                                <span class="text-danger">*</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-envelope"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_business_phone') is-invalid @enderror" id="userss_business_phone"
                                        name="user_business_phone" placeholder="Business Phone" value="{{ old('user_business_phone') }}">
                                </div>
                                @error('user_business_phone')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-xl-4">
                                <label for="user_personal_phone" class="form-label">Personal Phone</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-envelope"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_personal_phone') is-invalid @enderror" id="userss_personal_phone"
                                        name="user_personal_phone" placeholder="Personal Phone" value="{{ old('user_personal_phone') }}">
                                </div>
                                @error('user_personal_phone')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-xl-4">
                                <label for="user_iata_clia_number" class="form-label">IATA or CLIA Number</label>
                                <span class="text-danger">*</span>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-solid fa-info"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_iata_clia_number') is-invalid @enderror"
                                        id="userss_iata_clia_number" name="user_iata_clia_number" placeholder="Enter IATA or CLIA Number"
                                        value="{{ old('user_iata_clia_number') }}">
                                </div>
                                @error('user_iata_clia_number')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_clia_number" class="form-label">Personal CLIA Number</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-solid fa-info"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_clia_number') is-invalid @enderror"
                                    id="userss_clia_number" name="user_clia_number" placeholder="Enter Personal CLIA Number"
                                    value="{{ old('user_clia_number') }}">
                                </div>
                                @error('user_clia_number')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_iata_number" class="form-label">Personal IATA Number</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-solid fa-info"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_iata_number') is-invalid @enderror"
                                        id="userss_iata_number" name="user_iata_number" placeholder="Enter Personal IATA Number"
                                        value="{{ old('user_iata_number') }}">
                                </div>
                                @error('user_iata_number')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Address</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-map-marker-alt"></span>

                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('user_address') is-invalid @enderror"
                                        id="userss_address" name="user_address" placeholder="Enter Address"
                                        value="{{ old('user_address') }}">
                                </div>
                                @error('user_address')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Country</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-globe"></span> <!-- Icon for Country -->
                                        </div>
                                    </div>
                                    <select id="tr_country" name="user_country" class="form-control" style="width: 100%;">
                                        <option value="">Select Country</option>
                                        @foreach ($country as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_state')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">State</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-flag"></span>
                                        </div>
                                    </div>
                                    <select id="tr_state" name="user_state" class="form-control" style="width: 100%;">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                @error('user_state')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">City</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-regular fa-city"></span>
                                        </div>
                                    </div>
                                    <select class="form-control form-control select2" id="lib_city" name="user_city" autofocus>
                                        <option value="" selected>Select City</option>
                                        <!-- Cities will be populated here based on the selected state -->
                                    </select>
                                </div>
                                @error('user_city')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Zip</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-map-pin"></span>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control @error('user_zip') is-invalid @enderror"
                                        id="userss_zip" name="user_zip" placeholder="Enter Zip" value="{{ old('user_zip') }}">
                                </div>
                                @error('user_zip')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <label for="user_agencies_name" class="form-label">Agency Logo</label>
                                <div class="input-group">
                                    <input type="file" name="image" accept="image/*" class="form-control form-control-file">
                                </div>
                                <span style=" font-size: 12px; display: block; line-height: 15px; margin: -5px 0 10px 0;">Please upload
                                    a valid image file. Size of image should not be more than 2MB.</span>
                                @error('image')
                                    <div class="invalid-feedback mb-2">{{ $message }}</div>
                                @enderror
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
        fetchagencyUser();

        function fetchagencyUser() {

            // alert('hii');
            $.ajax({
                type: "GET",
                url: "{{ route('masteradmin.profile.agencyfetchUser') }}", 
                dataType: "json",
                success: function (response) {
                //    console.log(response);
                    if(response.agencyuser) {
                        // Set the user data in your HTML
                        $('p#agencyuser_agencies_name').text(response.agencyuser.user_agencies_name ?? ' - ');
                        $('p#agencyuser_franchise_name').text(response.agencyuser.user_franchise_name ?? ' - ');
                        $('p#agencyuser_consortia_name').text(response.agencyuser.user_consortia_name ?? ' - ');
                        $('p#agencyuser_first_name').text(response.agencyuser.user_first_name ?? ' - ');
                        $('p#agencyuser_last_name').text(response.agencyuser.user_last_name ?? ' - ');
                        $('p#agencyuser_email').text(response.agencyuser.user_email ?? ' - ');
                        $('p#agencyuser_personal_email').text(response.agencyuser.user_personal_email ?? ' - ');
                        $('p#agencyuser_business_phone').text(response.agencyuser.user_business_phone ?? ' - ');
                        $('p#agencyuser_personal_phone').text(response.agencyuser.user_personal_phone ?? ' - ');
                        $('p#agencyuser_iata_clia_number').text(response.agencyuser.user_iata_clia_number ?? ' - ');
                        $('p#agencyuser_clia_number').text(response.agencyuser.user_clia_number ?? ' - ');
                        $('p#agencyuser_iata_number').text(response.agencyuser.user_iata_number ?? ' - ');


                        $('div#agency-edit-link-container').html(`
                            <button type="button" value="${response.agencyuser.id}" id="openModal" class="btn btn-primary editbtnAgency btn-sm">Edit</button>
                        `);
                        
                    } else {
                        // Handle case if no user data is found (optional)
                        // console.log('No user data found');
                    }
                }
            });
        }
      
        $(document).on('click', '.editbtnAgency', function (e) {
            e.preventDefault();
            var stud_id12 = $(this).val();
            // alert(stud_id12);
            var editModal = new bootstrap.Modal(document.getElementById('editModalAgency'));
            editModal.show();
            var editStudentUrl = "{{ route('masteradmin.profile.agencyedits', ['id' => ':id']) }}";
            var url = editStudentUrl.replace(':id', stud_id12);
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    console.log(response.agencyuser);
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModalAgency').modal('hide');
                    } else {
                       
                        $('#userss_agencies_name').val(response.agencyuser.user_agencies_name);
                        $('#userss_franchise_name').val(response.agencyuser.user_franchise_name);
                        $('#userss_consortia_name').val(response.agencyuser.user_consortia_name);
                        $('#userss_first_name').val(response.agencyuser.user_first_name);
                        $('#userss_last_name').val(response.agencyuser.user_last_name);
                        $('#userss_email').val(response.agencyuser.user_email);
                        $('#userss_personal_email').val(response.agencyuser.user_personal_email);
                        $('#userss_business_phone').val(response.agencyuser.user_business_phone);
                        $('#userss_personal_phone').val(response.agencyuser.user_personal_phone);
                        $('#userss_iata_clia_number').val(response.agencyuser.user_iata_clia_number);
                        $('#userss_clia_number').val(response.agencyuser.user_clia_number);
                        $('#userss_iata_number').val(response.agencyuser.user_iata_number);
                    
                        $('#user_id').val(stud_id);
                    }
                }
            });
            $('.btn-close').find('input').val('');

        });

        $(document).on('click', '.update_users', function (e) {
            e.preventDefault();

            $(this).text('Updating..');
            var id = $('#user_id').val();

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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        // Handle change event for the country dropdown
        $('#tr_country').change(function() {
            var countryId = $(this).val();

            if (countryId) {
                $.ajax({
                    url: '{{ route('authregisterStates', ':countryId') }}'.replace(
                        ':countryId',
                        countryId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Clear the existing state options
                        $('#tr_state').empty();
                        $('#tr_state').append(
                            '<option value="">Select a State...</option>');

                        // Populate the state dropdown with new options
                        $.each(data, function(key, value) {
                            $('#tr_state').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching states: ' + textStatus);
                    }
                });
            } else {
                // Reset the state dropdown if no country is selected
                $('#tr_state').empty();
                $('#tr_state').append('<option value="">Select a State...</option>');
            }
        });
    });
</script>



<script>
    $(document).ready(function() {
        $('#tr_state').change(function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '{{ route('authregisterCities', ':stateId') }}'.replace(':stateId',
                        stateId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Clear the existing city options
                        $('#lib_city').empty();
                        $('#lib_city').append('<option value="">Select a City...</option>');

                        // Populate the city dropdown with new options
                        $.each(data, function(key, value) {
                            $('#lib_city').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching cities: ' + textStatus);
                    }
                });
            } else {
                // Reset the city dropdown if no state is selected
                $('#lib_city').empty();
                $('#lib_city').append('<option value="">Select a City...</option>');
            }
        });
    });
</script>



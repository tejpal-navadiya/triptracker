@extends('masteradmin.layouts.app')
<title>User Role | Trip Tracker</title>
@if (isset($access['view_role']) && $access['view_role'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('User Role') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('User Role') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_role']) && $access['add_role'])
                                    <a href="javascript:void(0)" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add User Role</button></a>
                                @endif
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    @if (Session::has('role-add'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('role-add') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('role-add');
                        @endphp
                    @endif
                    @if (Session::has('role-delete'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('role-delete') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('role-delete');
                        @endphp
                    @endif

                    <!-- Main row -->
                    <div class="card px-20">
                        <div class="card-body1">
                            <div class="col-md-12 table-responsive pad_table">
                                <table id="example9" class="table table-hover text-nowrap data-table">
                                    <thead>
                                        <tr>
                                            <th>Role Name</th>
                                            <th class="sorting_disabled" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card-->
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

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

                        <input type="hidden" name="role_id" id="role_id">
                        <ul id="update_msgList"></ul>
                        <div class="modal-body">
                            <div class="row pxy-15 px-10">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role_name">Role Name</label>
                                        <input type="text" class="form-control @error('role_name') is-invalid @enderror"
                                            id="role_name" name="role_name" placeholder="Enter Role Name"
                                            value="{{ old('role_name') }}" />
                                        @error('role_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                //datatable list
                var table = $('#example9').DataTable();
                table.destroy();

                table = $('#example9').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('user-role-details.index') }}",
                        type: 'GET',
                        data: function(d) {
                            d._token = "{{ csrf_token() }}";
                        }
                    },
                    columns: [{
                            data: 'role_name',
                            name: 'role_name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                //create popup
                // $('#createNew').click(function () {
                $('body').on('click', '#createNew', function() {
                    $('#saveBtn').val("create-product");
                    $('#role_id').val('');
                    $('#Form')[0].reset();
                    $('#modelHeading').html("Add User Role");
                    $('body').addClass('modal-open');
                    var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
                    editModal.show();
                });

                //insert data
                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Sending..');

                    var url = '';
                    var method = '';

                    if ($('#role_id').val() === '') {
                        // Add new data
                        url = "{{ route('user-role-details.store') }}";
                        method = "POST";
                    } else {
                        // Update existing data
                        var roleId = $('#role_id').val();
                        var url = "{{ route('masteradmin.role.update', ':roleId') }}";
                        url = url.replace(':roleId', roleId);

                        // Use PATCH method as required
                        var method = "PATCH";
                    }

                    $.ajax({
                        data: $('#Form').serialize(),
                        url: url,
                        type: method,
                        dataType: 'json',
                        success: function(data) {
                            table.draw();
                            $('#ajaxModel').modal('hide');
                            $('.modal-backdrop').hide();
                            $('body').removeClass('modal-open');
                            $('#ajaxModel').css('display', 'none');
                            $('#saveBtn').html('Save');
                            $('#Form')[0].reset();
                            $this.attr('disabled', true);
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Save Changes');
                        }
                    });
                });

                //edit popup
                $('body').on('click', '.editRole', function() {
                    var id = $(this).data('id');
                    $.get("{{ route('user-role-details.index') }}" + '/' + id + '/edit', function(data) {
                        // console.log(data);
                        $('#modelHeading').html("Edit User Role");
                        $('#saveBtn').val("edit-user");
                        var editModal = new bootstrap.Modal(document.getElementById('ajaxModel'));
                        editModal.show();
                        $('#role_id').val(data.role_id);
                        $('#role_name').val(data.role_name);


                    })
                });

                //delete record
                $('body').on('click', '.deleteRolebtn', function(e) {
                    e.preventDefault();
                    var role_id = $(this).data("id");
                    // alert(role_id);
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('user-role-details.store') }}" + '/' + role_id,
                        success: function(data) {
                            alert(data.success);

                            $('.modal').modal('hide');
                            $('.modal-backdrop').hide();
                            $('body').removeClass('modal-open');
                            $('.modal').css('display', 'none');

                            table.draw();

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });


            });
        </script>
    @endsection
@endif

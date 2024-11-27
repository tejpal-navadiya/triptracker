@extends('masteradmin.layouts.app')
<title>New User Role | Trip Tracker</title>
@if (isset($access['add_role']) && $access['add_role'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('New User Role') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('New User Role') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                <a href="#"><button class="add_btn_br">Cancel</button></a>
                                <a href="#"><button class="add_btn">Save</button></a>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->



            <section class="content px-10">
                <div class="container-fluid">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">New User Role</h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" id="roleForm" action="{{ route('masteradmin.role.store') }}">
                            @csrf
                            <div class="card-body2">
                                <div class="row pad-5">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="role_name">Role Name</label>
                                            <input type="text"
                                                class="form-control @error('role_name') is-invalid @enderror" id="role_name"
                                                name="role_name" placeholder="Enter Role Name"
                                                value="{{ old('role_name') }}" />
                                            @error('role_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row py-20 px-10">
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('masteradmin.role.index') }}" class="add_btn_br px-10">Cancel</a>
                                        <button type="submit" id="submitButton" class="add_btn px-10">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->


        <script>
            document.getElementById('roleForm').addEventListener('submit', function(e) {
                const submitButton = document.getElementById('submitButton');
                if (submitButton.disabled) {
                    // Prevent further form submission attempts
                    e.preventDefault();
                    return false;
                }

                // Disable the submit button to prevent multiple submissions
                submitButton.disabled = true;

                // Optionally show some feedback, like changing button text
                submitButton.innerText = 'Submitting...';
            });
        </script>
    @endsection
@endif

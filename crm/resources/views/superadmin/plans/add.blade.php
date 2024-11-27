<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trip Tracker | New subscription Plans</title>
    @include('layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0">Add Subscription Plan</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Add Subscription Plan</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('plans.index') }}" class="add_btn_br px-10">Cancel</a>
                            <button type="submit" form="yourForm" class="add_btn px-10">Save</button>

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
                            <h3 class="card-title">Add Subscription Plan</h3>
                        </div>

                        <form id="yourForm" method="POST" action="{{ route('plans.store') }}">
                            @csrf
                            <div class="card-body2">
                                <div class="row pad-5">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planname">Plan Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('sp_name') is-invalid @enderror"
                                                id="planname" name="sp_name" placeholder="Enter Name"
                                                value="{{ old('sp_name') }}">
                                            @error('sp_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planamount">Amount Monthy </label>
                                            <input type="number"
                                                class="form-control @error('sp_month_amount') is-invalid @enderror"
                                                id="planamount" name="sp_month_amount" placeholder="Enter Amount"
                                                value="{{ old('sp_month_amount') }}" min="0">
                                            @error('sp_month_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planamount">Amount Yearly </label>
                                            <input type="number"
                                                class="form-control @error('sp_year_amount') is-invalid @enderror"
                                                id="planamount" name="sp_year_amount" placeholder="Enter Amount"
                                                value="{{ old('sp_year_amount') }}" min="0">
                                            @error('sp_year_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planvalidity">Validity </label>
                                            <input type="number"
                                                class="form-control @error('sp_month') is-invalid @enderror"
                                                id="planvalidity" name="sp_month" placeholder="Enter Validity"
                                                value="{{ old('sp_month') }}" min="0">
                                            @error('sp_month')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="planvalidity">User</label>
                                            <input type="number"
                                                class="form-control @error('sp_user') is-invalid @enderror"
                                                id="sp_user" name="sp_user" placeholder="Enter Users"
                                                value="{{ old('sp_user') }}" min="0">
                                            @error('sp_user')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="plandescription">Description</label>
                                            <textarea id="plandescription" class="form-control @error('sp_desc') is-invalid @enderror" name="sp_desc" rows="3"
                                                placeholder="Description">{{ old('sp_desc') }}</textarea>
                                            @error('sp_desc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center py-20">
                                    <a href="{{ route('plans.index') }}" class="add_btn_br px-10">Cancel</a>
                                    <button type="submit" id="submitButton" class="add_btn px-10">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
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


    @include('layouts.footerlink')

    <script>
        document.getElementById('yourForm').addEventListener('submit', function(e) {
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

</body>

</html>

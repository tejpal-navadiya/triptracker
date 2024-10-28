@extends('masteradmin.layouts.app')

<title>Agency Details | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Agency Users') }}</h1>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">

                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('success');
                        @endphp
                    @endif


                    <section class="content px-10">
                        <div class="container-fluid">
                            <!-- card -->
                            <div class="card">



                                <!-- Main row -->
                                <div class="row">
                                    <!-- First Block -->
                                    <div class="col-md-6">

                                        <div class="card m-2 p-3">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>ID Number:</th>
                                                        <td>{{ $agency->user_agency_numbers }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Name:</th>
                                                        <td>{{ $agency->users_first_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Work Email Address:</th>
                                                        <td>{{ $agency->user_work_email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Personal Email Address:</th>
                                                        <td>{{ $agency->users_email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone Number:</th>
                                                        <td>{{ $agency->user_emergency_phone_number }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Second Block -->
                                    <div class="col-md-6">
                                        <div class="card m-2 p-3">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Emergency Contact Person:</th>
                                                        <td>{{ $agency->user_emergency_contact_person }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Emergency Phone Number:</th>
                                                        <td>{{ $agency->user_emergency_phone_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Emergency Email Address</th>
                                                        <td>{{ $agency->user_emergency_email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>User Role:</th>
                                                        <td>{{ $agency->role_id }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card m-2 p-3">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th>Address:</th>
                                                        <td>{{ $agency->users_address }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </section>
        @endsection
@endif

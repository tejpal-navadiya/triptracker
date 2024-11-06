@extends('masteradmin.layouts.app')
<title>Traveler Details | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Travelers') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Travelers') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['book_trip']) && $access['book_trip'])
                                    <a href="{{ route('masteradmin.travelers.create') }}" id="createNew"><button
                                            class="add_btn"><i class="fas fa-plus add_plus_icon"></i>Add
                                            Traveler</button></a>
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

                    <!-- Main row -->
                    <div class="card px-20">
                        <div class="card-body1">
                            <div class="col-md-12 table-responsive pad_table">
                                <table id="example1" class="table table-hover text-nowrap data-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trip as $value)
                                            <tr>
                                                <td>{{ $value->tr_traveler_name }}</td>
                                                <td>{{ $value->tr_email }}</td>
                                                <td>{{ $value->tr_phone }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($value->tr_address), 45, '...') }}
                                                <td>
                                                    <button type="button" class="btn btn-info">
                                                        {{ $value->trip_status->tr_status_name ?? '' }}</button>
                                                </td>

                                                <td>
                                                    <a href="{{ route('masteradmin.travelers.view', $value->tr_id) }}"><i
                                                            class="fas fa-regular fa-eye edit_icon_grid"></i></a>

                                                    <a href="{{ route('masteradmin.travelers.edit', $value->tr_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>

                                                    <a data-toggle="modal"
                                                        data-target="#delete-product-modal-{{ $value->sale_product_id }}"><i
                                                            class="fas fa-solid fa-trash delete_icon_grid"></i></a>

                                                    <div class="modal fade"
                                                        id="delete-product-modal-{{ $value->sale_product_id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                                            role="document">
                                                            <div class="modal-content">
                                                                <form id="delete-plan-form"
                                                                    action="{{ route('trip.destroy', $value->tr_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="modal-body pad-1 text-center">
                                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                                        <p class="company_business_name px-10"><b>Delete
                                                                                Trip</b></p>
                                                                        <p class="company_details_text">Are You Sure You
                                                                            Want to Delete This Traveler?</p>
                                                                        <button type="button" class="add_btn px-15"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="delete_btn px-15">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
    @endsection
@endif

@extends('masteradmin.layouts.app')
<title>Traveler Details | Trip Tracker</title>
@if (isset($access['list_traveler']) && $access['list_traveler'])
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
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Travelers') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_traveler']) && $access['add_traveler'])
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

                    @if (Session::has('error'))
                        <p class="text-danger"> {{ Session::get('error') }}</p>
                        @php
                            Session::forget('error');
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
                                            <!-- <th>Status</th> -->
                                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trip as $value)
                                            <tr>
                                                <td>{{ $value->trtm_first_name }}</td>
                                                <td>{{ $value->trtm_email }}</td>
                                                <td>{{ $value->trtm_number }}</td>
                                                <td>
                                                    {{ $value->city_name ?? '' }}{{ $value->city_name && ($value->state_name || $value->country_name || $value->trtm_zip) ? ', ' : '' }}
                                                    {{ $value->state_name ?? '' }}{{ $value->state_name && ($value->country_name || $value->trtm_zip) ? ', ' : '' }}
                                                    {{ $value->country_name ?? '' }}{{ $value->country_name && $value->trtm_zip ? ' ' : '' }}
                                                    {{ $value->trtm_zip ?? '' }}
                                                </td>

                                               
                                                <td>
                                                @if (isset($access['traveler_details']) && $access['traveler_details'])
                                                    <a href="{{ route('masteradmin.travelers.view', $value->trtm_id) }}"><i
                                                            class="fas fa-regular fa-eye edit_icon_grid"></i></a>
                                                @endif
                                                @if (isset($access['edit_traveler']) && $access['edit_traveler'])
                                                    <a href="{{ route('masteradmin.travelers.edit', $value->trtm_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>
                                                @endif
                                                @if (isset($access['delete_traveler']) && $access['delete_traveler'])
                                                    <a data-toggle="modal"
                                                        data-target="#delete-product-modal-{{ $value->trtm_id }}"><i
                                                            class="fas fa-solid fa-trash delete_icon_grid"></i></a>
                                                @endif
                                                    <div class="modal fade"
                                                        id="delete-product-modal-{{ $value->trtm_id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                                            role="document">
                                                            <div class="modal-content">
                                                                <form id="delete-plan-form"
                                                                    action="{{ route('masteradmin.travelers.destroy', $value->trtm_id) }}"
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

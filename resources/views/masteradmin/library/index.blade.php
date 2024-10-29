@extends('masteradmin.layouts.app')


<title>Library Details | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Library') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['book_trip']) && $access['book_trip'])
                                    <a href="{{ route('library.create') }}" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Library Item</button></a>
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
                                            <th>Category</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Currency</th>
                                            <th>Infomation</th>
                                            <th class="sorting_disabled" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($library as $value)
                                            <tr>
                                                <td>{{ $value->libcategory->lib_cat_name ?? '' }}</td>
                                                <td>{{ $value->lib_name }}</td>
                                                <td>{{ $value->city->name ?? '' }}, {{ $value->state->name ?? '' }},
                                                    {{ $value->country->name ?? '' }}</td>
                                                <td>{{ $value->currency->currency_symbol ?? '' }}
                                                    ({{ $value->currency->currency ?? '' }})
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($value->lib_basic_information), 45, '...') }}
                                                </td>

                                                <td>
                                                    <a href="{{ route('library.show', $value->lib_id) }}"><i
                                                            class="fas fa-regular fa-eye edit_icon_grid"></i></a>

                                                    <!-- <a href="{{ route('masteradmin.library.view') }}"><i
                                                                                                                                                        class="fas fa-regular fa-eye edit_icon_grid"></i></a> -->

                                                    <a href="{{ route('library.edit', $value->lib_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>



                                                    <a data-toggle="modal"
                                                        data-target="#delete-library-modal-{{ $value->lib_id }}">
                                                        <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                                    </a>

                                                    <div class="modal fade" id="delete-library-modal-{{ $value->lib_id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                                            role="document">
                                                            <div class="modal-content">

                                                                <form id="delete-plan-form"
                                                                    action="{{ route('library.destroy', $value->lib_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE') <!-- Spoofing DELETE method -->

                                                                    <div class="modal-body  pad-1 text-center">
                                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                                        <p class="company_business_name px-10"> <b>Delete
                                                                                Library</b></p>
                                                                        <p class="company_details_text">Are You Sure You
                                                                            Want to Delete This Library?</p>
                                                                        <button type="button" class="add_btn px-15"
                                                                            data-dismiss="modal">Cancel</button>
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

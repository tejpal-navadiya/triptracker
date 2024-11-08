@extends('masteradmin.layouts.app')
<!DOCTYPE html>
<title>Add Category | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Add Task Category') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Task Category') }}</li>
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
                            <h3 class="card-title">Add Category</h3>
                        </div>
                        <!-- /.card-header -->
                        <form id="libraryForm" method="POST" action="{{ route('task-category.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lib_cate_name">Category Name <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="task_cat_name"
                                                placeholder="Enter Name" name="task_cat_name" autofocus
                                                autocomplete="task_cat_name" value="{{ old('task_cat_name') }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('task_cat_name')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="task_cat_status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="task_cat_status" name="task_cat_status"
                                                autofocus>
                                                <option value="" disabled
                                                    {{ old('task_cat_status') ? '' : 'selected' }}>
                                                    Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Deactive</option>
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('task_cat_status')" />
                                        </div>
                                    </div>

                                </div>


                                <div class="row pxy-15 px-10">

                                    {{-- <div class="col-md-12" id="dynamic_field">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div> --}}

                                </div>

                                <div class="row py-20 px-10">
                                    <div class="col-md-12 text-center">
                                        <a href="{{ route('task-category.index') }}" class="add_btn_br px-10">Cancel</a>
                                        <button id="submitButton" type="submit" class="add_btn px-10">Save</button>
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
    @endsection
@endif

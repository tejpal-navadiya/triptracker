<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Category Details | Trip Tracker</title>
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
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Library Category') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Library Category') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                    <a href="{{ route('libraries-category.create') }}" id="createNew"><button
                                            class="add_btn"><i class="fas fa-plus add_plus_icon"></i>Add Library
                                            Category</button></a>
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
                                            <th>Category Name</th>
                                            <th data-orderable="false">Status</th>
                                            <th class="sorting_disabled" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($library_category as $value)
                                            <tr>
                                                <td>{{ $value->lib_cat_name }}</td>


                                                <td>
                                                    @if ($value->lib_cat_status == 1)
                                                        <button class="btn btn-success btn-sm">Active</button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm">Deactive</button>
                                                    @endif
                                                </td>

                                                <td>
                                                   

                                                    <!-- <a href="{{ route('masteradmin.library.view') }}"><i
                                                                                                                                                                                                                                                                                                                                                                        class="fas fa-regular fa-eye edit_icon_grid"></i></a> -->

                                                    <a href="{{ route('libraries-category.edit', $value->lib_cat_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>



                                                    <a data-toggle="modal"
                                                        data-target="#delete-library-modal-{{ $value->lib_cat_id }}">
                                                        <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                                    </a>

                                                    <div class="modal fade"
                                                        id="delete-library-modal-{{ $value->lib_cat_id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                                            role="document">
                                                            <div class="modal-content">

                                                                <form id="delete-plan-form"
                                                                    action="{{ route('libraries-category.destroy', $value->lib_cat_id) }}"
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


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
    @include('layouts.footerlink')
</body>

</html>

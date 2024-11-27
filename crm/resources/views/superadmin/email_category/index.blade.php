<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Category Details | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Email Category') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Category') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                    <a href="{{ route('email-categories.create') }}" id="createNew"><button
                                            class="add_btn"><i class="fas fa-plus add_plus_icon"></i>Add Email
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
                                            <th>Status</th>
                                            <th class="sorting_disabled" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($email_category as $value)
                                            <tr>
                                                <td>{{ $value->email_cat_name }}</td>


                                                <td>
                                                    @if ($value->email_cat_status == 1)
                                                        <button class="btn btn-success btn-sm">Active</button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm">Deactive</button>
                                                    @endif
                                                </td>

                                                <td>
                                                 
                                                            
                                                    <a href="{{ route('email-categories.edit', $value->email_cat_id) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>



                                                    <a data-toggle="modal"
                                                        data-target="#delete-email-modal-{{ $value->email_cat_id }}">
                                                        <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                                    </a>

                                                    <div class="modal fade"
                                                        id="delete-email-modal-{{ $value->email_cat_id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered"
                                                            role="document">
                                                            <div class="modal-content">

                                                                <form id="delete-plan-form"
                                                                    action="{{ route('email-categories.destroy', $value->email_cat_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE') <!-- Spoofing DELETE method -->

                                                                    <div class="modal-body  pad-1 text-center">
                                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                                        <p class="company_business_name px-10"> <b>Delete
                                                                                email</b></p>
                                                                        <p class="company_details_text">Are You Sure You
                                                                            Want to Delete This email?</p>
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

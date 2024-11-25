@extends('masteradmin.layouts.app')
<title>Email Template | Trip Tracker</title>
@if (isset($access['add_email_template']) && $access['add_email_template'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Email Template') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Template') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_email_template']) && $access['add_email_template'])
                                    <a href="{{ route('masteradmin.emailtemplate.create') }}"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Email Template</button></a>
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
                    @if (Session::has('email-template-delete'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('email-template-delete') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php
                            Session::forget('email-template-delete');
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
                                            <th>Title</th>
                                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @foreach ($EmailTemplate as $template)
                                        <?php //dd($template->category->email_cat_id ?? ''); ?>
                                            <tr>
                                                <td>{{ $template->emailcategory->email_cat_name ?? '' }}</td>
                                                <td>{{ $template->title }}</td>
                                                <td class="text-right">
                                                    <!-- Action buttons (Edit, Delete) -->
                                                  @if (isset($access['view_email_template']) && $access['view_email_template'])

                                                    <a href="{{ route('masteradmin.emailtemplate.edit', $template->email_tid) }}"><i
                                                            class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>
                                                    @endif
                                                    <form
                                                        action="{{ route('masteradmin.emailtemplate.destroy', $template->email_tid) }}"
                                                        method="POST" style="display:inline;"
                                                        id="delete-form-{{ $template->email_tid }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if (isset($access['delete_email_template']) && $access['delete_email_template'])
                                                        <a data-toggle="modal"
                                                            data-target="#delete-product-modal-{{ $template->email_tid }}">
                                                            <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                                        </a>
                                                       
                                                        <div class="modal fade"
                                                            id="delete-product-modal-{{ $template->email_tid }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <form id="delete-plan-form"
                                                                        action="{{ route('masteradmin.emailtemplate.destroy', $template->email_tid) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="modal-body pad-1 text-center">
                                                                            <i
                                                                                class="fas fa-solid fa-trash delete_icon"></i>
                                                                            <p class="company_business_name px-10"><b>Delete 
                                                                            Email Template</b></p>
                                                                            <p class="company_details_text">Are You Sure You
                                                                                Want to Delete This Email Template?</p>
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
                                                        @endif
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
@endif

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
        <div class="content-wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                        <div class="d-flex"> 
                            <h1 class="m-0">{{ __('Email Template') }}</h1>
                            <ol class="breadcrumb ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Template') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        </div>
                        <!-- <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                    <a href="{{ route('emails-templates.create') }}"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Email Template</button></a>
                            </ol>
                        </div> -->
                        <!-- /.col -->
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
                                <table id="emailTemplateList" class="table table-hover text-nowrap data-table">
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
                                                    <a href="{{ route('emails-templates.edit', $template->email_tid) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    <form
                                                        action="{{ route('emails-templates.destroy', $template->email_tid) }}"
                                                        method="POST" style="display:inline;"
                                                        id="delete-form-{{ $template->email_tid }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#delete-product-modal-{{ $template->email_tid }}">
                                                            Delete</button>

                                                        <div class="modal fade"
                                                            id="delete-product-modal-{{ $template->email_tid }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <form id="delete-plan-form"
                                                                        action="{{ route('emails-templates.destroy', $template->email_tid) }}"
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

<script>
$(document).ready(function () {
    $('#emailTemplateList').DataTable({
        dom: '<"mb-3"<l<fB>>>rt<"row"<i><p>>',
        buttons: [
            {
                text: '<i class="fas fa-plus"></i> Add Email Template',
                action: function (e, dt, node, config) {
                    window.location.href = "{{ route('emails-templates.create') }}";
                },
                className: 'add_btn'
            }
        ]
    });
});
</script>


<style type="text/css">
#emailTemplateList_length{ float: left; width: 50%; }

#emailTemplateList_filter{ float: left; width: 30%; }
</style>

    @include('layouts.footerlink')
</body>

</html>

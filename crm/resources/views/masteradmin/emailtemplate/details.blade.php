@extends('masteradmin.layouts.app')
<title>Email Template Details | Trip Tracker</title>
@if (isset($access['view_email_template']) && $access['view_email_template'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                        <div class="d-flex">    
                            <h1 class="m-0">{{ __('Email Template Details') }}</h1>
                            <ol class="breadcrumb ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Template Details') }}</li>
                            </ol>
                            </div>
                        </div><!-- /.col -->
                        <!-- <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_email_template']) && $access['add_email_template'])
                                    <a href="{{ route('masteradmin.emailtemplate.addemailtemplate') }}"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Email Template</button></a>
                                @endif
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
                                <table id="emailTemplateDetails" class="table table-hover text-nowrap data-table">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Lead Traveler</th>
                                            <th>Title</th>
                                            <th class="sorting_disabled text-right" data-orderable="false">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @foreach ($EmailTemplate as $template)
                                        <?php //dd($template->category->email_cat_id ?? ''); ?>
                                            <tr>
                                                <td>{{ $template->email_category->email_cat_name ?? '' }}</td>
                                                <td>{{ $template->lead_traveler->trtm_first_name ?? '' }}</td>
                                                <td>{{ $template->email_subject ?? '' }}</td>
                                                <td class="text-right">
                                                    <!-- Action buttons (Edit, Delete) -->
                                                  @if (isset($access['view_email_template']) && $access['view_email_template'])

                                                    <a href="{{ route('masteradmin.emailtemplate.viewemailtemplate', $template->emt_id) }}"><i
                                                    class="fas fa-eye edit_icon_grid"></i></a>
                                                    @endif
                                                    <form
                                                        action="{{ route('masteradmin.emailtemplate.destroyemailtemplate', $template->emt_id) }}"
                                                        method="POST" style="display:inline;"
                                                        id="delete-form-{{ $template->emt_id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if (isset($access['delete_email_template']) && $access['delete_email_template'])
                                                        <a data-toggle="modal"
                                                            data-target="#delete-email-modal-{{ $template->emt_id }}">
                                                            <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                                        </a>
                                                       
                                                        <div class="modal fade"
                                                            id="delete-email-modal-{{ $template->emt_id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <form id="delete-plan-form"
                                                                    action="{{ route('masteradmin.emailtemplate.destroyemailtemplate', $template->emt_id) }}"
                                                                    method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="modal-body pad-1 text-center">
                                                                            <i
                                                                                class="fas fa-solid fa-trash delete_icon"></i>
                                                                            <p class="company_business_name px-10"><b>Delete Email Template</b></p>
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

<script>
    $(document).ready(function () {
        $('#emailTemplateDetails').DataTable({
            @if (isset($access['add_email_template']) && $access['add_email_template'])       
            dom: '<"mb-3"<l<fB>>>rt<"row"<i><p>>',
            buttons: [
                {
                    text: '<i class="fas fa-plus add_plus_icon"></i> Add Email Template',
                    action: function (e, dt, node, config) {
                        window.location.href = "{{ route('masteradmin.emailtemplate.addemailtemplate') }}";
                    },
                    className: 'btn btn-primary add_btn'
                }
            ]
            @endif
        });
    });
</script>

<style type="text/css">
#emailTemplateDetails_length{ float: left; width: 50%; }

#emailTemplateDetails_filter{ float: left; width: 30%; }
</style>
    @endsection
@endif

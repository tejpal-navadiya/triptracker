@extends('masteradmin.layouts.app')

<title>Email Templates Details | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Email Templates') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Templates') }}</li>
                            </ol>
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


                    <!-- Main row -->
                    <div class="row">
                        <!-- First Block -->
                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Category:</th>
                                            <td>{{ $EmailTemplate->email_category->email_cat_name ?? '' }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Second Block -->
                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Subject:</th>
                                            <td>{{ $EmailTemplate->email_subject }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Lead Traveler:</th>
                                            <td>{{ $EmailTemplate->lead_traveler->tr_traveler_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                      
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-2 p-3">
                                Basic Infomation
                                <div class="card m-2 p-3">
                                    Infomation
                                    <p>
                                        <td>{{ strip_tags($EmailTemplate->email_text ?? '') }}</td>
                                    </p>
                                </div>
                            </div>

                            
                        </div>
                    </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    @endsection
@endif

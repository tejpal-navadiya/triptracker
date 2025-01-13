@extends('masteradmin.layouts.app')

<title>Email Templates Details | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Email Templates') }}</h1>
                            <ol class="breadcrumb  ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Email Templates') }}</li>
                            </ol>
                            </div>
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
                                            <td>{{ $EmailTemplate->email_subject ?? ''}}</td>
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
                                            <td>{{ $EmailTemplate->lead_traveler->trtm_first_name ?? '' }}</td>
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
                                    <p>
                                        <td>{!! html_entity_decode($EmailTemplate->email_text ?? '<p>No content available</p>', ENT_QUOTES, 'UTF-8') !!}</td>
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

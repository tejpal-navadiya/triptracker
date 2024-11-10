@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if (isset($access['workflow']) && $access['workflow'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">Trip Detail</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Trip Information</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content px-10">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Basic Information</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <p class="company_business_name">{{ $trip->tr_name ?? '' }}</p>
                                    <p class="company_details_text">
                                        {{ \Carbon\Carbon::parse($trip->tr_start_date ?? '')->format('M d, Y') }} -
                                        {{ \Carbon\Carbon::parse($trip->tr_end_date ?? '')->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header d-flex p-0 justify-content-center tab_panal">
                        <ul class="nav nav-pills p-2 tab_box">
                            <li class="nav-item"><a class="nav-link active" href="#Traveleroverview"
                                    data-toggle="tab">Traveler Information</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Agentinfo" data-toggle="tab">Agent
                                    Information</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Tasksinfo" data-toggle="tab">Tasks</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Documentsinfo" data-toggle="tab">Documents</a>
                            </li>
                            <!-- <li class="nav-item"><a class="nav-link" href="#Emailsinfo" data-toggle="tab">Related Emails</a>
                            </li> -->
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="tab-content px-20">
                        <div class="tab-pane active" id="Traveleroverview">
                            @include('masteradmin.trip.traveler-information')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="Agentinfo">
                            @include('masteradmin.trip.agent-information')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="Tasksinfo">
                            @include('masteradmin.trip.task-information')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="Documentsinfo">
                            @include('masteradmin.trip.document-information')
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    @endsection
@endif

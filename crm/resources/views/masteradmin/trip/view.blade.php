@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if (isset($access['details_trip']) && $access['details_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col">
                            <div class="d-flex">   
                            <h1 class="m-0">Trip Detail</h1>
                            <ol class="breadcrumb  ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Trip Information</li>
                            </ol>
                            </div>
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
                        @php
                            $activeTab = session('activeTab', 'Traveleroverview'); // Default to 'personalinformation'
                        @endphp
                        <ul class="nav nav-pills p-2 tab_box tab_box12">
                           

                            <li class="nav-item"><a class="nav-link {{ $activeTab == 'Traveleroverview' ? 'active' : '' }}" href="#Traveleroverview"
                                    data-toggle="tab">Household Information</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Agentinfo" data-toggle="tab">Agent
                                    Information</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Tasksinfo" data-toggle="tab">Tasks</a></li>
                            <li class="nav-item"><a class="nav-link" href="#Documentsinfo" data-toggle="tab">Documents</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#Emailsinfo" data-toggle="tab">Related Emails</a>
                            </li>
                            <li class="nav-item"><a class="nav-link {{ $activeTab == 'preferenceinfo' ? 'active' : '' }}" href="#preferenceinfo" data-toggle="tab">Preference</a>
                            </li>
                            <li class="nav-item"><a class="nav-link {{ $activeTab == 'TripDocumentinfo' ? 'active' : '' }}" href="#TripDocumentinfo" data-toggle="tab">Trip Document</a></li>

                        </ul>
                    </div><!-- /.card-header -->
                    <div class="tab-content tab-content12">
                        <div class="tab-pane {{ $activeTab == 'TripDocumentinfo' ? 'active' : '' }}" id="TripDocumentinfo">
                            @include('masteradmin.trip.trip-document-information')
                        </div>
                        <div class="tab-pane {{ $activeTab == 'Traveleroverview' ? 'active' : '' }}" id="Traveleroverview">
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
                        <div class="tab-pane" id="Emailsinfo">
                            @include('masteradmin.trip.email-information')
                        </div>
                        <div class="tab-pane {{ $activeTab == 'preferenceinfo' ? 'active' : '' }}" id="preferenceinfo">
                            @include('masteradmin.trip.preference-information')
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

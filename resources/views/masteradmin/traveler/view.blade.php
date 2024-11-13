@extends('masteradmin.layouts.app')
<title>View Trip | Trip Tracker</title>
@if(isset($access['book_trip']) && $access['book_trip'])
  @section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="m-0">Traveler Detail</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Traveler Information</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
      
        <div class="card-header d-flex p-0 justify-content-center tab_panal">
          <ul class="nav nav-pills p-2 tab_box">
            <li class="nav-item"><a class="nav-link active" href="#Traveleroverview" data-toggle="tab">Traveler Information</a></li>
            <li class="nav-item"><a class="nav-link" href="#Documentsinfo" data-toggle="tab">Documents</a></li>
            <li class="nav-item"><a class="nav-link" href="#Emailsinfo" data-toggle="tab">Trip History</a></li>
          </ul>
        </div><!-- /.card-header -->
          <div class="tab-content px-20">
            <div class="tab-pane active" id="Traveleroverview">
                @include('masteradmin.traveler.traveler-information')
            </div>            
            <!-- /.tab-pane -->
            <div class="tab-pane" id="Documentsinfo">
                @include('masteradmin.traveler.document-information')
            </div>
            <div class="tab-pane" id="Emailsinfo">
                @include('masteradmin.traveler.trip-history-information')
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
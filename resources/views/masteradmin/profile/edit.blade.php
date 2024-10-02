@extends('masteradmin.layouts.app')
<title>Personal Profile | Trip Tracker</title>
@section('content')
@if(isset($access['edit_profile']) && $access['edit_profile']) 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-12">
            <h1 class="m-0">Personal Profile</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Personal Profile</li>
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
          <div class="col-md-3">
            <div class="card">
              <div class="profile nav flex-column nav-tabs" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                @if(isset($access['edit_profile']) && $access['edit_profile']) 
                <a class="nav-link active" id="vert-tabs-personalinformation-tab" data-toggle="pill" href="#vert-tabs-personalinformation" role="tab" aria-controls="vert-tabs-personalinformation" aria-selected="true">Personal Information <i class="fas fa-angle-right right"></i></a>
                @endif
                @if(isset($access['change_password']) && $access['change_password']) 
                <a class="nav-link" id="vert-tabs-changepassword-tab" data-toggle="pill" href="#vert-tabs-changepassword" role="tab" aria-controls="vert-tabs-changepassword" aria-selected="false">Change Password <i class="fas fa-angle-right right"></i></a>
                @endif
                @if(isset($access['certifications']) && $access['certifications']) 
                <a class="nav-link" id="vert-tabs-certifications-tab" data-toggle="pill" href="#vert-tabs-certifications" role="tab" aria-controls="vert-tabs-certifications" aria-selected="false">Certifications <i class="fas fa-angle-right right"></i></a>
                @endif              
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="tab-content" id="vert-tabs-tabContent">
              <div class="tab-pane text-left fade show active" id="vert-tabs-personalinformation" role="tabpanel" aria-labelledby="vert-tabs-personalinformation-tab">
                <div class="card">
                  @include('masteradmin.profile.update-profile-information-form')
                </div>
              </div>
              <div class="tab-pane fade" id="vert-tabs-changepassword" role="tabpanel" aria-labelledby="vert-tabs-changepassword-tab">
                <div class="card">
                  @include('masteradmin.profile.update-password-form')
                </div>
              </div>

              <div class="tab-pane fade" id="vert-tabs-certifications" role="tabpanel" aria-labelledby="vert-tabs-certifications-tab">
                <div class="card">
                  @include('masteradmin.profile.update-profile-certification-form')
                </div>
              </div>
              
            </div>
          </div>
          <!-- /.col -->
        </div>
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

@endif
@endsection

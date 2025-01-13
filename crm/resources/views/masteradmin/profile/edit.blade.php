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
          <div class="col">
          <div class="d-flex">    
            <h1 class="m-0">Personal Profile</h1>
            <ol class="breadcrumb ml-auto">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Personal Profile</li>
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
        <div class="row">
        <div class="col-md-3">
          <div class="edit-profile-view-l">
              <div class="epv-top">
                  <div class="epv-top-thumb">
                      @if (Auth::guard('masteradmins')->user()->users_image)
                          <img src="{{ route('agencys.access', ['filename' => Auth::guard('masteradmins')->user()->users_image]) }}"
                              class="elevation-2 user_img" target="_blank">
                      @else
                          <img src="{{ url('public/dist/img/user2-160x160.jpg') }}" class="elevation-2 user_img"
                              alt="User Image">
                      @endif
                      <a href="#" class="edit-badge" data-toggle="modal" data-target="#uploadImageModal">
                          <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="17" cy="17" r="17" fill="#22CC7B"/>
                              <path d="M13.0652 13.0645H12.2782C11.8607 13.0645 11.4603 13.2303 11.1651 13.5255C10.8699 13.8207 10.7041 14.2211 10.7041 14.6385V21.7219C10.7041 22.1393 10.8699 22.5397 11.1651 22.8349C11.4603 23.1301 11.8607 23.2959 12.2782 23.2959H19.3615C19.779 23.2959 20.1793 23.1301 20.4745 22.8349C20.7697 22.5397 20.9356 22.1393 20.9356 21.7219V20.9348" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M20.1489 11.4902L22.51 13.8513M23.6001 12.7376C23.91 12.4277 24.0842 12.0072 24.0842 11.5689C24.0842 11.1305 23.91 10.7101 23.6001 10.4001C23.2901 10.0902 22.8697 9.91602 22.4313 9.91602C21.9929 9.91602 21.5725 10.0902 21.2626 10.4001L14.6396 16.9994V19.3605H17.0008L23.6001 12.7376Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                      </a>
                  </div>
              </div>
              <h5>{{ Auth::guard('masteradmins')->user()->users_first_name }} {{ Auth::guard('masteradmins')->user()->users_last_name }}<br><span>{{ Auth::guard('masteradmins')->user()->role_id == 0 ? 'Master Admin' : 'Agency User' }}</span></h5>
          </div>
        </div>
          <!-- Modal for Image Upload -->
          <div class="modal fade" id="uploadImageModal" tabindex="-1" role="dialog" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="uploadImageModalLabel">Upload Profile Image</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-body">
                              <div class="form-group">
                                  <label for="image">Select Image</label>
                                  <input type="file" name="image" id="image" accept="image/*" required>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Upload</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="edit-profile-view">
            <div class="profile nav" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                @if(isset($access['edit_profile']) && $access['edit_profile']) 
                <a class="nav-link active" id="vert-tabs-personalinformation-tab" data-toggle="pill" href="#vert-tabs-personalinformation" role="tab" aria-controls="vert-tabs-personalinformation" aria-selected="true">Personal Information </a>
                @endif
                @if(isset($access['change_password']) && $access['change_password']) 
                <a class="nav-link" id="vert-tabs-changepassword-tab" data-toggle="pill" href="#vert-tabs-changepassword" role="tab" aria-controls="vert-tabs-changepassword" aria-selected="false">Change Password </a>
                @endif
                @if(isset($access['certifications']) && $access['certifications']) 
                <a class="nav-link" id="vert-tabs-certifications-tab" data-toggle="pill" href="#vert-tabs-certifications" role="tab" aria-controls="vert-tabs-certifications" aria-selected="false">Certifications </a>
                @endif
                <!-- <a class="nav-link" id="vert-tabs-agencyinformation-tab" data-toggle="pill" href="#vert-tabs-agencyinformation" role="tab" aria-controls="vert-tabs-agencyinformation" aria-selected="true">Agency Information </a> -->
              </div>
            <div class="tab-content" id="vert-tabs-tabContent">
              <div class="tab-pane text-left fade show active" id="vert-tabs-personalinformation" role="tabpanel" aria-labelledby="vert-tabs-personalinformation-tab">
                <div>
                  @include('masteradmin.profile.update-profile-information-form')
                </div>
              </div>
              <div class="tab-pane fade" id="vert-tabs-changepassword" role="tabpanel" aria-labelledby="vert-tabs-changepassword-tab">
                <div>
                  @include('masteradmin.profile.update-password-form')
                </div>
              </div>

              <div class="tab-pane fade" id="vert-tabs-certifications" role="tabpanel" aria-labelledby="vert-tabs-certifications-tab">
                <div>
                  @include('masteradmin.profile.update-profile-certification-form')
                </div>
              </div>

              <!-- <div class="tab-pane fade" id="vert-tabs-agencyinformation" role="tabpanel" aria-labelledby="vert-tabs-agencyinformation-tab">
                <div>
                 
                </div>
              </div> -->
              
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

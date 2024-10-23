<!DOCTYPE html>

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
        <div class="card customcard">
          <div class="row">
            
            <!-- Category Dropdown Field -->
            <form action="{{ route('emailtemplate.store') }}" method="POST">
              @csrf 
              
              <!-- Category Dropdown -->
              <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category">
                  <option value="cancel">Cancel</option>
                  <option value="birthday">Birthday</option>
                  <option value="Final Payment">Final Payment</option> 
                  <option value="Anniversary">Anniversary</option>
                  <option value="Initial Contact">Initial Contact</option>
                  <option value="Princess Cruises">Princess Cruises</option>
                  <option value="ny Cruise Line">Any Cruise Line</option>
                  <option value="Carnival">Carnival</option>
                  <option value="Celebrity Cruises">Celebrity Cruises</option>
                  <option value="Cunard Cruises">Cunard Cruises</option>
                  <option value="Holland America Line">Holland America Line</option>
                  <option value="MSC Cruises">MSC Cruises</option> 
                  <option value="Oceania Cruises">Oceania Cruises</option>
                  <option value="Virgin Voyages">Virgin Voyages</option>
                  <option value="Royal Caribbean">Royal Caribbean</option>
                  <option value="Payments Email">Payments Email</option>
                  <option value="booking Email">booking Email</option>
                  <option value="Agency and Agent">Agency and Agent</option>
                  <option value="Home Email">Home Email</option>
                  <option value="Review Link">Review Link</option>
                  <option value="Agency Email">Agency Email</option>
                </select>
              </div>
              <div class="form-group">
                  <label for="category">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="">
              </div>

              <!-- Basic Information Textarea -->
              <x-input-label for="email_text" :value="__('Basic Information')" />
              <textarea class="form-control" id="email_text" name="email_text" placeholder="Enter Basic Information"></textarea>
              <x-input-error class="mt-2" :messages="$errors->get('lib_basic_information')" />

              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary mt-3">Save</button>
            </form>
            
          </div>
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

<script src="{{ url('public/js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea', // This will apply TinyMCE to all textareas
        menubar: false,
        plugins: 'code table lists image',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
    });
</script>
@endif
@endsection

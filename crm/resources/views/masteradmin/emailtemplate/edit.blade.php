
<!DOCTYPE html>

@extends('masteradmin.layouts.app')
<title>Email Template | Trip Tracker</title>
@section('content')
@if(isset($access['edit_profile']) && $access['edit_profile']) 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-12">
            <h1 class="m-0">Email Template</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
              <li class="breadcrumb-item active">Email Template</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content px-10">
      <div class="container-fluid">
      @if(Session::has('success'))
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
        <div class="card customcard">
          <div class="row">
            
            <!-- Category Dropdown Field -->
            <form action="{{ route('masteradmin.emailtemplate.update', $emailTemplate->email_tid) }}" method="POST">
              @csrf
              @method('patch')
              <!-- Category Dropdown -->
              <div class="form-group">
                  <label for="category">Category</label>
                  <select class="form-control" id="category" name="category" autofocus>
                  <option value="" disabled>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->email_cat_id }}"
                                                        {{ old('category', $emailTemplate->category) == $category->email_cat_id ? 'selected' : '' }}>
                                                        {{ $category->email_cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('email_category')" />
              </div>
                          <div class="form-group">
                  <label for="category">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{ $emailTemplate->title }}">
              </div>


              <!-- Basic Information Textarea -->
              <x-input-label for="email_text" :value="__('Basic Information')" />
              <textarea class="form-control" id="email_text" name="email_text" placeholder="Enter Basic Information">{{ $emailTemplate->email_text }}</textarea>
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


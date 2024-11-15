<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Email Template Details | Trip Tracker</title>
    @include('layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.sidebar')


        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                <div class="col-sm-12">
                    <h1 class="m-0">Edit Email Template</h1>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                    <li class="breadcrumb-item active">Edit Email Template</li>
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
                    <form action="{{ route('emails-templates.update', $emailTemplate->email_tid) }}" method="POST">
                    @csrf
                    @method('PUT')
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
                        <div class="row py-20 px-10">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('emails-templates.index') }}" class="add_btn_br px-10">Cancel</a>
                                <button id="submitButton" type="submit" class="add_btn px-10">Save</button>
                            </div>
                        </div>
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
        <script src="{{ url('public/js/tinymce/tinymce.min.js') }}"></script>
        <script>
            document.getElementById('emailForm').addEventListener('submit', function(e) {
                const submitButton = document.getElementById('submitButton');
                if (submitButton.disabled) {
                    // Prevent further form submission attempts
                    e.preventDefault();
                    return false;
                }

                // Disable the submit button to prevent multiple submissions
                submitButton.disabled = true;

                // Optionally show some feedback, like changing button text
                submitButton.innerText = 'Submitting...';
            });
        </script>

        <script>
            tinymce.init({
                selector: 'textarea', // This will apply TinyMCE to all textareas
                menubar: false,
                plugins: 'code table lists image',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
            });
        </script>
        <!-- ./wrapper -->
    @include('layouts.footerlink')
</body>

</html>

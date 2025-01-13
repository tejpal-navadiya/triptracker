<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Email Template Details | Trip Tracker</title>
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
                        <div class="col">
                        <div class="d-flex"> 
                            <h1 class="m-0">Add Email Template</h1>
                            <ol class="breadcrumb ml-auto">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Add Email Template</li>
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
                    <div class="card customcard">
                        <div class="row">
                            <!-- Category Dropdown Field -->
                            <form id="emailForm" action="{{ route('emails-templates.store') }}" method="POST">
                                @csrf

                                <!-- Category Dropdown -->
                                <div class="form-group">
                                <label for="tr_name">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category" name="category" autofocus>
                                                <option value="" disabled {{ old('email_category') ? '' : 'selected' }}>
                                                    Select Category</option>
                                                @foreach ($emailcategory as $category)
                                                    <option value="{{ $category->email_cat_id }}"
                                                        {{ old('email_category') === $category->email_cat_name ? 'selected' : '' }}>
                                                        {{ $category->email_cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('email_category')" />
                                </div>
                                <div class="form-group">
                                    <label for="category">Subject</label>
                                    <input type="text" class="form-control" id="title" name="title" value="">
                                </div>

                                <!-- Basic Information Textarea -->
                                <x-input-label for="email_text" :value="__('Basic Information')" />
                                <textarea class="form-control" id="email_text" name="email_text" placeholder="Enter Basic Information"></textarea>
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

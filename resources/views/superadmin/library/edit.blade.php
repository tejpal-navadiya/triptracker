<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Library Details | Trip Tracker</title>
    @include('layouts.headerlink')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.sidebar')

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Edit Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Edit Library') }}</li>
                            </ol>
                        </div><!-- /.col -->

                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->


            <section class="content px-10">
                <div class="container-fluid">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Library Item</h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('libraries.update', $library->lib_id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            @method('PUT') <!-- Spoof the PUT method -->

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Category<span class="text-danger">*</span></label>

                                            <select class="form-control" id="tr_category" name="lib_category" autofocus>
                                                <option value="" disabled>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->lib_cat_id }}"
                                                        {{ old('lib_category', $library->lib_category) == $category->lib_cat_id ? 'selected' : '' }}>
                                                        {{ $category->lib_cat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('lib_category')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tr_agent_id">Name<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="tr_agent_id"
                                                placeholder="Enter Name" name="lib_name" autofocus
                                                autocomplete="tr_agent_id" :value="old('tr_name', $library->lib_name ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tr_agent_id')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tag_name">Tag Name<span class="text-danger"></span></label>
                                            <x-text-input type="text" class="form-control" id="tag_name"
                                                placeholder="Enter Tag Name" name="tag_name" autofocus autocomplete="tag_name"
                                                :value="old('tag_name', $library->tag_name ?? '')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('tag_name')" />
                                        </div>
                                    </div>



                                </div>

                                <div class="row pxy-15 px-10">
                                </div>

                                <div class="row pxy-15 px-10">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-input-label for="tr_phone" :value="__('Basic Information')" />
                                            <textarea class="form-control" id="tr_phone" name="lib_basic_information" placeholder="Enter Basic Information"
                                                autofocus>
                                             {{ $library->lib_basic_information }}
                                            </textarea>
                                        </div>
                                    </div>


                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-input-label for="lib_image" :value="__('Image Upload')" />

                                        <!-- File input for uploading an image -->
                                        <input type="file" class="form-control" id="lib_image" name="lib_image[]"
                                            multiple />


                                        <!-- here -->

                                        <label for="trvd_document">Only jpg, jpeg, png, and pdf files are allowed</label>

                                    </div>
                                </div>



                                <div class="col-md-12" id="dynamic_field">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <div class="row py-20 px-10">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('libraries.index') }}" class="add_btn_br px-10">Cancel</a>
                                    <button type="submit" class="add_btn px-10">Save</button>
                                </div>
                            </div>
                    </div>
                    </form>

                    @if ($library->lib_image)
                        @php
                            // Decode the JSON to get an array of file paths
                            $files = json_decode($library->lib_image, true);
                        @endphp

                        @if (!empty($files) && is_array($files))
                            <div class="mt-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>File Preview</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Loop through files and display each as an image or PDF -->
                                        @foreach ($files as $file)
                                            <tr>
                                                <td>
                                                    @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                                        <!-- Display Image Preview -->
                                                        <a target="_blank" href="{{ config('app.image_url') }}/superadmin/library_image/{{ $file }}">
                                                        <img src="{{ config('app.image_url') }}/superadmin/library_image/{{ $file }}"
                                                            alt="Uploaded Image" class="img-thumbnail"
                                                            style="width: 100px; height: auto;">
                                                        </a>
                                                    @elseif (preg_match('/\.pdf$/i', $file))
                                                        <!-- Display PDF Preview -->
                                                        
                                                        <div class="embed-responsive embed-responsive-4by3"
                                                            style="max-width: 100px;">
                                                            <embed
                                                                src="{{ config('app.image_url') }}/superadmin/library_image/{{ $file }}"
                                                                type="application/pdf" class="embed-responsive-item" />
                                                        </div>
                                                        <a target="_blank" href="{{ config('app.image_url') }}/superadmin/library_image/{{ $file }}">View
                                                        </a>
                                                    @endif
                                                    </td>
                                                <td>
                                                    <!-- Delete Button -->
                                                    <form method="POST"
                                                        action="{{ route('libraries.image.delete', ['id' => $library->lib_id, 'image' => basename($file)]) }}"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No files available.</p>
                        @endif
                    @else
                        <p>No files available.</p>
                    @endif
                </div>
                <!-- /.card -->
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


        <script src="{{ url('public/vendor/flatpickr/js/flatpickr.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            tinymce.init({
                selector: 'textarea', // This will apply TinyMCE to all textareas
                menubar: false,
                plugins: 'code table lists image',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
            });
            tinymce.init({
                selector: 'textarea', // Change this selector based on your needs
                menubar: false,
                plugins: 'lists link image table',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table',
            });
        </script>


        <!-- ./wrapper -->
    @include('layouts.footerlink')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Details | Trip Tracker</title>
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
                            <h1 class="m-0">{{ __('Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Library') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                    <a href="{{ route('libraries.create') }}" id="createNew"><button class="add_btn"><i
                                    class="fas fa-plus add_plus_icon"></i>Add Library Item</button></a>
                            </ol>
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

                    <div class="col-lg-12 fillter_box">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <p class="m-0 filter-text"><i class="fas fa-solid fa-filter"></i>Filters</p>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <p class="m-0 float-sm-right filter-text">Clear Filters<i
                                    class="fas fa-regular fa-circle-xmark"></i></p>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-lg-2 col-1024 col-md-6 px-10">
                            <select id="lib_category" class="form-control select2" style="width: 100%;" name="lib_category">
                                <option value="" default >Choose Category</option>
                                @foreach ($librarycategory as $value)
                                    <option value="{{ $value->lib_cat_id }}">
                                        {{ $value->lib_cat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-1024 col-md-6 px-10">
                            <div class="input-group">
                                <input type="search" class="form-control" name="tag_name"  placeholder="Enter tag #" id="tag_name">
                                <div class="input-group-append" id="tag_name_submit">
                                    <button type="submit" class="btn btn add_btn" >
                                    <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>   
                        </div>   
                        
                    </div>

                </div>

                <div id="filter_data">
                    <div class="row">
                    @if(!empty($libraries) && $libraries->count())
                        @foreach ($libraries as $library)
                            <div class="col-lg-3">
                                <div class="libary-box">
                                    @if ($library->lib_image)
                                        @php
                                            $files = json_decode($library->lib_image, true);
                                        @endphp

                                        @if (!empty($files) && is_array($files) && isset($files[0]))
                                            @php
                                                $file = $files[0];
                                                $imageUrl =
                                                    config('app.image_path') . '/superadmin/library_image/' . $file;
                                            @endphp

                                            @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                                <img src="{{ $imageUrl }}" alt="Library Image"
                                                    class="libary-img img-fluid img-thumbnail">
                                            @elseif (preg_match('/\.pdf$/i', $file))
                                                <div class="embed-responsive embed-responsive-4by3">
                                                    <embed src="{{ $imageUrl }}" type="application/pdf"
                                                        class="embed-responsive-item">
                                                </div>
                                            @endif
                                        @else
                                            <img src="../public/dist/img/no_image.jpg" class="libary-img">
                                        @endif
                                    @else
                                        <img src="../public/dist/img/no_image.jpg" class="libary-img">
                                    @endif

                                    <p class="libary-name">{{ $library->lib_name }}</p>

                                    <div class="libary-btnbox">
                                        <a href="{{ route('libraries.show', $library->lib_id) }}" class="l-view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('libraries.edit', $library->lib_id) }}" class="l-edit-btn">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>
                                        <a href="#" data-toggle="modal"
                                            data-target="#delete-library-modal-{{ $library->lib_id }}"
                                            class="l-delete-btn">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="delete-library-modal-{{ $library->lib_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="deleteLibraryModalLabel{{ $library->lib_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('libraries.destroy', $library->lib_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body text-center">
                                                <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                                                <p><strong>Delete Library</strong></p>
                                                <p>Are you sure you want to delete this library?</p>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 colspan="10">There are no data.</h6>
                            </div>                            
                        </div>
                    </div>
                    @endif
                    </div>
                    <!-- Pagination Links -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            {{ $libraries->links() }} 
                        </div>
                    </div>
                </div>


                </div><!-- /.container-fluid -->
            </section>

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="Form" name="Form" class="mt-6 space-y-6" enctype="multipart/form-data">

                        <input type="hidden" name="role_id" id="role_id">
                        <ul id="update_msgList"></ul>
                        <div class="modal-body">
                            <div class="row pxy-15 px-10">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role_name">Role Name</label>
                                        <input type="text" class="form-control @error('role_name') is-invalid @enderror"
                                            id="role_name" name="role_name" placeholder="Enter Role Name"
                                            value="{{ old('role_name') }}" />
                                        @error('role_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="add_btn_br" data-dismiss="modal">Cancel</button>
                                <button type="submit" id="saveBtn" value="create"
                                    class="add_btn">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- /.content-wrapper -->


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
    @include('layouts.footerlink')
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        var defaultcategory = "";
        var defaulttagname = "";
        $('#lib_category').val(defaultcategory);
        $('#tag_name').val(defaulttagname);

        // Handle clicking on filter text to clear filters
        $('.filter-text').on('click', function() {
            clearFilters();
        });

        // Function to fetch filtered data with pagination
        function fetchFilteredData(url = '{{ route('libraries.index') }}') {
            var formData = {
                category: $('#lib_category').val(),
                tag_name: $('#tag_name').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: url, // Use dynamic URL for AJAX request
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filter_data').html(response); // Update the results container with the new filtered data
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                }
            });
        }

        // Attach change event handlers to filter inputs
        $('#lib_category, #tag_name').on('change keyup', function(e) {
            e.preventDefault();
            fetchFilteredData(); // Fetch filtered data without page reload
        });

        // Function to clear filters and reset pagination
        function clearFilters() {
            $('#lib_category').val('').trigger('change');
            $('#tag_name').val('').trigger('change');
            fetchFilteredData('{{ route('libraries.index') }}'); // Reset to the first page
        }

        // Handle pagination dynamically
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            
            let url = $(this).attr('href'); // Get the URL from the clicked pagination link
            let category = $('#lib_category').val(); // Get the selected category
            let tag_name = $('#tag_name').val(); // Get the entered tag name
            
            // Append the filter parameters to the pagination URL (if they exist)
            if (category) {
                url += `&category=${category}`;
            }
            if (tag_name) {
                url += `&tag_name=${tag_name}`;
            }

            fetchFilteredData(url); // Fetch paginated results with current filters
        });
    });
</script>

</html>

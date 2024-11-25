@extends('masteradmin.layouts.app')


<title>Library Details | Trip Tracker</title>
@if (isset($access['book_trip']) && $access['book_trip'])
    @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">{{ __('Library') }}</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{ __('Library') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['book_trip']) && $access['book_trip'])
                                    <a href="{{ route('library.create') }}" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Library Item</button></a>
                                @endif
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
                                    <button type="submit" class="btn btn-default" >
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
                                                    config('app.image_url').'/library_images/' . $file;
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
                                        <a href="{{ route('library.show', $library->lib_id) }}" class="l-view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('library.edit', $library->lib_id) }}" class="l-edit-btn">
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
                                        <form action="{{ route('library.destroy', $library->lib_id) }}" method="POST">
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
                        <div>
                            <h5 colspan="10">There are no data.</h5>
                        </div>
                        @endif
                    </div>

                    <!-- Pagination Links -->
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

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
    $(document).ready(function() {

        var defaultcategory = "";
        var defaulttagname = "";
       

        $('#lib_category').val(defaultcategory);

        $('#tag_name').val(defaulttagname);


        $('.filter-text').on('click', function() {
            clearFilters();
        });


        function fetchFilteredData() {
            var formData = {
                category: $('#lib_category').val(),
                tag_name: $('#tag_name').val(),
                _token: '{{ csrf_token() }}'
            };
            // alert('hii');

            $.ajax({
                url: '{{ route('library.index') }}',
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#filter_data').html(
                        response); 

                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    //alert('An error occurred while fetching data.');
                }
            });

        }

        // Attach change event handlers to filter inputs
        $('#lib_category, #tag_name').on('change keyup', function(e) {

            e.preventDefault();
            //   alert('hii');
            fetchFilteredData();
        });


        function clearFilters() {
            // Clear filters
            $('#lib_category').val('').trigger('change');
            $('#tag_name').val('').trigger('change');

          
        }


    });


    
</script>
    @endsection
@endif

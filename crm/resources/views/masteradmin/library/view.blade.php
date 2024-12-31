@extends('masteradmin.layouts.app')

<title>Library Details | Trip Tracker</title>
@if (isset($access['list_library']) && $access['list_library'])
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
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">{{ __('Library') }}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($access['add_library']) && $access['add_library'])
                                    <a href="{{ route('library.create') }}" id="createNew"><button class="add_btn"><i
                                                class="fas fa-plus add_plus_icon"></i>Add Library Item</button></a>
                                @endif
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->



                    <div class="row mb-2 align-items-center justify-content-between">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="lib_search" name="lib_search" class="form-control"
                                    placeholder="Search Library">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                        @if (isset($access['add_library']) && $access['add_library'])
                            <a href="{{ route('library.create') }}" class="btn btn-success" id="createNew">Create New
                                Library</a>
                        @endif
                        </div>
                    </div>



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
                        @foreach ($libraries as $library)
                            <div class="col-md-4"> <!-- Adjust the width as needed -->
                                <div class="card mb-3">

                                    @if ($library->lib_image)
                                        @php
                                            // Decode the JSON to get an array of image paths
                                            $images = json_decode($library->lib_image, true);
                                        @endphp

                                        @if (!empty($images) && is_array($images))
                                            <div class="mt-2">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Image Preview</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Display only the first image from the array -->
                                                        <tr>
                                                            <td>
                                                                <img src="{{ config('app.image_url') }}/library_images/{{ $images[0] }}"
                                                                    alt="Uploaded Image" class="img-thumbnail"
                                                                    style="max-width: 100px; height: auto;">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="card-body">

                                        <h5 class="card-title">{{ $library->lib_name }}</h5>

                                        <p class="card-text">{{ strip_tags($library->lib_basic_information) }}</p>
                                        @if (isset($access['library_item']) && $access['library_item'])
                                        <a href="{{ route('library.show', $library->lib_id) }}"
                                            class="btn btn-primary">View Details</a>
                                        @endif
                                        @if (isset($access['edit_library']) && $access['edit_library'])
                                        <a href="{{ route('library.edit', $library->lib_id) }}"
                                            class="btn btn-primary">Edit</a>
                                        @endif
                                        @if (isset($access['delete_library']) && $access['delete_library'])
                                        <a href="{{ route('library.destroy', $library->lib_id) }}"
                                            class="btn btn-primary">Delete</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#lib_search').on('keyup', function() {
                    let query = $(this).val();
                    // alert(query);
                    $.ajax({
                        url: '{{ route('masteradmin.library.search') }}', // Use the search route
                        type: "GET",
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#searchResults').html(data); // Update results in the div
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred: " + error);
                            $('#searchResults').html("<p>No results found.</p>");
                        }
                    });
                });
            });
        </script>
    @endsection
@endif

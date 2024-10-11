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

                    <!-- Main row -->
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
                                                        <!-- Loop through images and display one (or all if needed) -->
                                                        @foreach ($images as $image)
                                                            <tr>
                                                                <td>
                                                                    <!-- Display the image -->
                                                                    <img src="{{ config('app.image_url') }}{{ $userFolder }}/library_image/{{ $image }}"
                                                                        alt="Uploaded Image" class="img-thumbnail"
                                                                        style="max-width: 100px; height: auto;">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endif


                                    <div class="card-body">
                                        <h5 class="card-title">{{ $library->lib_name }}</h5>
                                        <p class="card-text">{{ $library->lib_basic_information }}</p>
                                        <a href="{{ route('library.show', $library->lib_id) }}"
                                            class="btn btn-primary">View Details</a>
                                        <a href="{{ route('library.edit', $library->lib_id) }}"
                                            class="btn btn-primary">Edit</a>
                                        <a href="{{ route('library.destroy', $library->lib_id) }}"
                                            class="btn btn-primary">Delete</a>
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
    @endsection
@endif

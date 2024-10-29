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
                        <!-- First Block -->
                        <div class="col-md-6">

                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Category:</th>
                                            <td>{{ $library->libcategory->lib_cat_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Currency:</th>
                                            <td>{{ $library->currency->currency_symbol ?? '' }}
                                                ({{ $library->currency->currency ?? '' }})</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Second Block -->
                        <div class="col-md-6">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $library->lib_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location:</th>
                                            <td>{{ $library->city->name ?? '' }}, {{ $library->state->name ?? '' }},
                                                {{ $library->country->name ?? '' }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-2 p-3">
                                Basic Infomation
                                <div class="card m-2 p-3">
                                    Infomation
                                    <p>
                                        <td>{{ strip_tags($library->lib_basic_information ?? '') }}</td>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card m-2 p-3">
                                    Sightseeing Infomation
                                    <div class="card m-2 p-3">
                                        <p>
                                            <td>{{ strip_tags($library->lib_sightseeing_information ?? '') }}</td>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="card m-2 p-3">
                                    Images
                                    @foreach ($libraries as $library)
                                        <div class="col-md-12"> <!-- Adjust the width as needed -->

                                            @if ($library->lib_image)
                                                @php
                                                    // Decode the JSON to get an array of image paths
                                                    $images = json_decode($library->lib_image, true);
                                                @endphp

                                                @if (!empty($images) && is_array($images))
                                                    <div class="mt-1">
                                                        <!-- Loop through images and display one (or all if needed) -->
                                                        @foreach ($images as $image)
                                                            <tr>
                                                                <td>
                                                                    <!-- Display the image -->
                                                                    <img src="{{ config('app.image_url') }}{{ $userFolder }}/library_image/{{ $image }}"
                                                                        alt="Uploaded Image" class="img-thumbnail"
                                                                        style="max-width: 220px; height: 200px;">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                @endif
                                            @endif
                                    @endforeach
                                </div>
                            </div>


                            <div class="row py-20 px-10">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('library.index') }}" class="add_btn_br px-10">Cancel</a>
                                    <button id="submitButton" type="submit" class="add_btn px-10">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    @endsection
@endif

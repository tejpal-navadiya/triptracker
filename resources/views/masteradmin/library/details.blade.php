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
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
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
                @if(Session::has('link-success'))
                <p class="text-success" > {{ Session::get('link-success') }}</p>
                @endif
                @if(Session::has('link-error'))
                <p class="text-danger" > {{ Session::get('link-error') }}</p>
                @endif
                
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
                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Category:</th>
                                            <td>{{ $library->libcategory->lib_cat_name ?? '' }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Second Block -->
                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $library->lib_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card m-2 p-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Tag:</th>
                                            <td>{{ $library->tag_name }}</td>
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
                                    <h4>Files</h4>

                                    @if ($library->lib_image)
                                        @php
                                            // Decode the JSON to get an array of file paths
                                            $files = json_decode($library->lib_image, true);
                                        @endphp

                                        @if (!empty($files) && is_array($files))
                                            <div class="d-flex flex-wrap mt-3">
                                                @foreach ($files as $file)
                                                    <div class="mr-3 mb-3 text-center">
                                                        @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                                            <!-- Display Image -->
                                                            <img src="{{ config('app.image_url') }}/library_images/{{ $file }}"
                                                                alt="Uploaded Image" class="img-thumbnail"
                                                                style="width: 100%; max-width: 200px;">
                                                        @elseif (preg_match('/\.pdf$/i', $file))
                                                            <!-- Display PDF as embedded viewer -->
                                                            <div class="embed-responsive embed-responsive-4by3">
                                                                <embed
                                                                    src="{{ config('app.image_url') }}/library_images/{{ $file }}"
                                                                    type="application/pdf" class="embed-responsive-item">
                                                            </div>
                                                        @endif
                                                        <!-- Download and Print Options -->
                                                        <div class="mt-2">
                                                            <a href="{{ config('app.image_url') }}/library_images/{{ $file }}"
                                                                class="btn btn-outline-primary btn-sm" download>
                                                                Download
                                                            </a>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No files available.</p>
                                        @endif
                                    @else
                                        <p>No files available.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- First Block -->
                                <div class="col-md-4">
                                    <div class="card m-2 p-3">
                                        <h4>Email send to Lead Traveler</h4>
                                        <form action="{{ route('masteradmin.library.send.email',$library->lib_id) }}" method="POST">
                                        @csrf
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="traveler_id">Lead Traveler</label>
                                                <select class="form-control" id="traveler_id" name="traveler_id" autofocus>
                                                    <option value="" > Select Lead Traveler </option>
                                                    @foreach ($lead_traveler as $value)
                                                        <option value="{{ $value->tr_id }}"  >
                                                            {{ $value->tr_traveler_name ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error class="mt-2" :messages="$errors->get('traveler_id')" />
                                            </div>
                                        </div>
                                        <div class="row py-20 px-10">
                                            <div class="col-md-12 text-center">
                                                <button id="submitButton" type="submit" class="add_btn px-10">Send</button>
                                            </div>
                                        </div>
                                        </form>

                                    </div>
                                </div>
                            </div>


                            <script>
                                function printFile(url) {
                                    const printWindow = window.open(url, '_blank');
                                    printWindow.print();
                                }
                            </script>
                            
                        </div>
                    </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    @endsection
@endif

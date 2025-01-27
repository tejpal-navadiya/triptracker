@extends('masteradmin.layouts.app')

<title>Library Details | Trip Tracker</title>
@if (isset($access['library_item']) && $access['library_item'])
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center justify-content-between">
                <div class="col">
                    <div class="d-flex">    
                    <h1 class="m-0">{{ __('Library') }}</h1>
                    <ol class="breadcrumb ml-auto">
                        <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">{{ __('Library') }}</li>
                    </ol>
                    </div>
                </div><!-- /.col -->
                <div class="col-auto">
                    <ol class="breadcrumb float-sm-right">
                        @if (isset($access['library_item']) && $access['library_item'])
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
                    <div class="card p-3">
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
                    <div class="card p-3">
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
                    <div class="card p-3">
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Basic Infomation</h3>
                        </div>                                
                        <div class="card-body">               
                            {!! html_entity_decode($library->lib_basic_information ?? '<p>No content available</p>', ENT_QUOTES, 'UTF-8') !!}
                            <!--{{ strip_tags($library->lib_basic_information ?? '') }}                                    -->
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="card">                            
                                <div class="card-header">
                                    <h3 class="card-title">Files</h3>
                                </div>                            

                            @if ($library->lib_image)
                            @php
                            // Decode the JSON to get an array of file paths
                            $files = json_decode($library->lib_image, true);
                            @endphp

                            @if (!empty($files) && is_array($files))
                            <div class="card-body">
                            <div class="d-flex flex-wrap">
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
                        <div class="card">
                            <div class="card-header">
                                    <h3 class="card-title">Email send to Lead Traveler</h3>
                                </div>
                                <div class="card-body">
                            <form action="{{ route('masteradmin.library.send.email',$library->lib_id) }}" method="POST">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="traveler_id">Lead Traveler</label>
                                        <select class="form-control" id="traveler_id" name="traveler_id" autofocus>
                                            <option value="" > Select Lead Traveler </option>
                                            @foreach ($lead_traveler as $value)
                                            <option value="{{ $value->trtm_id }}"  >
                                                {{ $value->trtm_first_name ?? '' }} {{ $value->trtm_last_name ?? '' }}
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

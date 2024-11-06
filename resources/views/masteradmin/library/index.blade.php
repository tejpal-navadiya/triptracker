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


                    @foreach ($library as $value)
                    @endforeach

                    {{-- <div class="row">
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

                                                    <tbody>
                                                        <!-- Display only the first image from the array -->
                                                        <tr>
                                                            <td>
                                                                <img src="{{ config('app.image_url') }}{{ $userFolder }}/library_image/{{ $images[0] }}"
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
                                        <p class="card-text"></p>
                                        <a href="{{ route('library.show', $value->lib_id) }}"><i
                                                class="fas fa-regular fa-eye edit_icon_grid"></i></a>


                                        <a href="{{ route('library.edit', $value->lib_id) }}"><i
                                                class="fas fa-solid fa-pen-to-square edit_icon_grid"></i></a>

                                        <a data-toggle="modal" data-target="#delete-library-modal-{{ $value->lib_id }}">
                                            <i class="fas fa-solid fa-trash delete_icon_grid"></i>
                                        </a>

                                        <div class="modal fade" id="delete-library-modal-{{ $value->lib_id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                            aria-hidden="true">

                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">

                                                    <form id="delete-plan-form"
                                                        action="{{ route('library.destroy', $value->lib_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE') <!-- Spoofing DELETE method -->

                                                        <div class="modal-body  pad-1 text-center">
                                                            <i class="fas fa-solid fa-trash delete_icon"></i>
                                                            <p class="company_business_name px-10"> <b>Delete
                                                                    Library</b></p>
                                                            <p class="company_details_text">Are You Sure You
                                                                Want to Delete This Library?</p>
                                                            <button type="button" class="add_btn px-15"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="delete_btn px-15">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}




                    <div class="row">
                        @foreach ($libraries as $library)
                            <div class="col-md-3">
                                <div class="card mb-4">
                                    @if ($library->lib_image)
                                        @php
                                            $files = json_decode($library->lib_image, true);
                                        @endphp

                                        @if (!empty($files) && is_array($files))
                                            <div class="mt-2">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        @foreach ($files as $file)
                                                            <tr>
                                                                <td class="text-center" style="width: 80px;">
                                                                    @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                                                                        <!-- Display the image -->
                                                                        <img src="{{ config('app.image_url') }}{{ $userFolder }}/library_image/{{ $file }}"
                                                                            alt="Uploaded Image"
                                                                            class="img-fluid img-thumbnail w-100">
                                                                    @elseif (preg_match('/\.pdf$/i', $file))
                                                                        <!-- Display the PDF with an embed tag -->
                                                                        <div class="embed-responsive embed-responsive-4by3">
                                                                            <embed
                                                                                src="{{ config('app.image_url') }}{{ $userFolder }}/library_image/{{ $file }}"
                                                                                type="application/pdf"
                                                                                class="embed-responsive-item">
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="title m-auto p-auto">
                                                <button type="button"
                                                    class="btn btn-primary">{{ $library->lib_name }}</button>
                                            </div>

                                        </div>
                                        <hr class="my-2">

                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('library.show', $library->lib_id) }}" class="text-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('library.edit', $library->lib_id) }}" class="text-warning">
                                                <i class="fas fa-pen"></i> Edit
                                            </a>
                                            <a href="#" data-toggle="modal"
                                                data-target="#delete-library-modal-{{ $library->lib_id }}"
                                                class="text-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="delete-library-modal-{{ $library->lib_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    </div>




                    <!-- /.row (main row) -->
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
                                        <input type="text"
                                            class="form-control @error('role_name') is-invalid @enderror" id="role_name"
                                            name="role_name" placeholder="Enter Role Name"
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

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
    @endsection
@endif

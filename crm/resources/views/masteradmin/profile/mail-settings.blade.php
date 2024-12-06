@extends('masteradmin.layouts.app')
<title>Update Mail Credentials | Trip Tracker</title>
@section('content')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0">Update Mail Credentials</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('masteradmin.home') }}">Analytics</a></li>
                                <li class="breadcrumb-item active">Update Mail Credentials</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <!-- <a href="{{ route('businessdetails.index') }}" class="add_btn_br px-10">Cancel</a> -->
                            <button type="submit" form="bdetails" class="add_btn px-10">Save</button>
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

                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Update Mail Credentials</h3>
                        </div>
                        <!-- /.card-header -->
                        <form id="bdetails" method="POST" action="{{ route('masteradmin.updatemailsetting') }}"  enctype="multipart/form-data">
                            @csrf

                            <div class="card-body2">
                                <div class="row pxy-15 px-10">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_username">Mail Username<span
                                            class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_username"
                                                placeholder="Enter Mail Username" name="mail_username" autofocus
                                                autocomplete="mail_username"
                                                value="{{ $mail->mail_username ?? '' }}"  />

                                            <x-input-error class="mt-2" :messages="$errors->get('mail_username')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_password"> Mail Password <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_password"
                                                placeholder="Enter Mail Password" name="mail_password" autofocus
                                                autocomplete="mail_password"
                                                value="{{ $mail->mail_password ?? ''}}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('mail_password')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_outgoing_host" >Mail Outgoing Host <span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_outgoing_host"
                                                placeholder="Enter Mail Outgoing Host" name="mail_outgoing_host" autofocus
                                                autocomplete="mail_outgoing_host"
                                                value="{{ $mail->mail_outgoing_host ?? '' }}" />
                                            <x-input-error class="mt-2" :messages="$errors->get('mail_outgoing_host')" />
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_incoming_host">Mail Incoming Host<span
                                                    class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_incoming_host"
                                                placeholder="Enter Mail Incoming Host" name="mail_incoming_host" autofocus
                                                autocomplete="mail_incoming_host" value="{{ $mail->mail_incoming_host ?? '' }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('mail_incoming_host')" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_outgoing_port">Mail Outgoing Port<span class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_outgoing_port"
                                                placeholder="Enter Mail Outgoing Port" name="mail_outgoing_port" autofocus
                                                autocomplete="mail_outgoing_port" value="{{ $mail->mail_outgoing_port ?? '' }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('mail_outgoing_port')" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mail_incoming_port">Mail Incoming Port<span
                                            class="text-danger">*</span></label>
                                            <x-text-input type="text" class="form-control" id="mail_incoming_port"
                                                placeholder="Enter Mail Incoming Port" name="mail_incoming_port" autofocus
                                                autocomplete="mail_incoming_port" value="{{ $mail->mail_incoming_port ?? '' }}"  />
                                            <x-input-error class="mt-2" :messages="$errors->get('mail_incoming_port')" />
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center py-20">
                                        <button type="submit" class="add_btn px-10">Save</button>
                                    </div>
                                </div>
                        </form>
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

  
@endsection

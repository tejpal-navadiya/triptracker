<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Trip Tracker') }}</title>

        @include('masteradmin.layouts.headerlink')

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            
            @include('masteradmin.layouts.navigation')
            @include('masteradmin.layouts.sidebar')
            <!-- <p>{{ session('showModal') }}</p> -->

            @yield('content')

            @if (session()->has('showModal'))
<!-- Bootstrap Modal -->
<div class="modal fade show" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
aria-hidden="true" style="display: block;">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Notice</h5>
        </div>
        <div class="modal-body">
            <p>{{ session('showModal') }}</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-primary">Purchase Plan</a>
        </div>
    </div>
</div>
</div>

@endif
            
        </div>
        @include('masteradmin.layouts.footerlink')
        @yield('scripts')
    </body>
</html>

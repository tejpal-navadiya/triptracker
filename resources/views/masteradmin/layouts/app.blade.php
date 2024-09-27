<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Profityo') }}</title>

        @include('masteradmin.layouts.headerlink')

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
        
            @include('masteradmin.layouts.navigation')
            @include('masteradmin.layouts.sidebar')
            @yield('content')
        </div>
        @include('masteradmin.layouts.footerlink')
    </body>
</html>

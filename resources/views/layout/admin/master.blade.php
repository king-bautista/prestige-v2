<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRESTIGE INTERACTIVE</title>
        <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL::to('dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('css/custom.css') }}">
    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper" id="app">
            <!-- Navbar -->
            @include('layout.admin.header-nav')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('layout.admin.left-nav')
        
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            @include('layout.admin.footer')
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <!-- <script src="{{ URL::to('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
        <!-- Toastr -->
        <script src="{{ URL::to('plugins/toastr/toastr.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::to('dist/js/adminlte.min.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @stack('scripts') <!-- To include script links -->
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRESTIGE INTERACTIVE</title>
        <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('js/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('css/custom-kiosk.css') }}">
        <link rel="stylesheet" href="{{ URL::to('css/softkeys-0.0.1.css') }}">
        <link rel="stylesheet" href="{{ URL::to('css/fancybox.css') }}">
        <link rel="stylesheet" href="{{ URL::to('css/jquery.zoom.css') }}">
    </head>

    <body>       
        <div id="app">
        
            @yield('content')

            <loader :is-visible="isLoading"></loader>            
        </div>

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ URL::to('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ URL::to('js/zoom.jquery.js') }}"></script>
        <script src="{{ URL::to('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::to('assets/js/popper-v2.min.js') }}"></script>	
        <script src="{{ URL::to('js/softkeys-0.0.1.js') }}"></script>
        <script src="{{ URL::to('js/way-finding.js') }}"></script>
        <script src="{{ URL::to('js/helper.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @stack('scripts') <!-- To include script links -->
    </body>
</html>

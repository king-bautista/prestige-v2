<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRESTIGE INTERACTIVE</title>
        <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Set Custom Theme UI -->
        <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/sm_default/css/bootstrap.min.css') }}"> 
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/softkeys/softkeys-0.0.1.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/select2-js/select2.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/sm_default/css/style.css') }}">
    </head>
    <body>
        
    @yield('content')

    <script src="{{ URL::to('themes/owlcarousel/vendors/jquery.min.js') }}"></script>
    <script src="{{ URL::to('themes/sm_default/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('themes/sm_default/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('themes/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ URL::to('themes/softkeys/softkeys-0.0.1.js') }}"></script>
    <script src="{{ URL::to('themes/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::to('themes/custom-js/helper.js') }}"></script>
    <script src="{{ URL::to('themes/select2-js/select2.min.js') }}"></script>

    @stack('scripts') <!-- To include script links -->
        
    </body>
</html>

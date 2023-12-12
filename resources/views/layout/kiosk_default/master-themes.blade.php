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
        <link rel="stylesheet" href="{{ URL::to('resources/css/style.css') }}">
        <link rel="stylesheet" href="{{ URL::to('resources/css/bootstrap.min.css') }}"> 
        <style>
            .text-mall-details {
                width: 100%;
                height: 100%;
                padding: 12px 20px;
                background-color: transparent;
                border: none;
                resize: none;
            }

            .home-category-holder {
                position: relative;
                float:left;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        
    @yield('content')

    <script src="{{ URL::to('resources/js/jquery.slim.min.js') }}"></script>
    <script src="{{ URL::to('resources/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('resources/js/bootstrap.min.js') }}"></script>
    @stack('scripts') <!-- To include script links -->
        
    </body>
</html>

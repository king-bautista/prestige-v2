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
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.theme.default.min.css') }}">
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

            .swiper-pagination1, .swiper-pagination-cinema {
                text-align: center;
            }

            .swiper-pagination-bullet {
                cursor: pointer;
                height: 17px;
                width: 17px;
                margin: 0 13.8px;
                background-color: #d5ddff;
                border-radius: 50%;
                display: inline-block;
                transition: background-color 0.6s ease;
            }

            .swiper-pagination-bullet-active, .swiper-pagination-bullet:hover {
                background-color: #2f3b87;
            }

            :root {
                --swiper-navigation-size: 60px !important;
            }

            :root {
                --swiper-theme-color: #484436;
            }

            .swiper-button-prev {
                left: 30px !important;
            }

            .swiper-button-next {
                right: 52px !important;
            }

            .swiper-button-disabled {
                opacity: 0 !important;
            }
            
        </style>
    </head>
    <body>
        
    @yield('content')

    <script src="{{ URL::to('themes/owlcarousel/vendors/jquery.min.js') }}"></script>
    <script src="{{ URL::to('resources/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('resources/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('themes/owlcarousel/owl.carousel.js') }}"></script>

    @stack('scripts') <!-- To include script links -->
        
    </body>
</html>

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
        <link rel="stylesheet" href="{{ URL::to('themes/sm_default/css/style.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/sm_default/css/bootstrap.min.css') }}"> 
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/owlcarousel/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/softkeys/softkeys-0.0.1.css') }}">
        <link rel="stylesheet" href="{{ URL::to('themes/jquery-ui/jquery-ui.min.css') }}">
        <style>
            .text-mall-details {
                width: 100%;
                height: 100%;
                padding: 12px 20px;
                background-color: transparent;
                border: none;
                resize: none;
            }
            
            #home-container {
                display: block;
            }

            #home-cat-contents {
                display: none;
            }

            .home-category-holder {
                position: relative;
                float:left;
                cursor: pointer;
            }

            .no-promos-found {
                display: flex;
            }

            .now-showing-details {
                display: block;
            }

            .now-showing-trailer {
                display: none;
            }

            .owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
                background: #2f3b87;
            }

            .owl-theme .owl-dots .owl-dot span {
                width: 18px;
                height: 18px;
                margin: 0 16.8px;
                background: #d5ddff;
            }

            .search-box {
                font-family: 'Henry Sans Medium';
                font-size: 24px;
                width: 65%;
                padding-left: 10px;
                padding-top: 8px;
                padding-bottom: 8px;
                border-color: #6051e3;
                border-width: 1px;
                margin: auto;
                border-radius: 10px;
                border-top-right-radius: 10px !important;
                border-bottom-right-radius: 10px !important;
                height: calc(1.5em + 0.75rem + 6px) !important;
            }

            .search-box-button {
                font-size: 24px;
                background-color: #0030ff;
                border-radius: 10px;
                margin-left: 5px;
                width: 111px;
                text-align: center;
                color: #fff;
            }

            .notification {
                font-family: 'Henry Sans Regular';
                font-weight: bold;
                font-size: 18px;
                float: left;
                margin: 0 510px 0 15px;
                border-width: 2px;
                color: #bc5b68;
                position: absolute;
                top: 53px;
                display: none;
            }

            .softkeys__btn {
                color: #6051e3;
                background-color: #fff;
                font-size: 1.6em !important;
                font-weight: bold;
                border: 1px solid #6051e3 !important;
                border-radius: 15px !important;
                width: 75px !important;
                height: 75px !important;
                line-height: 2.8em !important;
                padding: 0 !important;
                margin-bottom: 16px !important;
            }

            .softkeys__btn--space {
                width: 410px !important;
            }

            .softkeys__btn--search {
                width: 85px !important;
            }

            .softkeys__btn:hover, .softkeys__btn:focus {
                background-color: #98dad0 !important;
            }

            .ui-widget-content {
                border: 1px solid #c5c5c5;
                background: #ffffff;
                color: #333;
                height: 135px !important;
                overflow: hidden;
                overflow-y: scroll;
                z-index: 1;
                border-radius: 10px !important;
                margin-top: 2px !important;
            }

            .ui-widget-content {
                font-family: 'Henry Sans Regular' !important;
                font-size: 1.3rem !important;
            }

            .prestige-text-color {
                color: #6051E3;
            }

            .search-for {
                display:none;
            }

            .want-to-try span {
                font-size: 24px;
            }

            .pl-170 {
                padding-left: 170px;
            }

            .shop-logo {
                max-width: 240px;
                width: 173px !important;
                height: 73px !important;
                margin-right: 20px;
                border-radius: 10px !important;
                object-fit: cover;
            }

            .tenant-store {
                margin-left: auto;
                margin-right: auto;
                margin-top: 15px;
            } 

            .subscriber-holder {
                width: 1101px;
            }

            .letter-selected::first-letter {
                font-weight: bold;
                color: #532be2;
                text-decoration: underline;
            }

            .alphabet-box a.active {
                text-decoration: underline;
                color: #005aff !important;
            }

            #loadingDiv {
                position:absolute;;
                top:0;
                left:0;
                width:100%;
                height:100%;
                z-index: 99999;
                background-color:#000;
            }
            
        </style>
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

    @stack('scripts') <!-- To include script links -->
        
    </body>
</html>

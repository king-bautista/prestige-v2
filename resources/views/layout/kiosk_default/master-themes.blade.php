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

    <style>
        .select2-dropdown {
            border-top-right-radius: 15px !important;
            border-top-left-radius: 15px !important;
        }

        .select2-results {
            margin-top: 15px;
        }

        .select2-search--dropdown {
            position: absolute;
            width: 90%;
            bottom: -55px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: transparent !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            font-size: 24px;
        }

        ul#mapguide li.active:after {
            color: #6051e3;
        }

        .softkeys__btn--shift:hover {
            background-color: #98dad0 !important;
        }

        .thankyou {
            display:none;
        }

        .feedback-search-modal-position {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .feedback-section {
            width: 662px;
            height: 300px;
            padding: 35px;
            margin: auto;
            background-color: #fff;
            border-radius: 5px;
        }

        .feedback-option {
            position: relative;
            height: 50px;
        }

        .label-2 {
            font-family: 'Henry Sans Regular';
            color: #2a2a2a;
            font-size: 30px;
            font-weight: 500;
        }

        input.nothelpfulcheck[type=radio] {
            border: 0px;
            width: 22px;
            height: 22px;
            margin: 5px 15px 0 0;
            position: absolute;
        }

        .inputspan {
            position: absolute;
            top: 5px;
            font-family: 'Henry Sans Regular';
            font-size: 1em;
            font-weight: bold;
            margin-left: 35px;
        }

        .btn-submit-nothelpful {
            padding: 8px 50px 8px 50px;
            font-family: 'Henry Sans Regular';
        }

        .softkeys--alt .softkeys__btn[data-type=shift] {
            background-color: #fff !important;
            border-color: #4b94ce;
            color: #6051e3;
        }

        .btn-violet-color {
            background-color: #6051e3;
            color: #fff !important;
        }

        .feed-close {
            position: absolute;
            top: -45px;
            right: 351px;
            font-size: 2em;
            color: #ffffff;
            text-shadow: none;
            opacity: 1;
            font-family: Henry Sans Regular !important;
        }

    </style>

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

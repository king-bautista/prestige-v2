@extends('layout.kiosk_default.master')
@section('content')
<div class="container-fluid theme-bubble" style="background-image: url('{{ $site_background }}')">
    <div class="row">
        <div class="col-md-3 custom-p-1">
            <div class="banner-ads">
                <rotating-banners></rotating-banners>               
            </div>
        </div>
        <div class="col-md-9 m-0 p-0">
            <div class="row mr-0 ml-0">
                <div class="col-md-12 main-content-holder m-0 p-0">
                    <router-view></router-view>
                </div>
            </div>            
            <!-- <div class="row">
                <div class="col-md-12 text-center pt-2 pr-136">
                    <router-link to="/">
                        <div class="h-button widget-button home-button active logs" data-link='Category'>
                            <div class="button-text-align">Home</div>
                        </div>
                    </router-link>
                    <router-link to="/search">
                        <div class="h-button widget-button search-button logs" data-link='Search'>
                            <div class="button-text-align">Search</div>
                        </div>
                    </router-link>
                    <router-link to="/wayfinding">
                        <div class="h-button widget-button map-button logs" data-link='Map'>
                            <div class="button-text-align">Map</div>
                        </div>
                    </router-link>
                    <router-link to="/promos">
                        <div class="h-button widget-button promos-button logs" data-link='Promos'>
                            <div class="button-text-align">Promos</div>
                        </div>
                    </router-link>
                    <router-link to="/cinemas">
                        <div class="h-button widget-button cinema-button logs" data-link='Cinema'>
                            <div class="button-text-align">Cinema</div>
                        </div>
                    </router-link>
                </div>
            </div> -->
            <!-- <div id="popover-content" class="hide d-none" style="z-index:1">
                Need help? Touch here.
            </div>
            <div data-toggle="popover" data-container="body" data-placement="left" data-trigger="manual" type="button" data-html="true"  class="assistance_tooltip">
                <img src="{{ URL::to('assets/images/English/Help.png') }}" id="helpbutton">
            </div> -->
        </div>
    </div>
</div>
<rotating-screensaver></rotating-screensaver>

<!-- /.content -->
@stop

@push('scripts')
<script>
    var screensaver_handle = null;
    var helper_home = new Helpers();
    // PRELOADER
    // $('body').append("<div id='loadingDiv'><video muted='muted' autoplay loop><source src='{{ URL::to('assets/images/loading_page.mp4') }}' type='video/mp4'>Your browser does not support the video tag.</video></div>");

    // PRELOADER TIME INTERVAL
    $(window).on('load', function(){

    });

    setTimeout(removeLoader, 20000);
    setInterval(screenUpTime, (60*60*100));

    // setInterval(function(){ 
    //     $.get( "/api/v1/get-update", function( data ) {
    //         if(data.data.length > 0) {
    //             location.reload();
    //         }
    //     });
    // },(60000*5)) 

    // REMOVED PRELOADER
    function removeLoader(){
        $( "#loadingDiv" ).fadeOut(500, function() {
            // fadeOut complete. Remove the loading div
            $( "#loadingDiv" ).remove(); //makes page more lightweight 
        });  
    }

    function screenUpTime(){
        $.post( "/api/v1/screen-uptime", { site_screen_id: "{{$site_screen_id}}" } , function( data ) {
            console.log(data);
        }); 
    }

    $(document).ready(function(){
        // $('[data-toggle="popover"]').popover({
        //     html: true,
        //     content: function() {
        //         return $('#popover-content').html();
        //     }
        // }).click();

        // $('.assistance_tooltip').on('click',function(){
        //     $("#popover-content").html('To search, input your location and your desired destination. Then, click Get Directions');
        // });

        $('.h-button').on('click', function(){
            $('.h-button').removeClass('active');
            $(this).addClass('active');
            $(".theme-bubble").removeClass("theme-bubble-none");
        });

        $('.widget-button').on('click', function(){
            var page = $(this).data('link');
            helper_home.saveLogs({action: 'click'}, page);
        });

        $('.search-button').on('click', function() {
            $('#pills-profile-tab').tab('show');
        });        

    });

    // $('.home-button').click();
    
</script>
@endpush
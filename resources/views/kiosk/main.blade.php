@extends('layout.kiosk_default.master')
@section('content')

@if($site_orientation == 'Portrait')
<div class="container-fluid theme-portrait" style="background-image: url('{{ $site_background_portrait }}')">
    <div class="row">
        <div class="col-md-12 m-0 p-0">
            <div class="banner-ads-portrait">
                <rotating-banners></rotating-banners>               
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 m-0 p-0">
            <div class="row mr-0 ml-0">
                <div class="col-md-12 main-content-holder m-0 p-0">
                    <router-view></router-view>
                </div>
            </div>            
        </div>
    </div>
</div>
@else
<div class="container-fluid theme-landscape" style="background-image: url('{{ $site_background }}')">
    <div class="row">
        <div class="col-md-3 m-0 p-0">
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
        </div>
    </div>
</div>
@endif
<rotating-screensaver></rotating-screensaver>

<!-- /.content -->
@stop

@push('scripts')
<script>
    var screensaver_handle = null;
    var helper_home = new Helpers();

    setTimeout(removeLoader, 20000);
    setInterval(screenUpTime, (2000 * 60 * 2));

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
    
</script>
@endpush
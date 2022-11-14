@extends('layout.kiosk_default.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-1">
            <div class="banner-ads">
                <rotating-banners></rotating-banners>               
            </div>
        </div>
        <div class="col-md-9 m-0 p-0">
            <div class="row mr-0 ml-0">
                <div class="col-md-12 main-content-holder">
                    <router-view></router-view>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12 text-center">
                    <router-link to="/">
                        <div class="h-button widget-button home-button active">
                            <div class="button-text-align">Home</div>
                        </div>
                    </router-link>
                    <router-link to="/Search">
                        <div class="h-button widget-button search-button">
                            <div class="button-text-align">Search</div>
                        </div>
                    </router-link>
                    
                    <div class="h-button widget-button map-button">
                        <div class="button-text-align">Map</div>
                    </div>
                    <div class="h-button widget-button promos-button">
                        <div class="button-text-align">Promos</div>
                    </div>
                    <div class="h-button widget-button cinema-button">
                        <div class="button-text-align">Cinema</div>
                    </div>
                </div>
            </div>
            <div id="popover-content" class="hide d-none" style="z-index:999">
                Need help? Touch here.
            </div>
            <div data-toggle="popover" data-container="body" data-placement="left" data-trigger="manual" type="button" data-html="true"  class="assistance_tooltip" style="z-index:9999;position:absolute;top:870px;right:0px;">
                <img src="{{ URL::to('assets/images/English/Help.png') }}" id="helpbutton">
            </div>
        </div>
    </div>
</div>
<rotating-screensaver></rotating-screensaver>

<!-- /.content -->
@stop

@push('scripts')
<script>
    var screensaver_handle = null;
    var current_index = 0;

    $(document).on('click',function(){
        $("#screensaverwidget").hide();
        if(screensaver_handle) {
			clearTimeout(screensaver_handle);	
			screensaver_handle = null;
		}

        screensaver_handle = setTimeout(() => {
            $("#screensaverwidget").show();
		}, 1000 * 60 * 2);
		// }, 10000000 * 60 * 2);//DEVMODE
    });

    $(document).ready(function(){
        $('[data-toggle="popover"]').popover({
            html: true,
            content: function() {
                return $('#popover-content').html();
            }
        }).click();

        $('.assistance_tooltip').on('click',function(){
            $("#popover-content").html('To search, input your location and your desired destination. Then, click Get Directions');
        });

        $('.h-button').on('click', function(){
            $('.h-button').removeClass('active');
            $(this).addClass('active');
        });
    });

    $('.home-button').click();
    
</script>
@endpush
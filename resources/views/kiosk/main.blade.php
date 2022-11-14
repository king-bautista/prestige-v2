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

@push('scripts_var')
<script>
    var screensaver_handle = null;
    var count = 2;
    var countscreen = 2;
    var fullscreen_array = [];
    var banner_array = [];
    var current_index = 0;
</script>
@endpush

@push('scripts')
<script>
    // PRELOADER
    $('body').append("<div id='loadingDiv'><video muted='muted' autoplay loop><source src='{{ URL::to('assets/images/loading_page.mp4') }}' type='video/mp4'>Your browser does not support the video tag.</video></div>");

    // PRELOADER TIME INTERVAL
    $(window).on('load', function(){
        setTimeout(removeLoader, 6000); //wait for page load PLUS two seconds.
    });

    // REMOVED PRELOADER
    function removeLoader(){
        $( "#loadingDiv" ).fadeOut(500, function() {
            // fadeOut complete. Remove the loading div
            $( "#loadingDiv" ).remove(); //makes page more lightweight 
        });  
    }

    function checkFileExtension(filetype) {
        switch(filetype) {
            case 'ogg':
            case 'ogv':
            case 'mp4':
            case 'wmv':
            case 'avi':
            case 'mkv':
            case 'video/ogg':
            case 'video/ogv':
            case 'video/mp4':
            case 'video/wmv':
            case 'video/avi':
            case 'video/mkv':
                return 'video';
                break;
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/png':
            case 'image/gif':
                return 'image';
                break;
        }
    }

    // FULLSCREEN SLIDER
    function appendFullscreen(index = null) {
        var class_name = 'carousel-item';
        if(index != null) {
            countscreen = index;
            $('#carousel-fullscreen .carousel-item').removeClass('active');
            class_name = 'carousel-item active';
        }

        if((fullscreen_array.length-1) >= countscreen) {

            var type = 'image';
            type = checkFileExtension(fullscreen_array[countscreen].file_type);

            var carousel_item = '';
            carousel_item += '<div data-interval="'+fullscreen_array[countscreen].display_duration*1000+'" data-index="'+countscreen+'" data-id="'+fullscreen_array[countscreen].id+'" class="'+class_name+'">';
                if(type == 'video') {
                    carousel_item += '<span>';
                    carousel_item += '<video muted="muted" autoplay="true" style="margin: 0px; height: 100%; width: 100%;">';
                    carousel_item += '<source src="'+fullscreen_array[countscreen].material_image_path+'" type="video/ogg">';
                    carousel_item += 'Your browser does not support the video tag.';
                    carousel_item += '</video>';
                    carousel_item += '</span>';
                }
                else {
                    carousel_item += '<span>';
                    carousel_item += '<img src="'+fullscreen_array[countscreen].material_image_path+'" style="margin: 0px; height: 100%; width: 100%;">';
                    carousel_item += '</span>';
                }
            carousel_item += '</div>';
            $("#carousel-fullscreen").append(carousel_item);
            countscreen++;
        }
    }

    // BANNER SLIDER
    function appendBanners(index = null) {
        var class_name = 'carousel-item';
        if(index != null) {
            count = index;
            $('#carousel-banner .carousel-item').removeClass('active');
            class_name = 'carousel-item active';
        }

        if((banner_array.length) >= count) {

            var type = 'image';
            type = checkFileExtension(banner_array[count].file_type);

            var carousel_item = '';
            carousel_item += '<div data-interval="'+banner_array[count].display_duration*1000+'" data-index="'+count+'" data-id="'+banner_array[count].id+'" class="'+class_name+'">';
                if(type == 'video') {
                    carousel_item += '<span>';
                    carousel_item += '<video muted="muted" autoplay="true" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">';
                    carousel_item += '<source src="'+banner_array[count].material_image_path+'" type="video/ogg">';
                    carousel_item += 'Your browser does not support the video tag.';
                    carousel_item += '</video>';
                    carousel_item += '</span>';
                }
                else {
                    carousel_item += '<span>';
                    carousel_item += '<img src="'+banner_array[count].material_image_path+'" style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;">';
                    carousel_item += '</span>';
                }
            carousel_item += '</div>';
            $("#carousel-banner").append(carousel_item);
            count++;
        }
    }

    $(document).on('click',function(){
        $("#screensaverwidget").hide();
        if(screensaver_handle) {
			clearTimeout(screensaver_handle);	
			screensaver_handle = null;
		}

        screensaver_handle = setTimeout(() => {
            $("#screensaverwidget").show("slow", function() {
                appendFullscreen(current_index+1);
                appendFullscreen();
                appendBanners(current_index);
                appendBanners();
            });
		}, 1000 * 60 * 2);
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

        $('#fullscreen-ads-carousel').on('slide.bs.carousel', function () {
            //current_index = $(this).find('.active').data('index');
            $('#carousel-fullscreen .carousel-item:first').remove();
            appendFullscreen();
            if(fullscreen_array.length == countscreen) {
                countscreen = 0;                        
            }
        });

        $('#banner-ads-carousel').on('slide.bs.carousel', function () {
            current_index = $(this).find('.active').data('index');
            $('#carousel-banner .carousel-item:first').remove();
            appendBanners();
            if(banner_array.length == count) {
                count = 0;                        
            }
        });
    });

    $('.home-button').click();
    
</script>
@endpush
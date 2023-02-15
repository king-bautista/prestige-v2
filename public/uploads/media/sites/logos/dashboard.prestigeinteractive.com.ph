<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRESTIGE INTERACTIVE</title>
        <link rel="shortcut icon" href="https://dashboard.prestigeinteractive.com.ph/images/prestige-favicon.png">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/assets/css/bootstrap.min.css">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="qFk3WoW6x3dwOyi7wuQEH1mWZfODBoop8IBZoqY4">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/js/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/css/custom-kiosk.css">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/css/softkeys-0.0.1.css">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/css/fancybox.css">
        <link rel="stylesheet" href="https://dashboard.prestigeinteractive.com.ph/css/jquery.zoom.css">
    </head>

    <body>
        <script>
    var screensaver_handle = null;
    var count = 2;
    var countscreen = 2;
    var fullscreen_array = [];
    var banner_array = [];
    var current_index = 0;
</script>
 <!-- To include script links -->
        
        <div id="app">
        
            <div class="container-fluid">
    <div class="row">
        <div class="col-md-3 p-1">
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
            <div class="row">
                <div class="col-md-12 text-center">
                    <router-link to="/">
                        <div class="h-button widget-button home-button active">
                            <div class="button-text-align">Home</div>
                        </div>
                    </router-link>
                    <router-link to="/search">
                        <div class="h-button widget-button search-button">
                            <div class="button-text-align">Search</div>
                        </div>
                    </router-link>
                    <router-link to="/wayfinding">
                        <div class="h-button widget-button map-button">
                            <div class="button-text-align">Map</div>
                        </div>
                    </router-link>
                    <router-link to="/promos">
                        <div class="h-button widget-button promos-button">
                            <div class="button-text-align">Promos</div>
                        </div>
                    </router-link>
                    <router-link to="/cinemas">
                        <div class="h-button widget-button cinema-button">
                            <div class="button-text-align">Cinema</div>
                        </div>
                    </router-link>
                </div>
            </div>
            <div id="popover-content" class="hide d-none" style="z-index:1">
                Need help? Touch here.
            </div>
            <div data-toggle="popover" data-container="body" data-placement="left" data-trigger="manual" type="button" data-html="true"  class="assistance_tooltip">
                <img src="https://dashboard.prestigeinteractive.com.ph/assets/images/English/Help.png" id="helpbutton">
            </div>
        </div>
    </div>
</div>
<rotating-screensaver></rotating-screensaver>

<!-- /.content -->

            <loader :is-visible="isLoading"></loader>            
        </div>

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="https://dashboard.prestigeinteractive.com.ph/plugins/jquery/jquery.min.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/js/jquery.mousewheel.min.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/js/zoom.jquery.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/assets/js/bootstrap.min.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/assets/js/popper-v2.min.js"></script>	
        <script src="https://dashboard.prestigeinteractive.com.ph/js/softkeys-0.0.1.js"></script>
        <script src="https://dashboard.prestigeinteractive.com.ph/js/way-finding.js"></script>
        <script src="/js/app.js"></script>
        <script>
    // PRELOADER
    $('body').append("<div id='loadingDiv'><video muted='muted' autoplay loop><source src='https://dashboard.prestigeinteractive.com.ph/assets/images/loading_page.mp4' type='video/mp4'>Your browser does not support the video tag.</video></div>");

    // PRELOADER TIME INTERVAL
    $(window).on('load', function(){
        setTimeout(removeLoader, 10000); //wait for page load PLUS two seconds.
    });

    setInterval(function(){ 
        $.get( "/api/v1/get-update", function( data ) {
            if(data.data.length > 0) {
                location.reload();
            }
        });
    },(60000*5)) 

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

        if((fullscreen_array.length) >= countscreen) {

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
        $("#screensaverwidget").height('0').width('0');
        if(screensaver_handle) {
			clearTimeout(screensaver_handle);	
			screensaver_handle = null;
		}

        screensaver_handle = setTimeout(() => {
            $("#screensaverwidget").height('100%').width('100%');
		}, 2000 * 60 * 2);
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

        $('#banner-ads-carousel').on('slide.bs.carousel', function () {
            current_index = $(this).find('.active').data('index');
            $('#carousel-banner .carousel-item:first').remove();
            appendBanners();
            // reset rotation
            if(banner_array.length == count) {
                count = 0;                        
            }
        });

        $('#fullscreen-ads-carousel').on('slide.bs.carousel', function () {
            current_index = $(this).find('.active').data('index');
            $('#carousel-fullscreen .carousel-item:first').remove();
            appendFullscreen();
            // reset rotation
            if(fullscreen_array.length == countscreen) {
                countscreen = 0;                        
            }
        });

        $('.search-button').on('click', function() {
            $('#pills-profile-tab').tab('show');
        });
    });

    $('.home-button').click();
    
</script>
 <!-- To include script links -->
    </body>
</html>

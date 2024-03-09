<div style="width:0;height:0;position:absolute; top: 0; z-index: 9999; overflow: hidden; background-color: #fff;" id="screensaverwidget">
    <div class="multirotator-fullscreen-container"></div>
    <img class="mySlides" src="{{ URL::to('themes/sm_default/images/touchheretostart.png') }}" style="display: none;">
    <img class="mySlides" src="{{ URL::to('themes/sm_default/images/interactivedirectory.png') }}" style="display: none;">
</div>

@push('scripts')
<script>
    var fullscreen_ads = "{{ $fullscreen_ads }}";
    var screensaver_handle = '';
    var myIndex = 0;

    $(document).ready(function(){
        $('#screensaverwidget').on('click', function() {
            $("#home_btn").click();
        });

        $(document).on('click',function(){
            $("#screensaverwidget").height('0').width('0');
            $('.mySlides').hide();
            if(screensaver_handle) {
                clearTimeout(screensaver_handle);	
                screensaver_handle = null;
            }

            screensaver_handle = setTimeout(() => {
                screensaver_handle = setTimeout(() => {
                    $("#screensaverwidget").height('100%').width('100%');
                    showFullscreenAds();
                }, 5000); // 5 sec delay before showing screensaver
                helper.revertToEnglish();
                helper.resetKeyBoard();
                helper.homeBtnClick();
            }, 1000 * 60 * 2); // 2 min idle time, return to screensaver mode
        });

        screensaver_handle = setTimeout(() => {
            screensaver_handle = setTimeout(() => {
                $("#screensaverwidget").height('100%').width('100%');
                showFullscreenAds();
            }, 5000); // 5 sec delay before showing screensaver
            helper.revertToEnglish();
            helper.resetKeyBoard();
            helper.homeBtnClick();
        }, 2000 * 60 * 2); // 2 min idle time, return to screensaver mode
    });

    function carouselPlaceHolder() {
        var i;
		var x = document.getElementsByClassName("mySlides");

		for (i = 0; i < x.length; i++) {
			x[i].style.display = "none";
		}

        myIndex++;

		if (myIndex > x.length) {
			myIndex = 1
		};

		x[myIndex-1].style.display = "block"; 

        setTimeout(carouselPlaceHolder, 10000 , x[myIndex-1].currentTime = 0); // Change image every 5 seconds
    }

    function showFullscreenAds() {
        var my_fullscreen_ads = JSON.parse(helper.decodeEntities(fullscreen_ads));

        $('.multirotator-fullscreen-container').html('');
        $('.multirotator-fullscreen-container').html('<div class="owl-carousel owl-wrapper-fullscreen-ads"></div>');

        $.each(my_fullscreen_ads, function(key,fullscreen_ad) {
            var banner_element = '';
            banner_element += '<div class="item" data-display_duration="'+fullscreen_ad.display_duration*1000+'">';
            if(fullscreen_ad.file_type == 'video') {
                banner_element += '<span>';
                banner_element += '<video autoplay="true" class="banner-add banner-add-'+key+'" muted playsinline loop>';
                banner_element += '<source src="'+fullscreen_ad.material_path+'" type="video/ogg">';
                banner_element += 'Your browser does not support the video tag.';
                banner_element += '</video>';
                banner_element += '</span>';
            }
            else {
                banner_element += '<span>';
                banner_element += '<img src="'+fullscreen_ad.material_path+'" class="banner-add banner-add-'+key+'">';
                banner_element += '</span>';
            }
            banner_element += '</div>';

            $(".owl-wrapper-fullscreen-ads").append(banner_element);
        });

        owl_fullscreen = $('.owl-wrapper-fullscreen-ads');
        owl_fullscreen.on('changed.owl.carousel', function(e) {
            var current = e.item.index;
            var display_duration = $(e.target).find(".owl-item").eq(current).find(".item").data('display_duration');
            if(display_duration != undefined) {
                owl_fullscreen.trigger('play.owl.autoplay',[display_duration]);
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: true,
            items: 1,
            autoplay:true,
            autoplayTimeout:10000,
            autoplayHoverPause:false,
            mouseDrag: false,
            touchDrag: false,
            animateOut: 'fadeOut',
        });
        carouselPlaceHolder();
    }
</script>
@endpush
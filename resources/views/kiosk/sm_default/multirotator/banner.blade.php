<!-- image and video for landscape directory -->
<div class="multirotator_landscape_container"></div>

<!-- image and video for portrait directory -->
<!-- <div class="multirotator_portrait_container"></div> -->

@push('scripts')
<script>
    var helper = new Helpers();
    var banner_ads = "{{ $banner_ads }}";

    setTimeout(helper.removeLoader, 20000);
    setInterval(screenUpTime, 2000*60);

    function screenUpTime() {
        helper.screenUpTime('{{ $site_config->site_screen_id }}');
    }

    function showBannerAds() {

        var my_banner_ads = JSON.parse(helper.decodeEntities(banner_ads));
        
        $('.multirotator_landscape_container').html('');
        $('.multirotator_landscape_container').html('<div class="owl-carousel owl-wrapper-banner-ads"></div>');
        $.each(my_banner_ads, function(key,banner_ad) {
            var banner_element = '';
            banner_element += '<div class="item" data-display_duration="'+banner_ad.display_duration*1000+'">';
            if(banner_ad.file_type == 'video') {
                banner_element += '<span>';
                banner_element += '<video autoplay="true" class="multirotator_landscape banner-add banner-add-'+key+'" muted playsinline loop>';
                banner_element += '<source src="'+banner_ad.material_path+'" type="video/ogg">';
                banner_element += 'Your browser does not support the video tag.';
                banner_element += '</video>';
                banner_element += '</span>';
            }
            else {
                banner_element += '<span>';
                banner_element += '<img src="'+banner_ad.material_path+'" class="multirotator_landscape banner-add banner-add-'+key+'">';
                banner_element += '</span>';
            }
            banner_element += '</div>';

            $(".owl-wrapper-banner-ads").append(banner_element);
            $('.banner-add-'+key).on('click', function() {
                if(banner_ad.tenant_details) {
                    helper.saveLogs(banner_ad, 'Banner Ad');
                    helper.homeBtnClick();
                    //helper.saveBannerCount(banner_ad.content_id);
                    showTenantDetails(banner_ad.tenant_details);
                }
            });
        });

        owl_banner = $('.owl-wrapper-banner-ads');
        owl_banner.on('changed.owl.carousel', function(e) {
            var current = e.item.index;
            var display_duration = $(e.target).find(".owl-item").eq(current).find(".item").data('display_duration');
            if(display_duration != undefined) {
                owl_banner.trigger('play.owl.autoplay',[display_duration]);
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
            animateOut: 'fadeOut'
        });

    }

    showBannerAds();
</script>
@endpush
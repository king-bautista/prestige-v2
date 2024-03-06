<!-- title -->
<div class="p-3 font-weight-bold nav-titles translateme" data-en="Store Page">Store Page</div>

<div class="row">
    <div class="promo-featured-container" id="isSubscriber">
        <!-- BANNER -->
        <div class="col-sm-12">
            <div class="store-banner-container">                
            </div>
        </div>
        <!-- PRODUCTS -->
        <div class="col-sm-12" class="store-page-client-content">
            <div class="row promo-row-container">
            </div>
        </div>
    </div>
    <div class="promo-featured-container my-auto" id="nonSubscriber">
        <div class="d-flex justify-content-center">
            <div class="card border-0 bg-transparent tenantCardImgContainer">
                <img class="tenantCardImgContent" src="#">
            </div>
        </div>
    </div>
    <!-- TENANT DETAILS -->
    <div class="bg-white p-3 shadow tenant-card-info-tab">
        <div class="name-div">
            <div class="my-auto p-1">
                <img class="tenant-store-page-logo" src="#">
                <div class="font-weight-bold tenant-store-page-name"></div>
                <div class="tenant-store-page-floor"></div>
                <div class="tenant-store-page-views"><span class="view-count">0</span> <span class="translateme" data-en="Views">Views</span></div>
            </div>
            <div class="tenant-store-schedule">
                <span>
                    <svg class="svg-inline--fa fa-exclamation-circle mall-hours-info" aria-hidden="true"
                        focusable="false" data-prefix="fa" data-icon="exclamation-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" >
                        <path fill="currentColor"
                            d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"
                        ></path>
                    </svg>
                    <b>
                        <span class="text-success mall-hours-title">
                            <b class="mall-hours-title is-open translateme" data-en="Open"></b>
                        </span>
                        <span class="mall-hours-divider">|</span> <span class="mall-hours-title tenant-hours"></span>
                    </b>
                </span>
            </div>

            <div class="row mt-3 mb-0 socialMediaContentHide socialMediaContentsContent">
                <div class="col-6">
                    <a disabled="" role="button" class="btn ShareBtn" data-toggle="collapse" data-target="#shareclicktoogle" aria-expanded="false" aria-controls="collapseExample" >
                        <svg class="svg-inline--fa fa-share-alt fa-w-14 ShareTextMargin" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="share-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                            <path
                                fill="currentColor"
                                d="M352 320c-22.608 0-43.387 7.819-59.79 20.895l-102.486-64.054a96.551 96.551 0 0 0 0-41.683l102.486-64.054C308.613 184.181 329.392 192 352 192c53.019 0 96-42.981 96-96S405.019 0 352 0s-96 42.981-96 96c0 7.158.79 14.13 2.276 20.841L155.79 180.895C139.387 167.819 118.608 160 96 160c-53.019 0-96 42.981-96 96s42.981 96 96 96c22.608 0 43.387-7.819 59.79-20.895l102.486 64.054A96.301 96.301 0 0 0 256 416c0 53.019 42.981 96 96 96s96-42.981 96-96-42.981-96-96-96z"
                            ></path>
                        </svg>
                        
                        <span class="ShareText translateme" data-en="Share" >Share</span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <span class="text-danger ml-2 btn-like" id="btn-like">
                        <i class="far fa-heart svg-inline--fa fa-heart fa-w-16 heart-icon-btn btn-heart" aria-hidden="true"></i>
                        <a class="display-likes-btn">
                            <span class="like_counts"></span> <span>likes</span>
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <!-- SOCIAL MEDIA -->
        <div class="text-left social-media-container is_subscriber">
            <div class="mt-2 mb-2 social-media-fb-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-fb.svg') }}"/>
                <span class="social-media-text social-media-fb"></span>
            </div>

            <div class="mt-2 mb-2 social-media-ig-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-ig.svg') }}"/>
                <span class="social-media-text social-media-ig"></span>
            </div>

            <div class="mt-2 mb-2 social-media-twitter-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-x.png') }}"/>
                <span class="social-media-text social-media-twitter"></span>
            </div>

            <div class="mt-2 mb-2 social-media-tiktok-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-tiktok.png') }}"/>
                <span class="social-media-text social-media-tiktok"></span>
            </div>

            <div class="mt-2 mb-2 social-media-youtube-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-youtube.png') }}"/>
                <span class="social-media-text social-media-youtube"></span>
            </div>

            <div class="mt-2 mb-2 social-media-viber-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-viber.png') }}"/>
                <span class="social-media-text social-media-viber"></span>
            </div>
        </div>

        <div class="call-to-action">
            <div class="row storeNavigationContent">
                <div class="col-12 mt-3">
                    <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions">Get Directions</button>
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-prestige-rounded btn-prestige-pwd w-100 btn-direction-shop-pwd translateme" data-en="Get Directions (PWD-friendly)">
                        Get Directions (PWD-friendly)
                    </button>
                </div>
                <div class="col-12 mt-3">
                    <button class="btn w-100 btn-prestige-order-now translateme" data-en="Order Now" disabled="">Order Now</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- The Modal for tenant promos -->
<div id="imgPromoModalTenant" class="modal promo-modal-content">
     <!-- Modal content -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 promo-modal-container">
            <div class="modal-body m-0 p-0">
                <span class="close text-white closeTenant">X</span>
                <img class="promo-img promo-modal-img" src ="resources/uploads/promos/popup700px700px.png">
            </div>
        </div>
    </div>
</div>

<!-- The Modal for tenant banners -->
<div id="imgPromoBannerModal" class="modal promo-modal-content">
    <!-- Modal content -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 promo-modal-container-2">
            <div class="modal-body m-0 p-0">
                <span class="close text-white closeBanner">X</span>
                <img class="promo-img promo-banner-modal-img" src ="resources\uploads\promos\Featured Promo.png">
            </div>
        </div>
    </div>
</div>

<div class="custom-modal p-l-490 modal" id="modal-schedule">
    <div class="custom-modal-position set-width-schedule">                    
        <div class="modal-content set-height-schedule">
            <div class="modal-body">
                <!-- <span class="close text-white btn-close-sched">&times;</span> -->
                <span class="close btn-close-sched close_btn">X</span>
                <div class="label-1 translateme operational_hours" data-en="Operating Hours">Operating Hours</div>
                <div class="modal-body-schedule-days">
                </div>
                <div class="modal-body-schedule-time">
                </div>   
            </div>                   
        </div>     
    </div>
</div>

@push('scripts')
<script>
    var site_schedule = '{{ $site_schedule }}';
    var operational_hours = '{{ $operational_hours }}';
    var tenant_id = '';
    var tenant_schedule = '';
    var days = {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"};

    $(document).ready(function () {
        var modalTenant = $("#imgPromoModalTenant");
        var modalBanner = $("#imgPromoBannerModal");
        var spanTenant = $(".closeTenant");
        var spanBanner = $(".closeBanner");

        spanTenant.on("click", function () {
            modalTenant.css("display", "none");
        });

        spanBanner.on("click", function () {
            modalBanner.css("display", "none");
        });

        $('.tenant-store-schedule').on('click', function() {            
            var schedules = '';
            schedules = (tenant_schedule != '') ? tenant_schedule : JSON.parse(helper.decodeEntities(site_schedule));
            let tempSchedule = [];
            const currentSchedule = schedules;
            if (currentSchedule) {
                Object.keys(days).forEach(day => {
                    currentSchedule.forEach(obj => {
                        Object.keys(obj).forEach(key => {
                            if (key == 'schedules') {
                                if (obj['schedules'].match(day)) {
                                    tempSchedule.push(obj['start_time'] + " am - " + timeConvert(obj['end_time']));
                                }                               
                            }
                        });
                    });
                });
            }

            var str_days = '';
            $('.modal-body-schedule-days').html('');
            $.each(days, function(index,day) {
                str_days = '<div class="operational_days translateme" data-en="'+day+'" >'+day+'</div>';
                $('.modal-body-schedule-days').append(str_days);
            });

            var str_schedules = '';
            $('.modal-body-schedule-time').html('');
            $.each(tempSchedule, function(index,schedule) {
                str_schedules = '<div class="m-5-0">'+schedule+'</div>';
                $('.modal-body-schedule-time').append(str_schedules);
            });

            $('#modal-schedule').show();
        });

        $('.btn-close-sched').on('click', function(){
            $('#modal-schedule').hide();
        });

        $('#btn-like').on('click', function() {
            helper.updateLikeCount(tenant_id);
        });

        $('.btn-direction-shop').on('click', function() {
            helper.mapBtnClick();
            $('#tenant-select').val(tenant_id);
            $('.direction-from').click();
        });

        $('.btn-direction-shop-pwd').on('click', function() {
            helper.mapBtnClick();
            $('#tenant-select').val(tenant_id);
            $("#btnpwdchange").click();
        });

    });

    function timeConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
            time = time.slice(1); // Remove full string match value
            time[5] = +time[0] < 12 ? ' am' : ' pm'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }

    function showProducts(products) {
        if(products.banners) {
            var banner = '<img type="button" class="promo-banner-card" src="'+products.banners[0].image_url_path+'" />';
            $(".store-banner-container").append(banner);
            $('.promo-banner-card').on('click', function() {
                $('.promo-banner-modal-img').attr("src", products.banners[0].image_url_path);
                $("#imgPromoBannerModal").css("display", "block");
            });
        }

        if(products.product_list.length > 0) {
            $.each(products.product_list, function(index,product) {
                var product_item = '';
                product_item += '<div class="col-xl-4 col-lg-6 col-md-4 col-sm-4 promo-col-container">';
                product_item += '<div class="card border-0 bg-transparent promo-card">';
                product_item += '<img type="button" class="promo-img product_img_'+product.id+' " src="'+product.image_url_path+'" />';
                product_item += '</div>';
                product_item += '</div>';

                $( ".promo-row-container").append(product_item);
                $('.product_img_'+product.id).on('click', function() {
                    $('.promo-modal-img').attr("src", product.image_url_path);
                    $("#imgPromoModalTenant").css("display", "block");
                });
            });
        }
    }

    function dynamicHeight(){
        var tenant_name_height = $(".name-div").height(), max_height = parseInt("288.5"), excess_height, new_height;

        if(tenant_name_height >= max_height){
            $(".is_subscriber").addClass('social-media-container');
            $(".is_subscriber").removeClass('social-media-container-auto');
            excess_height = tenant_name_height - max_height;
            new_height = 120 - excess_height;
            alert(new_height);
            
            $(".social-media-container").css("height", new_height + "px");

        }
        else{
            $(".is_subscriber").removeClass('social-media-container');
            $(".is_subscriber").addClass('social-media-container-auto');
        }
    }

    function showTenantDetails(tenant) {
        tenant_schedule = '';
        console.log(tenant);
        var site_info = JSON.parse(helper.decodeEntities(operational_hours));
        tenant_id = tenant.id;
        if(tenant.tenant_details) {
            tenant_schedule = (tenant.tenant_details.schedules[0].schedules != undefined && tenant.tenant_details.schedules[0].schedules != '') ? tenant.tenant_details.schedules : '';
        }

        // TENANT DETAILS
        $('.tenant-store-page-logo').attr("src", tenant.brand_logo);
        $('.tenant-store-page-name').html(helper.convertToProperCase(tenant.brand_name));
        $('.tenant-store-page-floor').html(tenant.location);

        // STORE OR SITE HOURS
        $('.mall-hours-title').removeClass('text-success').removeClass('text-error')
        var is_open = (tenant.operational_hours.is_open) ? 'text-success' : 'text-danger';
        $('.is-open').removeClass('text-success text-danger');
        if(tenant.operational_hours.start_time) {
            $('.tenant-hours').html(tenant.operational_hours.start_time +'-'+tenant.operational_hours.end_time);
            $('.is-open').addClass(is_open).html((tenant.operational_hours.is_open) ? 'Open' : 'Closed');
            $('.is-open').attr("data-en",(tenant.operational_hours.is_open) ? 'Open' : 'Closed')
        }
        else {
            $('.is-open').addClass(is_open).html((site_info.operational_hours.is_open) ? 'Open' : 'Closed');
            $('.tenant-hours').html(site_info.start_time +'-'+site_info.end_time);
            $('.is-open').attr("data-en",(tenant.operational_hours.is_open) ? 'Open' : 'Closed')
        }

        // PRODUCT AND LOGO
        if(tenant.is_subscriber) {
            $(".promo-row-container").html('');
            $(".store-banner-container").html('');
            
            if(tenant.products != null) {
                showProducts(tenant.products);
                $('#isSubscriber').show();
                $('#nonSubscriber').hide();
            }
            else {
                $('.tenantCardImgContent').attr('src', tenant.brand_logo);
                $('#isSubscriber').hide();
                $('#nonSubscriber').show();            
            }
        }
        else {
            $('.tenantCardImgContent').attr('src', tenant.brand_logo);
            $('#isSubscriber').hide();
            $('#nonSubscriber').show();            
        }
        
        // STORE SOCIAL MEDIA
        $('.social-media-fb-container').show();    
        $('.social-media-ig-container').show();    
        $('.social-media-twitter-container').show();    
        $('.social-media-tiktok-container').show();
        $('.social-media-youtube-container').show();
        $('.social-media-viber-container').show();
        if(!tenant.tenant_details) {
            $('.social-media-fb-container').hide();    
            $('.social-media-ig-container').hide();    
            $('.social-media-twitter-container').hide();
            $('.social-media-tiktok-container').hide();
            $('.social-media-youtube-container').hide();
            $('.social-media-viber-container').hide();
        }
        else {
            $('.social-media-fb').html(tenant.tenant_details.facebook);
            $('.social-media-ig').html(tenant.tenant_details.instagram);
            $('.social-media-twitter').html(tenant.tenant_details.twitter);
            $('.social-media-tiktok').html(tenant.tenant_details.tiktok);
            $('.social-media-youtube').html(tenant.tenant_details.youtube);
            $('.social-media-viber').html(tenant.tenant_details.viber);
            
            if(!tenant.tenant_details.facebook) 
                $('.social-media-fb-container').hide();    
            if(!tenant.tenant_details.facebook)
                $('.social-media-ig-container').hide();    
            if(!tenant.tenant_details.twitter)
                $('.social-media-twitter-container').hide();  
            if(!tenant.tenant_details.tiktok)
                $('.social-media-tiktok-container').hide();  
            if(!tenant.tenant_details.youtube)
                $('.social-media-youtube-container').hide();
            if(!tenant.tenant_details.viber)
                $('.social-media-viber-container').hide();  
        }

        $('#promos-container').hide();
        $('#home-container').hide();
        $('#home-cat-contents').hide();
        $('#search-container').hide();
        $('#tenant-store-content').show();

        var contentPosition = $("#bigcontainer > div").length;
        if(contentPosition == 1){
            $("#bigcontainer").addClass("my-auto");
            $(".promo-row-container").addClass("dflex justify-content-center")
        }       

        if($(".btn-heart").hasClass("fas")) {
            $(".btn-heart").removeClass('fas').addClass('far');
        }

        helper.updateViewCount(tenant.id, tenant.view_count);        
        helper.setTenantCountDetails(tenant.id);
        // SAVE LOGS
        helper.saveLogs(tenant, 'Categories');

        current_location = 'tenant';
        page_history.push(current_location);

        dynamicHeight();
    }
</script>
@endpush
<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Store Page</div>

<div class="row">
    <div class="promo-featured-container" id="bigcontainer">
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
    <!-- TENANT DETAILS -->
    <div class="bg-white p-3 shadow tenant-card-info-tab">
        <div class="my-auto p-1">
            <img class="tenant-store-page-logo" src="resources\uploads\tenants\logo\JOLLIBEE.jpg" align="left">
            <div class="font-weight-bold tenant-store-page-name"></div>
            <div class="tenant-store-page-floor"></div>
            <div class="tenant-store-page-views"><span class="view-count">0</span> Views</div>
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
                        <b class="mall-hours-title is-open"></b>
                    </span>
                    <span class="mall-hours-divider">|</span> <span class="mall-hours-title tenant-hours"></span>
                </b>
            </span>
        </div>
        <!-- SOCIAL MEDIA -->
        <div class="text-left social-media-container">
            <div class="mt-4 social-media-fb-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-fb.svg') }}"/>
                <span class="social-media-text social-media-fb"></span>
            </div>

            <div class="mt-4 social-media-ig-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-ig.svg') }}"/>
                <span class="social-media-text social-media-ig"></span>
            </div>

            <div class="mt-4 social-media-twitter-container">
                <img class="social-media-icons" src="{{ URL::to('themes/sm_default/images/social-media-twitter.svg') }}"/>
                <span class="social-media-text social-media-twitter"></span>
            </div>
        </div>

        <div class="row mt-3 mb-0">
            <div class="col-sm-6 mt-0">
                <button class="btn w-100 btn-directions">Get Directions</button>
            </div>
            <div class="col-sm-6">
                <span class="text-danger ml-2 btn-like">
                    <svg class="svg-inline--fa fa-heart fa-w-16 heart-icon-btn" aria-hidden="true" focusable="false"
                        data-prefix="fa" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" >
                        <path
                            fill="currentColor"
                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"
                        ></path>
                    </svg>
                    <a class="display-likes-btn">
                        <span class="like_counts"></span> <span>likes</span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <a disabled="" role="button" class="btn ShareBtn" data-toggle="collapse" data-target="#shareclicktoogle" aria-expanded="false" aria-controls="collapseExample" >
                    <svg class="svg-inline--fa fa-share-alt fa-w-14 ShareTextMargin" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="share-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" >
                        <path
                            fill="currentColor"
                            d="M352 320c-22.608 0-43.387 7.819-59.79 20.895l-102.486-64.054a96.551 96.551 0 0 0 0-41.683l102.486-64.054C308.613 184.181 329.392 192 352 192c53.019 0 96-42.981 96-96S405.019 0 352 0s-96 42.981-96 96c0 7.158.79 14.13 2.276 20.841L155.79 180.895C139.387 167.819 118.608 160 96 160c-53.019 0-96 42.981-96 96s42.981 96 96 96c22.608 0 43.387-7.819 59.79-20.895l102.486 64.054A96.301 96.301 0 0 0 256 416c0 53.019 42.981 96 96 96s96-42.981 96-96-42.981-96-96-96z"
                        ></path>
                    </svg>
                    
                    <span class="ShareText">Share</span>
                </a>
            </div>

            <div class="col-6 mt-0">
                <button class="btn w-100 BtnOrderNow" disabled="" >Order Now</button>
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
                <span class="close text-white closeTenant">&times;</span>
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
                <span class="close text-white closeBanner">&times;</span>
                <img class="promo-img promo-banner-modal-img" src ="resources\uploads\promos\Featured Promo.png">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var site_schedule = '{{ $site_schedule }}';

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
    });

    function showProducts(products) {
        $(".promo-row-container").html('');
        $(".store-banner-container").html('');
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

    function showTenantDetails(tenant) {
        console.log(tenant);
        var site_info = JSON.parse(decodeEntities(site_schedule));
        // TENANT DETAILS
        $('.tenant-store-page-logo').attr("src", tenant.brand_logo);
        $('.tenant-store-page-name').html(tenant.brand_name);
        $('.tenant-store-page-floor').html(tenant.location);
        $('.view-count').html(tenant.view_count);
        $('.like_counts').html(tenant.like_count);

        // STORE OR SITE HOURS
        $('.mall-hours-title').removeClass('text-success').removeClass('text-error')
        var is_open = (tenant.operational_hours.is_open) ? 'text-success' : 'text-error';
        $('.is-open').addClass(is_open).html((tenant.operational_hours.is_open) ? 'Open' : 'Close');
        if(tenant.operational_hours.start_time) {
            $('.tenant-hours').html(tenant.operational_hours.start_time +'-'+tenant.operational_hours.end_time);
        }
        else {
            $('.tenant-hours').html(site_info.start_time +'-'+site_info.end_time);
        }

        // PRODUCT AND LOGO
        if(tenant.is_subscriber) {
            showProducts(tenant.products);
        }
        else {
            
        }
        
        // STORE SOCIAL MEDIA
        $('.social-media-fb-container').show();    
        $('.social-media-ig-container').show();    
        $('.social-media-twitter-container').show();    
        if(!tenant.tenant_details.facebook) 
            $('.social-media-fb-container').hide();    
        if(!tenant.tenant_details.facebook)
            $('.social-media-ig-container').hide();    
        if(!tenant.tenant_details.twitter)
            $('.social-media-twitter-container').hide();    

        $('.social-media-fb').html(tenant.tenant_details.facebook);
        $('.social-media-ig').html(tenant.tenant_details.instagram);
        $('.social-media-twitter').html(tenant.tenant_details.twitter);

        $('#tenant-store-content').show();
        $('#promos-container').hide();

        var contentPosition = $("#bigcontainer > div").length;
        if(contentPosition == 1){
            $("#bigcontainer").addClass("my-auto");
            $(".promo-row-container").addClass("dflex justify-content-center")
        }
    }
</script>
@endpush
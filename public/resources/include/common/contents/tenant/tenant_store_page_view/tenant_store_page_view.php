<!-- back btn -->
<!--Start of the directory back button-->
<?php include('resources/include/common/navigation/back_button.php'); ?>
<!--Start of the directory back button-->

<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Store Page</div>

<div class="row">
    <div class="promo-featured-container my-auto" id="">
        <div class="d-flex justify-content-center">
            <div class="card border-0 bg-transparent tenantCardImgContainer">
                <img class="tenantCardImgContent" src="resources\uploads\tenants\logo\JOLLIBEE.jpg">
            </div>
        </div>
    </div>
    
    <div class="bg-white p-3 shadow tenant-card-info-tab">

        <div class="my-auto p-1">
            <img class="tenant-store-page-logo" src="resources\uploads\tenants\logo\JOLLIBEE.jpg" align="left">
            <div class="font-weight-bold tenant-store-page-name">Jollibee</div>
            <div class="tenant-store-page-floor">GF</div>
            <div class="tenant-store-page-views">0 Views</div>
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
                    <span class="text-success mall-hours-title"><b class="mall-hours-title">Open</b></span> <span class="mall-hours-divider">|</span> <span class="mall-hours-title">10:00am-10:00pm</span>
                </b>
            </span>
        </div>
        
        <!-- <div class="text-left" id="socialMediaContents">
            <div class="mt-4">
                <img class="social-media-icons" src="resources\uploads\tenants\socials\social-media-fb.svg"/>
                <span class="social-media-text">Jollibee</span>
            </div>

            <div class="mt-4">
                <img class="social-media-icons" src="resources\uploads\tenants\socials\social-media-ig.svg"/>
                <span class="social-media-text">Jollibee</span>
            </div>

            <div class="mt-4">
                <img class="social-media-icons" src="resources\uploads\tenants\socials\social-media-twitter.svg"/>
                <span class="social-media-text">Jollibee</span>
            </div>
        </div> -->

        <div class="row mt-3 mb-0 socialMediaContentHide socialMediaContentsContent">
            <div class="col-sm-6">

                <a disabled="" role="button" class="btn ShareBtn-2" data-toggle="collapse" data-target="#shareclicktoogle" aria-expanded="false" aria-controls="collapseExample">
                    <svg class="svg-inline--fa fa-share-alt fa-w-14" viewBox="0 0 448 352" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="share-alt" role="img" xmlns="http://www.w3.org/2000/svg" data-fa-i2svg="">
                        <path
                            fill="currentColor"
                            d="M352 320c-22.608 0-43.387 7.819-59.79 20.895l-102.486-64.054a96.551 96.551 0 0 0 0-41.683l102.486-64.054C308.613 184.181 329.392 192 352 192c53.019 0 96-42.981 96-96S405.019 0 352 0s-96 42.981-96 96c0 7.158.79 14.13 2.276 20.841L155.79 180.895C139.387 167.819 118.608 160 96 160c-53.019 0-96 42.981-96 96s42.981 96 96 96c22.608 0 43.387-7.819 59.79-20.895l102.486 64.054A96.301 96.301 0 0 0 256 416c0 53.019 42.981 96 96 96s96-42.981 96-96-42.981-96-96-96z"
                        ></path>
                    </svg>
                    <!-- <span class="fa fa-share-alt" style="margin: 0;" viewbox="0 0 448 352"></span> Font Awesome fontawesome.com -->
                    <span class="translateme share-span" style="vertical-align: 7px; font-size: 1em;">Share</span>
                </a>

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
                        0 <span>likes</span>
                    </a>
                </span>
            </div>
        </div>

        <div class="row mt-3 storeNavigationContent">
            <div class="col-12 mt-3">
                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop">Get Directions</button>
            </div>

            <div class="col-12 mt-3">
                <button class="btn btn-prestige-rounded btn-prestige-pwd w-100 btn-direction-shop-pwd">
                    Get Directions (PWD-friendly)
                </button>
            </div>
            <div class="col-12 mt-3">
                <button class="btn w-100 btn-prestige-order-now" disabled="">Order Now</button>
            </div>
        </div>


    </div>
</div>


@extends('layout.kiosk_default.master-themes')
@section('content')

<div class="bg-kiosk tenant-main-content" style="background-image: url('{{ $site->site_background_path}}');">
    <div class="row w-100 m-0">
        <div class="col-xl-3 col-lg-12 dca-1">
            <!--Start of the directory banner-->
            <?php include('resources/include/common/multirotator/banner_landscape.php'); ?>
            <!--End of the directory banner-->
        </div>

        <div class="col-xl-9 col-lg-12 directory-container-content">
            @include('kiosk.sm_tanza.header')

            <!--Start of the directory about page-->
            <div id="DirectoryAboutPage">
                @include('kiosk.sm_tanza.pages.about')
            </div>
            <!--End of the directory about page-->

            <!--Start of the directory home content categories-->
                @include('kiosk.sm_tanza.pages.categories')
            <!--End of the directory home content categories-->

            <!--Start of the directory search content categories-->
            <div id="search-container">
                <?php include('resources/include/common/contents/directory_search.php'); ?>
            </div>
            <!--End of the directory search content categories-->

            <!--Start of the directory map content categories-->
            <div id="map-container">
                <?php include('resources/include/common/contents/directory_map.php');?>
            </div>
            <!--End of the directory map content categories-->

            <!--Start of the directory promos content categories-->
            <div id="promos-container">
                @include('kiosk.sm_tanza.pages.promos')
            </div>

            <div id="tenant-store-content">
                @include('kiosk.sm_tanza.pages.tenant')
            </div>
            <!--End of the directory promo content categories-->

            <!--Start of the directory cinema content categories-->
            <div id="cinema-container">
                @include('kiosk.sm_tanza.pages.cinema')
            </div>
            <!--End of the directory cinema content categories-->

            <!--Start of the directory navigation content navigation-->
            @include('kiosk.sm_tanza.nav')
            <!--End of the directory navigation content navigation-->
        </div>
    </div>
</div>
<!-- /.content -->
@stop

@push('scripts')
<script>
    /* script for popover */
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });

    /* script for random content of popover */
    $('[data-toggle="popover"]').on("click", function () {
        const assistant_messages = [
            "Touch a featured store or your desired store to get directions. You may check the latest product and promo offers. Scroll to view more.",
            "Need help? Touch here.",
            "Looking for outfits? Try searching dress or shirt.",
        ];

        var contentIndex = Math.floor(Math.random() * assistant_messages.length);
        var newMessage = assistant_messages[contentIndex];

        // Set the 'data-content' attribute for the popover
        $(this).attr("data-content", newMessage);
    });

    /* for data interval carousel */
    $('.carousel').carousel({
        interval: false,
    });

    // for modal for promo
    var modal = document.getElementById("imgPromoModal");

    var btn = document.getElementById("imgPromoBtn");

    var span = document.getElementsByClassName("close")[0];

    // for modal for tenants
    var modalTenant = document.getElementById("imgPromoModalTenant");

    var spanTenant = document.getElementsByClassName("closeTenant")[0];

    spanTenant.onclick = function() {
    modalTenant.style.display = "none";
    }

    window.onclick = function(event) {
    if (event.target == modalTenant) {
        modalTenant.style.backdrop = "static";
    }
    };

    /* for multirotator */
    // Get the video element
    var video = document.getElementById("vido");
    var image = document.getElementById("imge");
    let inValue = 1000 * 20;

    var interval = setInterval(function() {
        // Toggle the visibility of the video by changing the 'display' style property
        if (video.style.display === "none") {
            video.style.display = "block";
            image.style.display = "none"; // Hide the image when the video is visible
        } else {
            video.style.display = "none";
            image.style.display = "block"; // Show the image when the video is hidden
        }
    }, inValue); // 5000 milliseconds = 5 seconds
</script>   

<script>
    // for navigation positioning if remove another nav
    var NavContentPositioning = $("#NavContentContainer > div").length;
        if(NavContentPositioning == 1){
            $("#NavContentContainer").removeClass("nav-content-container");
            $("#NavContentContainer").addClass("nav-content-container-4");
        }

        else if(NavContentPositioning == 2){
            $("#NavContentContainer").removeClass("nav-content-container");
            $("#NavContentContainer").removeClass("nav-content-container-1");
            $("#NavContentContainer").removeClass("nav-content-container-2");
            $("#NavContentContainer").removeClass("nav-content-container-4");
            $("#NavContentContainer").addClass("nav-content-container-3");
        }

        else if(NavContentPositioning == 3){
            $("#NavContentContainer").removeClass("nav-content-container");
            $("#NavContentContainer").removeClass("nav-content-container-1");
            $("#NavContentContainer").removeClass("nav-content-container-3");
            $("#NavContentContainer").removeClass("nav-content-container-4");
            $("#NavContentContainer").addClass("nav-content-container-2");
        }

        else if(NavContentPositioning == 4){
            $("#NavContentContainer").removeClass("nav-content-container");
            $("#NavContentContainer").removeClass("nav-content-container-2");
            $("#NavContentContainer").removeClass("nav-content-container-3");
            $("#NavContentContainer").removeClass("nav-content-container-4");
            $("#NavContentContainer").addClass("nav-content-container-1");
        }

        else {
            $("#NavContentContainer").removeClass("nav-content-container-1");
            $("#NavContentContainer").removeClass("nav-content-container-2");
            $("#NavContentContainer").removeClass("nav-content-container-3");
            $("#NavContentContainer").removeClass("nav-content-container-4");
            $("#NavContentContainer").addClass("nav-content-container");
        }

    /* for logo button to view about page */
    $("#ImgMallLogo").on('click', function(){
        $('#DirectoryAboutPage, #search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4').show();
        $('#ImgMallLogo, #home-container, #home-cat-contents, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage').hide();
        $('#home_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
    });

    // FOR PROMO TENANT STORE PAGE
    $("#tenants-tenant-store").on('click', function(){
        $('#tenant-store-content').show();
        $('#promos-container').hide();

        var contentPosition = $("#bigcontainer > div").length;
        if(contentPosition == 1){
            $("#bigcontainer").addClass("my-auto");
            $("#testId1").addClass("dflex justify-content-center")
        }
    });

    // FOR TENANT PAGE
    $("#CatBtnCasualDining").on('click', function(){
        $('#TenantPage').show();
        $('#CatTabCategories').hide();
    });

    /* for home button */
    $("#home_btn").on('click', function(){
        $('#home-container, #search_v4, #home_v4s, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-cat-contents, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#home_txt').addClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        sub_categories = '';
        main_category = '';
        supplementals = '';
        alphabetical = '';
        tenant_list = '';
        $('#Tab-Category-Tab').click();        
    });

    /* for search button */
    $("#search_btn").on('click', function(){
        $('#search-container, #search_v4s, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #map-container, #promos-container, #cinema-container, #search_v4, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#search_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
    });

    /* for map button */
    $("#map_btn").on('click', function(){
        $('#map-container, #map_v4s, #home_v4, #search_v4, #promos_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #promos-container, #cinema-container, #map_v4, #home_v4s, #search_v4s, #promos_v4s, #cinema_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#map_txt').addClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
    });

    /* for promos button */
    $("#promos_btn").on('click', function(){
        $('#promos-container, #promos_v4s, #home_v4, #search_v4, #map_v4, #cinema_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #map-container, #cinema-container, #promos_v4, #home_v4s, #search_v4s, #cinema_v4s, #map_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#promos_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        $('#cinema_txt').removeClass("nav-btn-active");
        showPromos();
    });

    /* for cinema button */
    $("#cinema_btn").on('click', function(){
        $('#cinema-container, #cinema_v4s, #home_v4, #search_v4, #promos_v4, #map_v4, #ImgMallLogo').show();
        $('#home-container, #home-cat-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-store-content, #TenantPage, #DirectoryAboutPage').hide();
        $('#cinema_txt').addClass("nav-btn-active");
        $('#home_txt').removeClass("nav-btn-active");
        $('#map_txt').removeClass("nav-btn-active");
        $('#promos_txt').removeClass("nav-btn-active");
        $('#search_txt').removeClass("nav-btn-active");
        showCinemas();
    });

    /* for hiding the div and showing the home div */
    /* for redirecting to home button using home nav button*/
    $("#home_btn").on('click', function(){
        $('#home-cat-contents,#search-container,#map-container,#promos-container,#cinema-container').hide();
        $('#home-container').show();
    });

    /* for redirecting to home button using back button*/
    $("#Back_btn").on('click', function(){
        $('#cinema-container, #home-container, #home-cat-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-store-content, #DirectoryAboutPage').hide();
        $('#home-container').show();
    });
</script>



<!-- for carousel -->
<!-- <script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides((slideIndex += n));
    }

    function currentSlide(n) {
        showSlides((slideIndex = n));
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlidesContent");
        let dots = document.getElementsByClassName("carousel-dot");
        if (n > slides.length) {
            slideIndex = 1;
        }
        if (n < 1) {
            slideIndex = slides.length;
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" carousel-active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " carousel-active";
    }
</script> -->
@endpush
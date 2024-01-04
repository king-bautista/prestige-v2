<!-- Popper and Bootstrap JS -->
<script src="resources/js/jquery.slim.min.js"></script>
<script src="resources/js/popper.min.js"></script>
<script src="resources/js/bootstrap.min.js"></script>

<script>
    /*-------- script for popover --------*/
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });

    /*-------- script for random content of popover --------*/
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

    /*-------- for data interval carousel --------*/
    $(".carousel").carousel({
        interval: false,
    });

    //-------- for modal for promo --------
    $(document).ready(function () {
        var modal = $("#imgPromoModal");
        var btn = $("#imgPromoBtn");
        var span = $(".close");

        btn.on("click", function () {
            modal.css("display", "block");
        });

        span.on("click", function () {
            modal.css("display", "none");
        });

        $(window).on("click", function (event) {
            if (event.target === modal[0]) {
                modal.css("backdrop", "static");
            }
        });
    });

    //-------- for modal for tenants --------
    $(document).ready(function () {
        var modalTenant = $("#imgPromoModalTenant");
        var btnTenant = $("#imgPromoBtnTenant");
        var spanTenant = $(".closeTenant");

        btnTenant.on("click", function () {
            modalTenant.css("display", "block");
        });

        spanTenant.on("click", function () {
            modalTenant.css("display", "none");
        });

        $(window).on("click", function (event) {
            if (event.target === modalTenant[0]) {
                modalTenant.css("backdrop", "static");
            }
        });
    });

    //-------- for modal for tenants banner promo --------
    $(document).ready(function () {
        var modalBanner = $("#imgPromoBannerModal");
        var btnBanner = $("#imgPromoBannerBtn");
        var spanBanner = $(".closeBanner");

        btnBanner.on("click", function () {
            modalBanner.css("display", "block");
        });

        spanBanner.on("click", function () {
            modalBanner.css("display", "none");
        });

        $(window).on("click", function (event) {
            if (event.target === modalBanner[0]) {
                modalBanner.css("backdrop", "static");
            }
        });
    });

    //-------- for modal for cinema sched --------
    $(document).ready(function () {
        var modalCinema = $("#CinemaDetailsModal");
        var btnCinema = $("#CinemaDetailsViewBtn");
        var spanCinema = $(".closeCinemaDetails");

        btnCinema.on("click", function () {
            modalCinema.css("display", "block");
        });

        spanCinema.on("click", function () {
            modalCinema.css("display", "none");
        });

        $(window).on("click", function (event) {
            if (event.target === modalCinema[0]) {
                modalCinema.css("backdrop", "static");
            }
        });
    });

    /*-------- for multirotator --------*/
    // Get the video element
    var video = document.getElementById("vido");
    var image = document.getElementById("imge");
    let inValue = 1000 * 20;

    var interval = setInterval(function () {
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
    //-------- for navigation positioning if remove another nav --------
    var NavContentPositioning = $("#NavContentContainer > div").length;
    if (NavContentPositioning == 1) {
        $("#NavContentContainer").removeClass("nav-content-container");
        $("#NavContentContainer").addClass("nav-content-container-4");
    } else if (NavContentPositioning == 2) {
        $("#NavContentContainer").removeClass("nav-content-container");
        $("#NavContentContainer").removeClass("nav-content-container-1");
        $("#NavContentContainer").removeClass("nav-content-container-2");
        $("#NavContentContainer").removeClass("nav-content-container-4");
        $("#NavContentContainer").addClass("nav-content-container-3");
    } else if (NavContentPositioning == 3) {
        $("#NavContentContainer").removeClass("nav-content-container");
        $("#NavContentContainer").removeClass("nav-content-container-1");
        $("#NavContentContainer").removeClass("nav-content-container-3");
        $("#NavContentContainer").removeClass("nav-content-container-4");
        $("#NavContentContainer").addClass("nav-content-container-2");
    } else if (NavContentPositioning == 4) {
        $("#NavContentContainer").removeClass("nav-content-container");
        $("#NavContentContainer").removeClass("nav-content-container-2");
        $("#NavContentContainer").removeClass("nav-content-container-3");
        $("#NavContentContainer").removeClass("nav-content-container-4");
        $("#NavContentContainer").addClass("nav-content-container-1");
    } else {
        $("#NavContentContainer").removeClass("nav-content-container-1");
        $("#NavContentContainer").removeClass("nav-content-container-2");
        $("#NavContentContainer").removeClass("nav-content-container-3");
        $("#NavContentContainer").removeClass("nav-content-container-4");
        $("#NavContentContainer").addClass("nav-content-container");
    }

/*-------- for navigation home to hide and show contents --------*/
    /*-------- for food card to categories content --------*/
    $("#food-card").on("click", function () {
        $("#home-food-contents, #CatTabCategories").show();
        $("#home-container").hide();
    });

    //-------- FOR Tab categories --------
        /*-------- for food button to its contents --------*/
        $("#Categories-nav-tab").on("click", function () {
            $("#TenantPage, #tenant-store-default").hide();
            $("#CatTabCategories").show();
        });

        //-------- FOR TENANT PAGE --------
        $("#CatBtnCasualDining").on("click", function () {
            slideFoodCategories = 1;
            $("#TenantPage").show();
            $("#CatTabCategories").hide();
            showFoodCategoriesSlides(slideFoodCategories);
        });

        //-------- FOR STORE TENANT PAGE DEFAULT --------
        $("#foodBtnDefault").on("click", function () {
            $("#tenant-store-default").show();
            $("#CatTabCategories").hide();
        });

        //-------- FOR STORE TENANT PAGE --------
        $("#TenantStoreContentCard").on("click", function () {
            slideFoodCategories = 1;
            $("#tenant-store-content").show();
            $("#home-food-contents").hide();
            showFoodCategoriesSlides(slideFoodCategories);

            var TenantStoreContentSocialMedia = $("#socialMediaContents > div").length;
            if (TenantStoreContentSocialMedia >= 3) {
                $(".socialMediaContentsContent").removeClass("socialMediaContentHide");
                $(".storeNavigationContent").removeClass("mt-3");
                $(".storeNavigationContent").addClass("storeNavigationBtn");
                $("#socialMediaContents").show();
            }
        });

    /*-------- for fashion card to categories content --------*/
    $("#fashion-card").on("click", function () {
        $("#home-fashion-contents, #FashionTabCategories").show();
        $("#home-container").hide();
    });
        /*-------- for food button to its contents --------*/
        $("#Fashion-nav-tab").on("click", function () {
            $("#TenantPage").hide();
            $("#FashionTabCategories").show();
        });

    /*-------- for electronics card to categories content --------*/
    $("#electronics-card").on("click", function () {
        $("#home-electronics-contents, #ElectronicsTabCategories").show();
        $("#home-container").hide();
    });

    /*-------- for services card to categories content --------*/
    $("#services-card").on("click", function () {
        $("#home-services-contents, #ServicesTabCategories").show();
        $("#home-container").hide();
    });

    /*-------- for finds card to categories content --------*/
    $("#essentials-card").on("click", function () {
        $("#home-essentials-contents, #EssentialsTabCategories").show();
        $("#home-container").hide();
    });

    //-------- FOR PROMO TENANT SUBSCRIBER PAGE --------
    $("#tenants-tenant-store").on("click", function () {
        $("#tenant-subscriber-content").show();
        $("#promos-container").hide();

        var contentPosition = $("#bigcontainer > div").length;
        if (contentPosition == 1) {
            $("#bigcontainer").addClass("my-auto");
            $("#PromoRowContainer").addClass("dflex justify-content-center");
        }

        else {
            $("#bigcontainer").removeClass("my-auto");
            $("#PromoRowContainer").removeClass("dflex justify-content-center");
        }
    });

//-------- for nav buttons functions show and hide contents --------

    /*-------- for logo button to view about page --------*/
    $("#ImgMallLogo").on("click", function () {
        $("#DirectoryAboutPage, #search_v4, #home_v4, #map_v4, #promos_v4, #cinema_v4").show();
        $("#ImgMallLogo, #home-container, #home-food-contents, #search-container, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-subscriber-content, #TenantPage, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#home_txt").removeClass("nav-btn-active");
        $("#search_txt").removeClass("nav-btn-active");
        $("#map_txt").removeClass("nav-btn-active");
        $("#promos_txt").removeClass("nav-btn-active");
        $("#cinema_txt").removeClass("nav-btn-active");
    });

    /*-------- for home button --------*/
    $("#home_btn").on("click", function () {
        $("#home-container, #search_v4, #home_v4s, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo").show();
        $("#home-food-contents, #map-container, #promos-container, #cinema-container, #search_v4s, #home_v4, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-subscriber-content, #TenantPage, #DirectoryAboutPage, #promos-default, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#home_txt").addClass("nav-btn-active");
        $("#search_txt").removeClass("nav-btn-active");
        $("#map_txt").removeClass("nav-btn-active");
        $("#promos_txt").removeClass("nav-btn-active");
        $("#cinema_txt").removeClass("nav-btn-active");

        $('#Tab-Category-Tab').trigger('click');
        $('#Tab-Fashion-Tab').trigger('click');
        $('#Tab-Electronics-Tab').trigger('click');
        $('#Tab-Service-Tab').trigger('click');
        $('#Tab-Essentials-Tab').trigger('click');
    });

    /*-------- for search button --------*/
    $("#search_btn").on("click", function () {
        $("#search-container, #search_v4s, #home_v4, #map_v4, #promos_v4, #cinema_v4, #ImgMallLogo").show();
        $("#home-container, #home-food-contents, #map-container, #promos-container, #cinema-container, #search_v4, #home_v4s, #map_v4s, #promos_v4s, #cinema_v4s, #tenant-subscriber-content, #TenantPage, #DirectoryAboutPage, #promos-default, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#search_txt").addClass("nav-btn-active");
        $("#home_txt").removeClass("nav-btn-active");
        $("#map_txt").removeClass("nav-btn-active");
        $("#promos_txt").removeClass("nav-btn-active");
        $("#cinema_txt").removeClass("nav-btn-active");

        var searchListCardsContent = $(".searchListCards > div").length;
            if (searchListCardsContent >= 1) {
                $("#searchList").show();
                $("#searchNone").hide();
            }

            else {
                $("#searchList").hide();
                $("#searchNone").show();
            }
    });

    /*-------- for map button --------*/
    $("#map_btn").on("click", function () {
        $("#map-container, #map_v4s, #home_v4, #search_v4, #promos_v4, #cinema_v4, #ImgMallLogo").show();
        $("#home-container, #home-food-contents, #search-container, #promos-container, #cinema-container, #map_v4, #home_v4s, #search_v4s, #promos_v4s, #cinema_v4s, #tenant-subscriber-content, #TenantPage, #DirectoryAboutPage, #promos-default, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#map_txt").addClass("nav-btn-active");
        $("#search_txt").removeClass("nav-btn-active");
        $("#home_txt").removeClass("nav-btn-active");
        $("#promos_txt").removeClass("nav-btn-active");
        $("#cinema_txt").removeClass("nav-btn-active");

        $('#btnpwdchange').on('click', function() {
            $(this).toggleClass('clicked').css({
                'background-color': $(this).hasClass('clicked') ? '#0030ff' : 'white',
                'color': $(this).hasClass('clicked') ? 'white' : '#0030ff',
                'border-color': $(this).hasClass('clicked') ? '0.5px solid #fff' : ''
            });
        });
    });

    /*-------- for promos button --------*/
    $("#promos_btn").on("click", function () {
        $("#promos-container, #promos_v4s, #home_v4, #search_v4, #map_v4, #cinema_v4, #ImgMallLogo").show();
        $("#home-container, #home-food-contents, #search-container, #map-container, #cinema-container, #promos_v4, #home_v4s, #search_v4s, #cinema_v4s, #map_v4s, #tenant-subscriber-content, #TenantPage, #DirectoryAboutPage, #promos-default, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#promos_txt").addClass("nav-btn-active");
        $("#home_txt").removeClass("nav-btn-active");
        $("#map_txt").removeClass("nav-btn-active");
        $("#search_txt").removeClass("nav-btn-active");
        $("#cinema_txt").removeClass("nav-btn-active");

        var promo_div = $(".SlideShowContainer > div").length;
        if (promo_div == 0) {
            $("#promos-default").show();
            $("#promos-container").hide();
        }
    });

    /*-------- for cinema button --------*/
    $("#cinema_btn").on("click", function () {
        $("#cinema-container, #cinema_v4s, #home_v4, #search_v4, #promos_v4, #map_v4, #ImgMallLogo").show();
        $("#home-container, #home-food-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-subscriber-content, #TenantPage, #DirectoryAboutPage, #promos-default, #tenant-store-content, #home-fashion-contents, #home-electronics-contents, #home-services-contents, #home-essentials-contents, #tenant-store-default").hide();
        $("#cinema_txt").addClass("nav-btn-active");
        $("#home_txt").removeClass("nav-btn-active");
        $("#map_txt").removeClass("nav-btn-active");
        $("#promos_txt").removeClass("nav-btn-active");
        $("#search_txt").removeClass("nav-btn-active");

        $('#Tab-Cinema-Tab').trigger('click');
        
        // for cinema sched if there is no movie available
        var cinemaContentDefault = $(".cinemaCardPosition > div").length;
        if (cinemaContentDefault == 0) {
            $("#CinemaTabDefault").show();
            $("#CinemaTabSchedule").hide();
        }

        // for adjusting the column if the content of cinema sched is more than 6 cards
        var cinemaSchedContentPosition = $("#SmCinemaSchedcontent > div").length;
        if (cinemaSchedContentPosition <= 4) {
            $("#SmCinemaSchedcontent").addClass("justify-content-center");
        } 
        else if (cinemaSchedContentPosition == 5) {
            $("#SmCinemaSchedcontent").addClass("cinema-details-sched-content-adjustment");
        } 
        else {
            $("#SmCinemaSchedcontent").removeClass("justify-content-center");
            $("#SmCinemaSchedcontent").removeClass("cinema-details-sched-content-adjustment");
            $(".SmCinemaSched").removeClass("mx-4");
            $(".SmCinemaSched").addClass("mr-5");
        }

        // for centering cinema cards
        var contentPosition = $("#bigcontainer > div").length;
        if (contentPosition == 2) {
            $("#bigcontainer").addClass("my-auto");
            $("#testId1").addClass("dflex justify-content-center");
        }

        // for cinema sched carousel
        let slideIndexCinema = 1;
        showSlidesCinema(slideIndexCinema);

        // function plusSlidesCinema(n) {
        //     showSlidesCinema((slideIndexCinema += n));
        // }

        // function currentSlideCinema(n) {
        //     showSlidesCinema((slideIndexCinema = n));
        // }

        $(".cinemaPrev").on("click", function(){
            const n = -1;
            showSlidesCinema((slideIndexCinema += n));
        });
        $(".cinemaNext").on("click", function(){
            const n = 1;
            showSlidesCinema((slideIndexCinema += n));
        });

        function showSlidesCinema(n) {
            let slidesCinema = $(".carousel-item-cinema");
            let dotsCinema = $(".carousel-dot-cinema");

            slideIndexCinema = n > slidesCinema.length ? 1 : n < 1 ? slidesCinema.length : slideIndexCinema;
            $(".right-btn-carousel-cinema").toggle(slideIndexCinema !== slidesCinema.length);
            $(".left-btn-carousel-cinema").toggle(slideIndexCinema !== 1);

            // To get the number of cards and adjust the postion of the movie containers content
            if (slideIndexCinema === slidesCinema.length) {
                var currentSlideDivCount = $(".cinemaCardPosition > div").length;
                var maxRowPerSlide = 3;
                var cinemaCardContainerContentSingle = 1;
                var cinemaCardContainerContentDouble = 2;
                var newCurrentSlideDivCount = currentSlideDivCount % maxRowPerSlide;

                // If there is only one card in the container
                if (newCurrentSlideDivCount == cinemaCardContainerContentSingle) {
                    $(".carouselContentAdjustment").addClass("carousel-content-container-cinema");
                    $(".carouselContentAdjustment").removeClass("carousel-content-container-cinema-adjustment");
                    $(".img-cinema-card").removeClass("img-cinema-card-adjustment");
                    $(".MovieTitle").addClass("MovieTitleSingle");
                    $(".ViewDetails").addClass("ViewDetailsSingle");
                }

                // If there is only two cards in the container
                else if (newCurrentSlideDivCount == cinemaCardContainerContentDouble) {
                    $(".carouselContentAdjustment").removeClass("carousel-content-container-cinema");
                    $(".carouselContentAdjustment").addClass("carousel-content-container-cinema-adjustment");
                    $(".img-cinema-card").addClass("img-cinema-card-adjustment");
                    $(".MovieTitle").addClass("MovieTitleDouble");
                    $(".ViewDetails").addClass("ViewDetailsDouble");
                } else {
                    $(".carouselContentAdjustment").addClass("carousel-content-container-cinema");
                    $(".carouselContentAdjustment").removeClass("carousel-content-container-cinema-adjustment");
                    $(".img-cinema-card").removeClass("img-cinema-card-adjustment");
                    $(".MovieTitle").removeClass("MovieTitleSingle");
                    $(".ViewDetails").removeClass("ViewDetailsSingle");
                    $(".MovieTitle").removeClass("MovieTitleDouble");
                    $(".ViewDetails").removeClass("ViewDetailsDouble");
                }
            } else {
                $(".carouselContentAdjustment").addClass("carousel-content-container-cinema");
                $(".carouselContentAdjustment").removeClass("carousel-content-container-cinema-adjustment");
                $(".img-cinema-card").removeClass("img-cinema-card-adjustment");
                $(".MovieTitle").removeClass("MovieTitleSingle");
                $(".ViewDetails").removeClass("ViewDetailsSingle");
                $(".MovieTitle").removeClass("MovieTitleDouble");
                $(".ViewDetails").removeClass("ViewDetailsDouble");
            }

            slidesCinema.hide().eq(slideIndexCinema - 1).show();
            dotsCinema.removeClass("carousel-active").eq(slideIndexCinema - 1).addClass("carousel-active");
        }
    });

    /* for hiding the div and showing the home div */
    /* for redirecting to home button using home nav button*/
    $("#home_btn").on("click", function () {
        $("#home-food-contents,#search-container,#map-container,#promos-container,#cinema-container").hide();
        $("#home-container").show();
    });

    /* for redirecting to home button using back button*/
    $("#Back_btn").on("click", function () {
        $("#cinema-container, #home-container, #home-food-contents, #search-container, #map-container, #promos-container, #cinema_v4, #home_v4s, #search_v4s, #promos_v4s, #map_v4s, #tenant-subscriber-content, #DirectoryAboutPage, #promos-default").hide();
        $("#home-container").show();
    });
</script>

<!---------------- for separate home card carousels contents ---------------->
    <!---------------- for food carousels contents ---------------->
    <script>
        //---------------- for food categories carousel ----------------
        let slideFoodCategories = 1;
        showFoodCategoriesSlides(slideFoodCategories);

        // function plusSlidesCinema(n) {
        //     showFoodCategoriesSlides((slideFoodCategories += n));
        // }

        function currentSlideFoodCategories(n) {
            showFoodCategoriesSlides((slideFoodCategories = n));
        }

        $(".FoodCategoriesPrev").on("click", function(){
            const n = -1;
            showFoodCategoriesSlides((slideFoodCategories += n));
        });
        $(".FoodCategoriesNext").on("click", function(){
            const n = 1;
            showFoodCategoriesSlides((slideFoodCategories += n));
        });

        function showFoodCategoriesSlides(n) {
            let slidesFoodCategories = $(".carousel-item-per-food-category");
            let dotsFoodCategories = $(".carousel-dot-per-food-category");

            slideFoodCategories = n > slidesFoodCategories.length ? 1 : n < 1 ? slidesFoodCategories.length : slideFoodCategories;
            $(".right-btn-carousel-per-food-category").toggle(slideFoodCategories !== slidesFoodCategories.length);
            $(".left-btn-carousel-per-food-category").toggle(slideFoodCategories !== 1);

            slidesFoodCategories.hide().eq(slideFoodCategories - 1).show();
            dotsFoodCategories.removeClass("carousel-active").eq(slideFoodCategories - 1).addClass("carousel-active");
        }

        //---------------- for food alphabetical carousel ----------------
        let slideFoodAlphabetical = 1;
        showFoodAlphabeticalSlides(slideFoodAlphabetical);

        // function plusSlidesCinema(n) {
        //     showFoodAlphabeticalSlides((slideFoodAlphabetical += n));
        // }

        function currentSlidePerFoodAlphabetical(n) {
            showFoodAlphabeticalSlides((slideFoodAlphabetical = n));
        }

        $(".foodAlphabeticalPrev").on("click", function(){
            const n = -1;
            showFoodAlphabeticalSlides((slideFoodAlphabetical += n));
        });
        $(".foodAlphabeticalNext").on("click", function(){
            const n = 1;
            showFoodAlphabeticalSlides((slideFoodAlphabetical += n));
        });

        function showFoodAlphabeticalSlides(n) {
            let slidesFoodAlphabetical = $(".carousel-item-per-food-alphabetical");
            let dotsFoodAlphabetical = $(".carousel-dot-per-food-alphabetical");

            slideFoodAlphabetical = n > slidesFoodAlphabetical.length ? 1 : n < 1 ? slidesFoodAlphabetical.length : slideFoodAlphabetical;
            $(".right-btn-carousel-per-food-alphabetical").toggle(slideFoodAlphabetical !== slidesFoodAlphabetical.length);
            $(".left-btn-carousel-per-food-alphabetical").toggle(slideFoodAlphabetical !== 1);

            slidesFoodAlphabetical.hide().eq(slideFoodAlphabetical - 1).show();
            dotsFoodAlphabetical.removeClass("carousel-active").eq(slideFoodAlphabetical - 1).addClass("carousel-active");
        }

        //---------------- for food cravings carousel ----------------
        let slideFoodCravings = 1;
        showFoodCravingsSlides(slideFoodCravings);

        // function plusSlidesCinema(n) {
        //     showFoodCravingsSlides((slideFoodCravings += n));
        // }

        function currentSlidePerFoodCravings(n) {
            showFoodCravingsSlides((slideFoodCravings = n));
        }

        $(".foodCravingsPrev").on("click", function(){
            const n = -1;
            showFoodCravingsSlides((slideFoodCravings += n));
        });
        $(".foodCravingsNext").on("click", function(){
            const n = 1;
            showFoodCravingsSlides((slideFoodCravings += n));
        });

        function showFoodCravingsSlides(n) {
            let slidesFoodCravings = $(".carousel-item-per-food-cravings");
            let dotsFoodCravings = $(".carousel-dot-per-food-cravings");

            slideFoodCravings = n > slidesFoodCravings.length ? 1 : n < 1 ? slidesFoodCravings.length : slideFoodCravings;
            $(".right-btn-carousel-per-food-cravings").toggle(slideFoodCravings !== slidesFoodCravings.length);
            $(".left-btn-carousel-per-food-cravings").toggle(slideFoodCravings !== 1);

            slidesFoodCravings.hide().eq(slideFoodCravings - 1).show();
            dotsFoodCravings.removeClass("carousel-active").eq(slideFoodCravings - 1).addClass("carousel-active");
        }
    </script>


    <!---------------- for fashion carousels contents ---------------->
    <script>
        //---------------- for fashion alphabetical carousel ----------------
        let slideFashionAlphabetical = 1;
        showFashionAlphabeticalSlides(slideFashionAlphabetical);

        // function plusSlidesCinema(n) {
        //     showFashionAlphabeticalSlides((slideFashionAlphabetical += n));
        // }

        function currentSlidePerFashionAlphabetical(n) {
            showFashionAlphabeticalSlides((slideFashionAlphabetical = n));
        }

        $(".fashionAlphabeticalPrev").on("click", function(){
            const n = -1;
            showFashionAlphabeticalSlides((slideFashionAlphabetical += n));
        });
        $(".fashionAlphabeticalNext").on("click", function(){
            const n = 1;
            showFashionAlphabeticalSlides((slideFashionAlphabetical += n));
        });

        function showFashionAlphabeticalSlides(n) {
            let slidesFashionAlphabetical = $(".carousel-item-per-fashion-alphabetical");
            let dotsFashionAlphabetical = $(".carousel-dot-per-fashion-alphabetical");

            slideFashionAlphabetical = n > slidesFashionAlphabetical.length ? 1 : n < 1 ? slidesFashionAlphabetical.length : slideFashionAlphabetical;
            $(".right-btn-carousel-per-fashion-alphabetical").toggle(slideFashionAlphabetical !== slidesFashionAlphabetical.length);
            $(".left-btn-carousel-per-fashion-alphabetical").toggle(slideFashionAlphabetical !== 1);

            slidesFashionAlphabetical.hide().eq(slideFashionAlphabetical - 1).show();
            dotsFashionAlphabetical.removeClass("carousel-active").eq(slideFashionAlphabetical - 1).addClass("carousel-active");
        }

        //---------------- for fashion trends carousel ----------------
        let slideFashion = 1;
        showFashionSlides(slideFashion);

        // function plusSlidesCinema(n) {
        //     showFashionSlides((slideFashion += n));
        // }

        function currentSlidePerTrends(n) {
            showFashionSlides((slideFashion = n));
        }

        $(".FashionTrendsPrev").on("click", function(){
            const n = -1;
            showFashionSlides((slideFashion += n));
        });
        $(".FashionTrendsNext").on("click", function(){
            const n = 1;
            showFashionSlides((slideFashion += n));
        });

        function showFashionSlides(n) {
            let slidesFashion = $(".carousel-item-per-trends");
            let dotsFashion = $(".carousel-dot-per-trends");

            slideFashion = n > slidesFashion.length ? 1 : n < 1 ? slidesFashion.length : slideFashion;
            $(".right-btn-carousel-per-trends").toggle(slideFashion !== slidesFashion.length);
            $(".left-btn-carousel-per-trends").toggle(slideFashion !== 1);

            slidesFashion.hide().eq(slideFashion - 1).show();
            dotsFashion.removeClass("carousel-active").eq(slideFashion - 1).addClass("carousel-active");
        }
    </script>


    <!---------------- for consumer electronics carousels contents ---------------->
    <script>
        //---------------- for electronics alphabetical carousel ----------------
        let slideElectronicsAlphabetical = 1;
        showElectronicsAlphabeticalSlides(slideElectronicsAlphabetical);

        // function plusSlidesCinema(n) {
        //     showElectronicsAlphabeticalSlides((slideElectronicsAlphabetical += n));
        // }

        function currentSlidePerElectronicsAlphabetical(n) {
            showElectronicsAlphabeticalSlides((slideElectronicsAlphabetical = n));
        }

        $(".ElectronicsAlphabeticalPrev").on("click", function(){
            const n = -1;
            showElectronicsAlphabeticalSlides((slideElectronicsAlphabetical += n));
        });
        $(".ElectronicsAlphabeticalNext").on("click", function(){
            const n = 1;
            showElectronicsAlphabeticalSlides((slideElectronicsAlphabetical += n));
        });

        function showElectronicsAlphabeticalSlides(n) {
            let slidesElectronicsAlphabetical = $(".carousel-item-per-electronics-alphabetical");
            let dotsElectronicsAlphabetical = $(".carousel-dot-per-electronics-alphabetical");

            slideElectronicsAlphabetical = n > slidesElectronicsAlphabetical.length ? 1 : n < 1 ? slidesElectronicsAlphabetical.length : slideElectronicsAlphabetical;
            $(".right-btn-carousel-per-electronics-alphabetical").toggle(slideElectronicsAlphabetical !== slidesElectronicsAlphabetical.length);
            $(".left-btn-carousel-per-electronics-alphabetical").toggle(slideElectronicsAlphabetical !== 1);

            slidesElectronicsAlphabetical.hide().eq(slideElectronicsAlphabetical - 1).show();
            dotsElectronicsAlphabetical.removeClass("carousel-active").eq(slideElectronicsAlphabetical - 1).addClass("carousel-active");
        }

        //---------------- for electronics latest carousel ----------------
        let slideElectronics = 1;
        showElectronicsSlides(slideElectronics);

        // function plusSlidesCinema(n) {
        //     showElectronicsSlides((slideElectronics += n));
        // }

        function currentSlidePerLatest(n) {
            showElectronicsSlides((slideElectronics = n));
        }

        $(".ElectronicsLatestPrev").on("click", function(){
            const n = -1;
            showElectronicsSlides((slideElectronics += n));
        });
        $(".ElectronicsLatestNext").on("click", function(){
            const n = 1;
            showElectronicsSlides((slideElectronics += n));
        });

        function showElectronicsSlides(n) {
            let slidesElectronics = $(".carousel-item-per-latest");
            let dotsElectronics = $(".carousel-dot-per-latest");

            slideElectronics = n > slidesElectronics.length ? 1 : n < 1 ? slidesElectronics.length : slideElectronics;
            $(".right-btn-carousel-per-latest").toggle(slideElectronics !== slidesElectronics.length);
            $(".left-btn-carousel-per-latest").toggle(slideElectronics !== 1);

            slidesElectronics.hide().eq(slideElectronics - 1).show();
            dotsElectronics.removeClass("carousel-active").eq(slideElectronics - 1).addClass("carousel-active");
        }
    </script>


    <!---------------- for services carousels contents ---------------->
    <script>
        //---------------- for services alphabetical carousel ----------------
        let slideServicesAlphabetical = 1;
        showServicesAlphabeticalSlides(slideServicesAlphabetical);

        // function plusSlidesCinema(n) {
        //     showServicesAlphabeticalSlides((slideServicesAlphabetical += n));
        // }

        function currentSlidePerServicesAlphabetical(n) {
            showServicesAlphabeticalSlides((slideServicesAlphabetical = n));
        }

        $(".ServicesAlphabeticalPrev").on("click", function(){
            const n = -1;
            showServicesAlphabeticalSlides((slideServicesAlphabetical += n));
        });
        $(".ServicesAlphabeticalNext").on("click", function(){
            const n = 1;
            showServicesAlphabeticalSlides((slideServicesAlphabetical += n));
        });

        function showServicesAlphabeticalSlides(n) {
            let slidesServicesAlphabetical = $(".carousel-item-per-services-alphabetical");
            let dotsServicesAlphabetical = $(".carousel-dot-per-services-alphabetical");

            slideServicesAlphabetical = n > slidesServicesAlphabetical.length ? 1 : n < 1 ? slidesServicesAlphabetical.length : slideServicesAlphabetical;
            $(".right-btn-carousel-per-services-alphabetical").toggle(slideServicesAlphabetical !== slidesServicesAlphabetical.length);
            $(".left-btn-carousel-per-services-alphabetical").toggle(slideServicesAlphabetical !== 1);

            slidesServicesAlphabetical.hide().eq(slideServicesAlphabetical - 1).show();
            dotsServicesAlphabetical.removeClass("carousel-active").eq(slideServicesAlphabetical - 1).addClass("carousel-active");
        }

        //---------------- for services services carousel ----------------
        let slideServices = 1;
        showServicesSlides(slideServices);

        // function plusSlidesCinema(n) {
        //     showServicesSlides((slideServices += n));
        // }

        function currentSlidePerServices(n) {
            showServicesSlides((slideServices = n));
        }

        $(".FunctionServicesPrev").on("click", function(){
            const n = -1;
            showServicesSlides((slideServices += n));
        });
        $(".FunctionServicesNext").on("click", function(){
            const n = 1;
            showServicesSlides((slideServices += n));
        });

        function showServicesSlides(n) {
            let slidesServices = $(".carousel-item-per-services");
            let dotsServices = $(".carousel-dot-per-services");

            slideServices = n > slidesServices.length ? 1 : n < 1 ? slidesServices.length : slideServices;
            $(".right-btn-carousel-per-services").toggle(slideServices !== slidesServices.length);
            $(".left-btn-carousel-per-services").toggle(slideServices !== 1);

            slidesServices.hide().eq(slideServices - 1).show();
            dotsServices.removeClass("carousel-active").eq(slideServices - 1).addClass("carousel-active");
        }
    </script>


    <!---------------- for essentials & novelties carousels contents ---------------->
    <script>
        //---------------- for essentials alphabetical carousel ----------------
        let slideEssentialsAlphabetical = 1;
        showEssentialsAlphabeticalSlides(slideEssentialsAlphabetical);

        // function plusSlidesCinema(n) {
        //     showEssentialsAlphabeticalSlides((slideEssentialsAlphabetical += n));
        // }

        function currentSlidePerEssentialsAlphabetical(n) {
            showEssentialsAlphabeticalSlides((slideEssentialsAlphabetical = n));
        }

        $(".EssentialsAlphabeticalPrev").on("click", function(){
            const n = -1;
            showEssentialsAlphabeticalSlides((slideEssentialsAlphabetical += n));
        });
        $(".EssentialsAlphabeticalNext").on("click", function(){
            const n = 1;
            showEssentialsAlphabeticalSlides((slideEssentialsAlphabetical += n));
        });

        function showEssentialsAlphabeticalSlides(n) {
            let slidesEssentialsAlphabetical = $(".carousel-item-per-essentials-alphabetical");
            let dotsEssentialsAlphabetical = $(".carousel-dot-per-essentials-alphabetical");

            slideEssentialsAlphabetical = n > slidesEssentialsAlphabetical.length ? 1 : n < 1 ? slidesEssentialsAlphabetical.length : slideEssentialsAlphabetical;
            $(".right-btn-carousel-per-essentials-alphabetical").toggle(slideEssentialsAlphabetical !== slidesEssentialsAlphabetical.length);
            $(".left-btn-carousel-per-essentials-alphabetical").toggle(slideEssentialsAlphabetical !== 1);

            slidesEssentialsAlphabetical.hide().eq(slideEssentialsAlphabetical - 1).show();
            dotsEssentialsAlphabetical.removeClass("carousel-active").eq(slideEssentialsAlphabetical - 1).addClass("carousel-active");
        }

        //---------------- for essentials goodies carousel ----------------
        let slideEssentials = 1;
        showEssentialsSlides(slideEssentials);

        // function plusSlidesCinema(n) {
        //     showEssentialsSlides((slideEssentials += n));
        // }

        function currentSlidePerGoodies(n) {
            showEssentialsSlides((slideEssentials = n));
        }

        $(".EssentialsGoodiesPrev").on("click", function(){
            const n = -1;
            showEssentialsSlides((slideEssentials += n));
        });
        $(".EssentialsGoodiesNext").on("click", function(){
            const n = 1;
            showEssentialsSlides((slideEssentials += n));
        });

        function showEssentialsSlides(n) {
            let slidesEssentials = $(".carousel-item-per-goodies");
            let dotsEssentials = $(".carousel-dot-per-goodies");

            slideEssentials = n > slidesEssentials.length ? 1 : n < 1 ? slidesEssentials.length : slideEssentials;
            $(".right-btn-carousel-per-goodies").toggle(slideEssentials !== slidesEssentials.length);
            $(".left-btn-carousel-per-goodies").toggle(slideEssentials !== 1);

            slidesEssentials.hide().eq(slideEssentials - 1).show();
            dotsEssentials.removeClass("carousel-active").eq(slideEssentials - 1).addClass("carousel-active");
        }
    </script>

    <script>
        //---------------- for search carousel ----------------
        let slideSearch = 1;
        showSearchSlides(slideSearch);

        // function plusSlidesCinema(n) {
        //     showSearchSlides((slideSearch += n));
        // }

        function currentSlidePerSearch(n) {
            showSearchSlides((slideSearch = n));
        }

        $(".SearchPrev").on("click", function(){
            const n = -1;
            showSearchSlides((slideSearch += n));
        });
        $(".SearchNext").on("click", function(){
            const n = 1;
            showSearchSlides((slideSearch += n));
        });

        function showSearchSlides(n) {
            let slidesSearch = $(".carousel-item-per-search");
            let dotsSearch = $(".carousel-dot-per-search");

            slideSearch = n > slidesSearch.length ? 1 : n < 1 ? slidesSearch.length : slideSearch;
            $(".right-btn-carousel-per-search").toggle(slideSearch !== slidesSearch.length);
            $(".left-btn-carousel-per-search").toggle(slideSearch !== 1);

            slidesSearch.hide().eq(slideSearch - 1).show();
            dotsSearch.removeClass("carousel-active").eq(slideSearch - 1).addClass("carousel-active");
        }
    </script>

    <!---------------- for promo carousel content ---------------->
    <script>
        // for promos carousel
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides((slideIndex += n));
        }

        function currentSlide(n) {
            showSlides((slideIndex = n));
        }

        function showSlides(n) {
            let slides = $(".carousel-item-promo");
            let dots = $(".carousel-dot-promo");

            slideIndex = n > slides.length ? 1 : n < 1 ? slides.length : slideIndex;

            $(".right-btn-carousel").toggle(slideIndex !== slides.length);
            $(".left-btn-carousel").toggle(slideIndex !== 1);

            slides.hide().eq(slideIndex - 1).show();
            dots.removeClass("carousel-active").eq(slideIndex - 1).addClass("carousel-active");
        }
    </script>

    <!---------------- for cinema carousels contents ---------------->
    <script>
        //---------------- for cinema - cinema locator carousel ----------------
        let slideCinemaLocator = 1;
        showCinemaLocatorSlides(slideCinemaLocator);

        // function plusSlidesCinema(n) {
        //     showCinemaLocatorSlides(( slideCinemaLocator += n));
        // }

        // function currentSlidePerCinemaLocator(n) {
        //     showCinemaLocatorSlides((slideCinemaLocator = n));
        // }

        $(".CinemaLocatorPrev").on("click", function(){
            const n = -1;
            showCinemaLocatorSlides((slideCinemaLocator += n));
        });
        $(".CinemaLocatorNext").on("click", function(){
            const n = 1;
            showCinemaLocatorSlides((slideCinemaLocator += n));
        });

        function showCinemaLocatorSlides(n) {
            let slidesCinemaLocator = $(".carousel-item-per-cinema-locator");

            // let dotsCinemaLocator = $(".carousel-dot-per-cinema-locator");

            slideCinemaLocator = n > slidesCinemaLocator.length ? 1 : n < 1 ? slidesCinemaLocator.length : slideCinemaLocator;
            $(".right-btn-carousel-per-cinema-locator").toggle(slideCinemaLocator !== slidesCinemaLocator.length);
            $(".left-btn-carousel-per-cinema-locator").toggle(slideCinemaLocator !== 1);

            // for adjusting the column if the content is more than 6 cards
            var cinemaContentPosition = $("#CinemaCardContentCinemaLocator > div").length;
            if (cinemaContentPosition <= 6) {
                $(".cinemaCardsCinemaLocator").addClass("col-lg-12");
                $(".cinemaCardsCinemaLocator").addClass("col-xl-12");
                $(".CinemaCardContentCinemaLocator").addClass("cinema-card-container");
                $(".CinemaCardContentCinemaLocator").removeClass("cinema-card-container-expand");
                $(".cinemaCardsCinemaLocator").removeClass("col-xl-6");
                $(".cinemaCardsCinemaLocator").removeClass("col-lg-6");
            }

            else if (cinemaContentPosition > 6) {
                $(".cinemaCardsCinemaLocator").removeClass("col-lg-12");
                $(".cinemaCardsCinemaLocator").removeClass("col-xl-12");
                $(".CinemaCardContentCinemaLocator").removeClass("cinema-card-container");
                $(".CinemaCardContentCinemaLocator").addClass("cinema-card-container-expand");
                $(".cinemaCardsCinemaLocator").addClass("col-xl-6");
                $(".cinemaCardsCinemaLocator").addClass("col-lg-6");
            }

            slidesCinemaLocator.hide().eq(slideCinemaLocator - 1).show();
            // dotsCinemaLocator.removeClass("carousel-active").eq(slideCinemaLocator - 1).addClass("carousel-active");

            if(slideCinemaLocator == slidesCinemaLocator.length){
                var cinemaCount = $(".CinemaCardContentCinemaLocator > div").length;
                var cinemaModulo = cinemaCount %  12;
                if (cinemaModulo <= 6){
                    $(".CinemaCardContentCinemaLocator").each(function(){
                        // $(".cinemaCardsCinemaLocator").removeClass("mx-auto");
                        $(".cinemaCardsCinemaLocator").addClass("col-lg-12");
                        $(".cinemaCardsCinemaLocator").addClass("col-xl-12");
                        $(".CinemaCardContentCinemaLocator").addClass("cinema-card-container");
                        $(".CinemaCardContentCinemaLocator").removeClass("cinema-card-container-expand");
                    });
                    
                }
                else if (cinemaModulo === 0){
                    $(".CinemaCardContentCinemaLocator").each(function(){
                        $(".cinemaCardsCinemaLocator").removeClass("col-lg-12");
                        $(".cinemaCardsCinemaLocator").removeClass("col-xl-12");
                        $("#CinemaCardContentCinemaLocator").removeClass("cinema-card-container");
                        $("#CinemaCardContentCinemaLocator").addClass("cinema-card-container-expand");
                        $(".cinemaCardsCinemaLocator").addClass("col-xl-6");
                        $(".cinemaCardsCinemaLocator").addClass("col-lg-6");
                    });
                }
                else if (cinemaModulo > 6){
                    $(".CinemaCardContentCinemaLocator").each(function(){
                        $(".cinemaCardsCinemaLocator").removeClass("mx-auto");
                        $(".CinemaCardContentCinemaLocator").addClass("cinema-card-container-expand");
                    });
                }

            }
            $(".left-btn-carousel-per-cinema-locator").click(function () {
                $(".CinemaCardContentCinemaLocator").each(function(){
                    $(".cinemaCardsCinemaLocator").removeClass("col-lg-12");
                    $(".cinemaCardsCinemaLocator").removeClass("col-xl-12");
                    $("#CinemaCardContentCinemaLocator").removeClass("cinema-card-container");
                    $("#CinemaCardContentCinemaLocator").addClass("cinema-card-container-expand");
                    $(".cinemaCardsCinemaLocator").addClass("col-xl-6");
                    $(".cinemaCardsCinemaLocator").addClass("col-lg-6");
                });
                
            });
            
        }
    </script>


<!-- title -->
<div class="p-3 font-weight-bold nav-titles translateme" data-en="Cinema">Cinema</div>

<div class="tab-content" id="Cinema-nav-tab-content">
    <div class="tab-pane show active" id="Tab-Cinema" role="tabpanel">
        <div class="p-2 text-center cinema-page-title translateme" data-en="Cinema Locator">Cinema Locator</div>
        <!-- CINEMA LIST -->
        <div class="slideshow-content-container cinema-list">
        </div>
    </div>    
    <div class="tab-pane" id="Tab-Schedule" role="tabpanel">
        <!-- NOW SHOWING LIST -->
        <div id="CinemaTabSchedule">
            <div class="p-2 text-center cinema-page-title-2 translateme" data-en="Now Showing">Now Showing</div>
            <div class="slideshow-content-container d-flex justify-content-center now-showing-list">
            </div>
        </div>  
        <!-- SHOWING NOT FOUND -->
        <div id="CinemaTabDefault">
            <div class="p-2 text-center cinema-page-title-2 translateme" data-en="Now Showing">Now Showing</div>
            <div class="d-flex justify-content-center">
                <img class="ImgCinemaDefault" src="{{ URL::to('themes/sm_default/images/cinema-default-desktop.png') }}">
            </div>
        </div>      
    </div>
</div>

<!-- Cinema navigation -->
<div class="cinema-nav-tabs"> 
    <span class="mr-4 nav-tab-title translateme" data-en="Select to view">Select to view: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Cinema-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn translateme" id="Tab-Cinema-Tab" data-toggle="pill" data-target="#Tab-Cinema" type="button" role="tab" aria-controls="Tab-Cinema" aria-selected="true" data-en="Cinema">Cinema</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn translateme" id="Tab-Schedule-tab" data-toggle="pill" data-target="#Tab-Schedule" type="button" role="tab" aria-controls="Tab-Schedule" aria-selected="false" data-en="Schedule">Schedule</button>
        </li>
    </ul>
</div>

<!-- SHOWING DETAILS -->
<div id="CinemaDetailsModal" class="modal CinemaDetailsModalContent">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 CinemaDeatilsModalContainer">
            <span class="close closeCinemaDetails text-white">&times;</span>
            <div class="modal-body p-5 card card-body movieDetailsContentContainer">
                <div class="now-showing-details">
                    <div class="row">
                        <div class="col-9 text-left">
                            <span class="MovieMainTitle"> <span class="movie-title"></span> <span class="movie-rating"></span> </span>
                            <div class="mt-2 text-justify cinema-details-container">
                                <div class="cinema-details-content"></div>
                            </div>
                            <br />
                            <span class="movie-genre">Genre: Drama</span> <br />
                            <span class="movie-cast">Cast: Song Joong-Ki, Hong Sa-Bin</span>
                        </div>
                        <div class="col-3 text-center">
                            <!-- Object Fit Images -->
                            <img class="movieImgView" src="#" data-filmid="#" />
                        
                            <div class="mt-2"><button class="btn btn-prestige-color watch-trailer" data-src="">Watch Trailer</button>
                            </div>

                            <div class="mt-2">
                                <button class="btn btn-prestige" disabled="disabled">Buy Tickets</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-2">
                        <span class="cinema-sched-title"><span>Cinema Schedule</span>:</span>
                        <div class="cinema-details-sched-container">
                            <div class="d-flex cinema-details-sched-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="now-showing-trailer">
                    <div id="videocontainer" class="embed-responsive embed-responsive-16by9">
                        
                    </div>
                    <div id="novideo" style="display: none; text-align: center;">
                        <img src="{{ URL::to('themes/sm_default/images/no-internet.png') }}" style="width: 508px;" />
                    </div>
                </div>
 
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var cinemas = "{{ $cinemas }}";
    var now_showing = "{{ $now_showing }}";

    $(document).ready(function() {
        $('#Tab-Cinema-Tab').on('click', function() {
            showCinemas();
        });

        $('#Tab-Schedule-tab').on('click', function() {
            showNowShowing();
        });

        var modalCinema = $("#CinemaDetailsModal");
        var spanCinema = $(".closeCinemaDetails");

        spanCinema.on("click", function () {
            $('#videocontainer').html('');
            $('.now-showing-trailer').hide();
            $('.now-showing-details').show();
            modalCinema.css("display", "none");
        });

        $('.watch-trailer').on('click', function() {
            var link = $(this).data('src').replace("watch?v=", "embed/");
            var youtube_str = '<iframe class="embed-responsive-item" width="420" height="315" src="'+link+'?autoplay=1&loop=0&controls=0&showinfo=0&fs=0&disablekb=1&" id="video" allowscriptaccess="always" allow="autoplay;"></iframe>';
            $('#videocontainer').html(youtube_str);
            $('.now-showing-trailer').show();
            $('.now-showing-details').hide();
        })
    });

    function showCinemas() {
        var my_cinemas = JSON.parse(helper.decodeEntities(cinemas));
        $('.cinema-list').html('');
        $('.cinema-list').html('<div class="owl-carousel owl-theme owl-wrapper-cinema"></div>');
        $.each(my_cinemas, function(key,cinemas) {
            var cinema_element = '';
            cinema_element = '<div class="item">';
            cinema_element += '<div class="carousel-content-container-per-food-category mb-4">';
            cinema_element += '<div class="row cinema-'+key+'">';
            cinema_element += '</div>';
            cinema_element += '</div>';
            cinema_element += '</div>';
            $( ".owl-wrapper-cinema" ).append(cinema_element);

            $.each(cinemas, function(index,cinema) {
                var cinema_item = '';
                cinema_item += '<div class="col-md-6 mt-3 mx-auto cinemaCards">';
                cinema_item += '<div class="card cinema-cards border-0">';
                cinema_item += '<div class="d-flex flex-row pb-1 my-auto">';
                cinema_item += '<div class="my-auto">';
                cinema_item += '<img class="cinema-img" src="resources/uploads/cinema/assets/images/sm-cinema-logo.png" alt="...">';
                cinema_item += '</div>';
                cinema_item += '<div class="mt-2">';
                cinema_item += '<div class="cinema-txt-1">'+cinema.brand_name+'</div>';
                cinema_item += '<div class="cinema-txt-2">'+cinema.location+'</div>';
                cinema_item += '</div>';
                cinema_item += '</div>';                         
                cinema_item += '</div>';
                cinema_item += '</div>';
                $( ".cinema-"+key ).append(cinema_item);
                // MAP FUNCTION HERE

            });
        });

        var navigation_button = '';
        navigation_button += '<a class="promo-prev">';
        navigation_button += '<div class="left-btn-carousel left-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="resources/uploads/imagebutton/Left.png">';
        navigation_button += '</div>';
        navigation_button += '</a>';
        navigation_button += '<a class="promo-next">';
        navigation_button += '<div class="right-btn-carousel right-btn-carousel-per-food-alphabetical">';
        navigation_button += '<img src="resources/uploads/imagebutton/Right.png">';
        navigation_button += '</div>';
        navigation_button += '</a>';

        $('.cinema-list').append(navigation_button);

        var cinema = $('.owl-wrapper-cinema');
        cinema.on("initialized.owl.carousel", function(e) {
            if(e.item.count == 1) {
                $('.promo-prev').hide();
                $('.promo-next').hide();
            }
            else {
                $('.promo-prev').hide();
                $('.promo-next').show();
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });

        $('.promo-next').click(function() {
            cinema.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            cinema.trigger('prev.owl.carousel');
        })

        cinema.on('changed.owl.carousel', function(e) {
            var first = ( !e.item.index)
            if( first ){
                $('.promo-prev').hide();
            }
            else {
                $('.promo-prev').show();
            }

            var total = e.relatedTarget.items().length - 1;
            var current = e.item.index;
            if(total == current) {
                $('.promo-next').hide();
            }
            else {
                $('.promo-next').show();
            }
            
        });
    }

    function showNowShowing() {
        var my_showing = JSON.parse(helper.decodeEntities(now_showing));
        console.log(my_showing.length);
        if(my_showing.length == 0) {
            $('#CinemaTabSchedule').hide();
            $('#CinemaTabDefault').show();
            return false;
        }

        $('.now-showing-list').html('');
        $('.now-showing-list').html('<div class="owl-carousel owl-theme owl-wrapper-now-showing"></div>');
        $.each(my_showing, function(key,showings) {
            var showing_element = '';
            showing_element += '<div class="item">';
            showing_element += '<div class="carousel-content-container-cinema carouselContentAdjustment mb-4">';
            showing_element += '<div class="row cinemaCardPosition now-showing-'+key+'">';
            showing_element += '</div>';
            showing_element += '</div>';
            showing_element += '</div>';
            $( ".owl-wrapper-now-showing" ).append(showing_element);

            $.each(showings, function(index,showing) {
                var showing_item = '';
                showing_item += '<div class="col-sm-4 showing-details-'+showing.film_id+'">';
                showing_item += '<div class="card img-cinema-card">';
                showing_item += '<img type="button" class="movie-img" src="/uploads/media/cinema/'+showing.film_id+'.jpg">';
                showing_item += '</div>';
                showing_item += '<div class="text-center MovieTitle">'+showing.title+'</div>';
                showing_item += '<div class="text-center ViewDetails">';
                showing_item += '<button class="btn btn-lg BtnCinemaDetails">See Details</button>';
                showing_item += '</div>';
                showing_item += '</div>';
                $( ".now-showing-"+key ).append(showing_item);
                $('.showing-details-'+showing.film_id).on('click', function() {
                    $('.now-showing-trailer').hide();
                    $('.now-showing-details').show();

                    $('.movie-title').html(showing.title);
                    $('.movie-rating').html(showing.rating);
                    $('.movie-genre').html(showing.genre_name);
                    $('.movie-cast').html(showing.casting);
                    $('.cinema-details-content').html(showing.synopsis);
                    $('.movieImgView').attr("src", '/uploads/media/cinema/'+showing.film_id+'.jpg');
                    $('.watch-trailer').attr("data-src", showing.trailer_url);
                    $('.cinema-details-sched-content').html('');
                    generateSchedules(showing.cinema_schedules);
                    $("#CinemaDetailsModal").css("display", "block");
                });

            });

        });

        var navigation_button = '';
        navigation_button += '<a class="promo-prev">';
        navigation_button += '<div class="left-btn-carousel">';
        navigation_button += '<img src="resources/uploads/imagebutton/Left.png">';
        navigation_button += '</div>';
        navigation_button += '</a>';
        navigation_button += '<a class="promo-next">';
        navigation_button += '<div class="right-btn-carousel">';
        navigation_button += '<img src="resources/uploads/imagebutton/Right.png">';
        navigation_button += '</div>';
        navigation_button += '</a>';

        $('.now-showing-list').append(navigation_button);

        var showing = $('.owl-wrapper-now-showing');
        showing.on("initialized.owl.carousel", function(e) {
            if(e.item.count == 1) {
                $('.promo-prev').hide();
                $('.promo-next').hide();
            }
            else {
                $('.promo-prev').hide();
                $('.promo-next').show();
            }
        }).owlCarousel({
            margin: 0,
            nav: false,
            loop: false,
            items: 1,
        });

        $('.promo-next').click(function() {
            showing.trigger('next.owl.carousel');
        })

        $('.promo-prev').click(function() {
            showing.trigger('prev.owl.carousel');
        })

        showing.on('changed.owl.carousel', function(e) {
            var first = ( !e.item.index)
            if( first ){
                $('.promo-prev').hide();
            }
            else {
                $('.promo-prev').show();
            }

            var total = e.relatedTarget.items().length - 1;
            var current = e.item.index;
            if(total == current) {
                $('.promo-next').hide();
            }
            else {
                $('.promo-next').show();
            }
            
        });
    }

    function generateSchedules(cinemas) {
        $.each(cinemas, function(key,schedules) {
            var schedule_str = '';
            schedule_str += '<div class="mt-3 mb-1 mx-4 SmCinemaSched">';
            schedule_str += ''+key+'';
            $.each(schedules, function(index,schedule) {
                schedule_str += '<div class="text-center">'+schedule+'</div>';
            });
            schedule_str += '</div>';
            $('.cinema-details-sched-content').append(schedule_str);
        });        
    }

</script>
@endpush
<!-- title -->
<div class="p-3 font-weight-bold nav-titles">Cinema</div>

<div class="slideshow-content-container">
    <div class="tab-content">
        <div class="tab-pane show active" id="tab-cinema" role="tabpanel">
            <div class="p-2 text-center cinema-page-title">Cinema Locator</div>
            <div>
                <div class="owl-carousel owl-theme owl-wrapper-cinema">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="tab-content" id="Cinema-nav-tab-content">

    <div class="tab-pane show active" id="Tab-Cinema" role="tabpanel">
        <div class="p-2 text-center cinema-page-title">Cinema Locator</div>
        <div class="owl-carousel owl-theme owl-wrapper-cinema">
        </div>
    </div>
    
    <div class="tab-pane" id="Tab-Schedule" role="tabpanel">        
        <div id="CinemaTabDefault">
        </div>
    </div>
</div> -->

<!-- Cinema navigation -->
<!-- <div class="cinema-nav-tabs"> 
    <span class="mr-4 nav-tab-title">Select to view: </span>
    <ul class="nav nav-pills bg-white nav-tab-pills-container" id="Cinema-nav-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active nav-tab-pills-btn" id="Tab-Cinema-Tab" data-toggle="pill" data-target="#Tab-Cinema" type="button" role="tab" aria-controls="Tab-Cinema" aria-selected="true">Cinema</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-tab-pills-btn" id="Tab-Schedule-tab" data-toggle="pill" data-target="#Tab-Schedule" type="button" role="tab" aria-controls="Tab-Schedule" aria-selected="false">Schedule</button>
        </li>
    </ul>
</div> -->

@push('scripts')
<script>
    var cinemas = "{{ $cinemas }}";
    var now_showing = "{{ $now_showing }}";

    $(document).ready(function() {
        var cinema = $('.owl-wrapper-cinema');
        cinema.owlCarousel({
            margin: 10,
            nav: false,
            loop: false,
            items: 1,
        });
    });

    function showCinemas() {
        var my_cinemas = JSON.parse(decodeEntities(cinemas));
        console.log(my_cinemas);
        $( ".owl-wrapper-cinema" ).html('');
        $.each(my_cinemas, function(key,cinemas) {
            var cinema_element = '';
            cinema_element += '<div class="item">';
            cinema_element += '<div class="row px-5 cinema-card-container-expand cinema-'+key+'">';
            cinema_element += '</div>';
            cinema_element += '</div>';
            $( ".owl-wrapper-cinema" ).append(cinema_element);

            $.each(cinemas, function(index,cinema) {
                var cinema_item = '';
                cinema_item += '<div class="col-md-12 col-xl-6 mt-3 mx-auto cinemaCards">';
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
    }

    function showNowShowing() {
        var my_showing = JSON.parse(decodeEntities(now_showing));
        $( ".swiper-container-shows" ).html('');
        $.each(my_showing, function(key,showings) {
            var showing_element = '';
            showing_element += '<div class="swiper-slide">';
            showing_element += '<div class="carousel-content-container-cinema carouselContentAdjustment">';
            showing_element += '<div class="row cinemaCardPosition now-showing-'+key+'">';
            showing_element += '</div>';
            showing_element += '</div>';
            showing_element += '</div>';
            $( ".swiper-container-shows" ).append(showing_element);

            $.each(showings, function(index,showing) {
                var showing_item = '';
                showing_item += '<div class="col-sm-4">';
                showing_item += '<div class="card img-cinema-card">';
                showing_item += '<img type="button" id="CinemaDetailsViewBtn" class="movie-img" src ="/uploads/media/cinema/'+showing.film_id+'.jpg">';
                showing_item += '</div>';
                showing_item += '<div class="text-center MovieTitle">'+showing.title+'</div>';
                showing_item += '<div class="text-center ViewDetails">';
                showing_item += '<button class="btn btn-lg BtnCinemaDetails" id="CinemaDetailsViewBtn">See Details</button>';
                showing_item += '</div>';
                showing_item += '</div>';

                $( ".now-showing-"+key ).append(showing_item);
                // MAP FUNCTION HERE

            });

        });
    }

    showCinemas();
    //showNowShowing();

    // var swiper_cinema = new Swiper('.swiper-container-cinema', {
    //     pagination: {
    //         el: '.swiper-pagination-cinema',
    //         clickable: true,
    //     },
    //     // Navigation arrows
    //     navigation: {
    //         nextEl: '.swiper-button-cinema-next',
    //         prevEl: '.swiper-button-cinema-prev',
    //     },
    // });

    // var swiper_showing = new Swiper('.swiper-container', {
    //     pagination: {
    //         el: '.swiper-pagination-shows',
    //         clickable: true,
    //     },
    //     // Navigation arrows
    //     navigation: {
    //         nextEl: '.swiper-button-shows-next',
    //         prevEl: '.swiper-button-shows-prev',
    //     },
    // });

</script>
@endpush
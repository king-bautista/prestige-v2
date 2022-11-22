<template>
    <div style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title">{{ page_title }}</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder">
            </div>
        </div>
        <div>
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    {{ tab_title }}
                </div>
            </div>
            <div v-show="cinema_locator" class="row col-md-10 offset-md-1">
                <div id="myCinemas" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="cinema_list.length > 0">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCinemas" v-for="(cinemas, index) in cinema_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(cinemas, index) in cinema_list" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="cinema in cinemas" class="col-12 col-sm-6 text-left mt-3">
                                    <div class="cinema-store bg-white text-center box-shadowed ml-3">
                                        <div class="image-holder h-100">
                                            <img :src="cinema.brand_logo" :alt="cinema.brand_name">
                                        </div>
                                        <div class="text-left pta-2 brand-name">
                                            <div class="shop_name">{{ cinema.brand_name }}</div>
                                            <div style="font-size: 0.7em;color:#2a2a2a">{{ cinema.building_name }}, {{ cinema.floor_name }} </div>
                                            <div style="font-weight: bold;font-size: 0.7em">
                                                <span class="translateme text-success" v-if="cinema.active==1">Open</span>
                                                <span class="translateme text-success" v-if="cinema.active==0">Close</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#myCinemas" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myCinemas" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" class="no-record-found">
            </div>
            <div v-show="schedules" class="row col-md-10 offset-md-1">
                <div id="myMovies" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="schedule_list.length > 0">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myMovies" v-for="(movies, index) in schedule_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(movies, index) in schedule_list" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="movie in movies" class="col-12 col-sm-4 text-center mt-3">
                                    <img class="schedule-image" v-bind:src="'//www.smcinema.com/CDN/media/entity/get/FilmPosterGraphic/h-'+movie.film_id+'?width=198&amp;height=247&amp;referenceScheme=HeadOffice&amp;allowPlaceHolder=true'" :data-filmid="movie.film_id">
                                    <div class="text-center click-text" style="font-size:1.3em;color:#000000;padding: 12px 0px;" :data-filmid="movie.film_id">{{ movie.title }}</div>
                                    <div class="text-center seeDetails"><button class="btn btn-sm btn-cinema-details" :data-filmid="movie.film_id" @click="showModal(movie)">See Details</button></div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#myMovies" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myMovies" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found_movies" src="images/stay-tuned-movie.png" class="no-movies-found">
            </div>
        </div>
        <div class="tabs-container">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">Select to view</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" @click="tab_title='Cinema Locator'; cinema_locator = true; schedules = false;">
                    <div>
                        <a class="translateme tenant-category">Cinema</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item" @click="tab_title='Now Showing'; cinema_locator = false; schedules = true;">
                    <div>
                        <a class="translateme tenant-alphabet">Schedule</a>
                    </div>
                </div>
            </div>
        </div>
        <img :src="back_button" style="z-index:999;position:absolute;top:780px;right:15px; cursor:pointer;" @click="goBack">

        <div class="custom-modal">
		    <div class="custom-modal-body">
                <div class="text-right text-white">
                    <span style="font-size:1.5em;margin-right:-10px" class="btn-close-cinema">X</span>
                </div>
                <div class="modal-content" style="border-radius:20px;">
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-9 text-left">
                                <span style="font-size: 2em;position:relative">
                                    <span class="movie-rating"></span>
                                </span>
                                <div class="mt-2 text-justify"></div>
                                <br>Genre: 		
                            </div>
                            <div class="col-3 text-center">
                                <img class="mr-2 schedule-image-2" src="//www.smcinema.com/CDN/media/entity/get/FilmPosterGraphic/h-?width=198&amp;height=247&amp;referenceScheme=HeadOffice&amp;allowPlaceHolder=true" data-filmid="">
                                <div class="mt-2">
                                    <button class="btn btn-prestige-color video-btn" style="width: 76%!important;border-radius:10px" data-src="">Watch Trailer</button>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-prestige" disabled="" style="width: 76%!important;border-radius:10px;color: #b6c3ff;border: 1px solid #b6c3ff;opacity: 30%;">Buy Tickets</button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <span style="font-size:1.2em"><span>Cinema Schedule</span>:</span>
                            <div class="row">
                            </div>
                        </div>

                    </div>
                </div>
		    </div>
	    </div>
    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                cinema_list: [],
                schedule_list: [],
                tab_title: 'Cinema Locator',
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Cinema',
                cinema_locator: true,
                cinema_schedule: false,
                no_record_found: false,
                no_record_found_movies: false,
                movie_details: '',
                schedules: false,
            };
        },

        created() {
            this.getSite();
            this.getCinemaList();
            this.getSchedules();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            goBack: function() {
            },

            getCinemaList: function() {
                axios.get('/api/v1/cinemas')
                .then(response => {
                    this.cinema_list = response.data.data;
                    if(this.cinema_list.length == 0) {
                        this.no_record_found = true;
                    } else {
                        this.no_record_found = false;
                    }
                });
            },

            getSchedules: function() {
                axios.get('/api/v1/now-showing')
                .then(response => {
                    this.schedule_list = response.data.data;
                    if(this.schedule_list.length == 0) {
                        this.no_record_found_movies = true;
                    }else {
                        this.no_record_found_movies = false;
                    }
                });
            },

            showModal: function(data) {
                this.movie_details = data;
                $('#cinema_details').show();
            },
        },

        mounted() {
            $(function() {
                $('.store-tabs-item').click(function () {
                    $('.store-tabs-item').removeClass('tab-item-selected');
                    $(this).addClass('tab-item-selected');
                });

                $(".carousel").carousel({
                    interval: false,
                    pause: true,
                    touch:true,
                });
            });
        },

    };

</script>
<template>
    <div class="router-page" style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title" class="translateme" :data-en="page_title">{{ page_title }}</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder" @click="callHomeMethod">
            </div>
        </div>
        <div>
            <div v-show="cinema_locator">
                <div class="row mt-77 mb-13">
                    <div class="col-md-12 home-title text-center translateme" data-en="Cinema Locator">Cinema Locator</div>
                </div>
                <div class="row col-md-10 offset-md-1">
                    <div id="myCinemas" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-show="cinema_list.length > 0">
                        
                        <!-- Control dots -->
                        <ul class="carousel-indicators z-1" v-show="curent_cinema_list_count > 0">
                            <li data-target="#myCinemas" v-for="(cinemas, index) in cinema_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ul>

                        <div class="carousel-inner custom-p-0-65 carousel-mh-605">
                            <div class="carousel-item custom-p-0-18" v-for="(cinemas, index) in cinema_list" v-bind:class = "(index == 0) ? 'active':''">
                                <div class="row mb-3">
                                    <div v-for="cinema in cinemas" class="col-12 col-sm-6 text-left mt-3" @click="helper.saveLogs(cinema, 'Cinema');findStore(cinema);">
                                        <div class="cinema-store bg-white text-center box-shadowed">
                                            <div class="image-holder-cinema h-100">
                                                <img :src="cinema.brand_logo" :alt="cinema.brand_name">
                                            </div>
                                            <div class="text-left pta-2 brand-name">
                                                <div class="shop_name">{{ cinema.brand_name }}</div>
                                                <div style="font-size: 0.7em;color:#2a2a2a">{{ cinema.floor_name }}, {{ cinema.building_name }} </div>
                                                <div style="font-weight: bold;font-size: 0.7em">
                                                    <span class="translateme text-success" v-if="cinema.active==1" data-en="Open">Open</span>
                                                    <span class="translateme text-success" v-if="cinema.active==0" data-en="Close">Close</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>

                        <button class="carousel-control-prev control-prev-cpc" type="button" data-target="#myCinemas" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next control-next-cpc" type="button" data-target="#myCinemas" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                    <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" class="no-record-found">
                </div>
            </div>
            <div v-show="schedules">
                <div class="row mt-77 mb-13">
                    <div class="col-md-12 home-title text-center translateme" data-en="Now Showing">Now Showing</div>
                </div>
                <div class="row col-md-10 offset-md-1">
                    <div id="myMovies" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false" v-show="schedule_list.length > 0">
                        <!-- Control dots -->
                        <ul class="carousel-indicators z-1">
                            <li data-target="#myMovies" v-for="(movies, index) in schedule_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"></li>
                        </ul>
                        <div class="carousel-inner custom-w-818-ma carousel-mh-528">
                            <div class="carousel-item" v-for="(movies, index) in schedule_list" v-bind:class = "[index == 0 ? 'first-item active':'', index == curent_schedule_list_count? 'last-item':'']">
                                <div class="row mb-3 justify-content-center">
                                    <div v-for="movie in movies" class="col-12 col-sm-4 text-center mt-3">
                                        <img class="schedule-image" v-bind:src="'/uploads/media/cinema/'+movie.film_id+'.jpg'" :data-filmid="movie.film_id">
                                        <div class="text-center click-text" style="font-size:1.3em;color:#000000;padding: 12px 0px;" :data-filmid="movie.film_id">{{ movie.title }}</div>
                                        <div class="text-center seeDetails"><button class="btn btn-sm btn-cinema-details resize-see-details translateme" :data-filmid="movie.film_id" @click="showModal(movie)" data-en="See Details">See Details</button></div>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                        <button class="carousel-control-prev control-prev-cps" type="button" data-target="#myMovies" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next control-next-cps" type="button" data-target="#myMovies" data-slide="next" v-show="curent_schedule_list_count>=1">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                    <img v-show="no_record_found_movies" src="images/stay-tuned-movie.png" class="no-movies-found">
                </div>
            </div>
        </div>
        <div class="tabs-container">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme" data-en="Select to view">Select to view</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" @click="tabCinema">
                    <div>
                        <a class="translateme cinema-locator" data-en="Cinema">Cinema</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item" @click="tabSchedule">
                    <div>
                        <a class="translateme cinema-schedule" data-en="Schedule">Schedule</a>
                    </div>
                </div>
            </div>
        </div>
        <img  class="back-button" :src="back_button" @click="goBack">
        <div class="back-overlay translateme" data-en="Back" @click="goBack">Back</div>

        <div class="custom-modal" id="schedule-modal">
		    <div class="custom-modal-body">
                <div class="text-right text-white">
                    <span style="font-size:1.5em;margin-right:-10px" class="btn-close-cinema" @click="closeModal">X</span>
                </div>
                <div class="modal-content" style="border-radius:20px;">
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-9 text-left">
                                <span class="cinema-title">{{ movie_details.title }}</span>                          
                                <span style="font-size: 2em;position:relative">
                                    <span class="movie-rating">{{ movie_details.rating }}</span>
                                </span>                              
                                <div class="mt-2 text-justify">
                                    {{ movie_details.synopsis }}
                                </div>
                                <br><span><strong>Genre:</strong></span>&nbsp;&nbsp; {{ movie_details.genre_name }}
                                <br><span><strong>Casting:</strong></span>&nbsp;&nbsp; {{ movie_details.casting }}         
                            </div>
                            <div class="col-3 text-center">
                                <img class="mr-2 schedule-image" v-bind:src="'/uploads/media/cinema/'+movie_details.film_id+'.jpg'" :data-filmid="movie_details.film_id">
                                <div class="mt-2">
                                    <button class="btn btn-prestige-color video-btn" style="width: 76%!important;border-radius:10px" @click="showTrailer(movie_details.trailer_url)">Watch Trailer</button>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-prestige" disabled="" style="width: 76%!important;border-radius:10px;color: #b6c3ff;border: 1px solid #b6c3ff;opacity: 30%;">Buy Tickets</button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <span style="font-size:1.2em"><span>Cinema Schedule</span>:</span>
                            <div class="row">
                                <div v-for="(schedules, index) in movie_details.cinema_schedules" class="col-12 col-sm-3 text-center">
                                    <span>{{ index }}</span>
                                    <div v-for="schedule in schedules">
                                        {{ schedule }}
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
		    </div>
	    </div>

        <div class="custom-modal" id="myTrailerModal">
            <div style="position: relative;top: 40%;transform: translateY(-50%);width: 1182px; left: 32%; color:#000000">
                <div class="youtube-modal-blocker"></div>
                <div class="text-right text-white">
                    <span style="font-size:1.5em;margin-right:-10px" class="btn-close-trailer">X</span>
                </div>
                <div class="modal-content" style="border-radius:20px;">
                    <div class="modal-body p-5">
                        <div id="videocontainer" class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" width="420" height="315" src="" id="video" allowscriptaccess="always" allow="autoplay;"></iframe>
                        </div>
                        <div id="novideo" style="display:none;text-align: center;">
                            <img src="images/no-internet.png" style="width:508px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script> 
	export default {
        name: "Cinema",
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
                helper: new Helpers(),
                curent_cinema_list_count: 0,
                curent_schedule_list_count: 0,
                content_language: '',
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
                if (this.cinema_locator == false) {
                    if (this.curent_schedule_list_count>0) {
                        $('.h-button').removeClass('active');
                        $('.home-button').addClass('active');
                        this.$root.$emit('MainCategories');
                    } else {
                        $(".cinema-locator").trigger('click');
                        this.tabCinema();
                    }        
                } else {
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                    this.$root.$emit('MainCategories');
                } 
                
            },

            getCinemaList: function() {
                axios.get('/api/v1/cinemas')
                .then(response => {
                    this.cinema_list = response.data.data;
                    this.curent_cinema_list_count = this.cinema_list.length -1
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
                    this.curent_schedule_list_count = this.schedule_list.length -1
                    if(this.schedule_list.length == 0) {
                        this.no_record_found_movies = true;
                    }else {
                        this.no_record_found_movies = false;
                    }
                });
            },

            showModal: function(data) {
                this.movie_details = data;
                $('#schedule-modal').show();
            },

            closeModal: function() {
                this.movie_details = '';
                $('#schedule-modal').hide();
            },

            tabCinema: function() {
                this.tab_title='Cinema Locator'; 
                this.cinema_locator = true; 
                this.schedules = false;
                this.$root.$emit('callAssistant','cinemalist',this.content_language);
            },

            tabSchedule: function() {
                this.tab_title='Now Showing';
                this.cinema_locator = false;
                this.schedules = true;
                this.$root.$emit('callAssistant','cinemaschedule',this.content_language);
            },

            showTrailer: function(trailer_url) {
                var videoSrc = trailer_url;

                // replace with embed link
                videoSrc = videoSrc.replace("watch?v=", "embed/");

                //check internet connection
                var ifConnected = window.navigator.onLine;
                if (ifConnected) {
                    // has internet connection
                    $("#novideo").hide();
                    $("#videocontainer").show();
                } else {
                    // no internet connection
                    $("#videocontainer").hide();
                    $("#novideo").show();
                }

                var playlist = videoSrc.substr(30);
                
                // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
                $("#video").attr('src',videoSrc + "?loop=1&playlist=" + playlist + "&autoplay=1"); 
                                    
                $("#schedule-modal").hide();
                $("#myTrailerModal").show();
            },

            resetPage: function(content_language) {
                this.content_language = content_language;
                if (this.curent_schedule_list_count>=0) {
                    $(".cinema-schedule").trigger('click');
                    this.tabSchedule();
                } else {
                    $(".cinema-locator").trigger('click');
                    this.tabCinema();
                }
                $('.first-item').trigger('click');

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);

                // $('.resize-see-details').autoSizr(21);
            },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','cinema')
            },

            findStore: function(value) {
                this.$root.$emit('callFindStore',value,'cinema')
			},

        },

        mounted() {
            $(function() {
                $(".control-prev-cpc,.control-prev-cps").hide();
                $(".control-next-cpc,.control-next-cps").hide();

                $('.store-tabs-item').click(function () {
                    $('.store-tabs-item').removeClass('tab-item-selected');
                    $(this).addClass('tab-item-selected');
                });

                $(".carousel").carousel({
                    interval: false,
                    pause: true,
                    touch:true,
                });

                $(".btn-close-trailer").on('click',function(){
                    $("#myTrailerModal").hide();
                    $("#schedule-modal").show();
                    $("#video").attr('src',"");
                    $("#custom-modal").hide();
                });

                $("#schedule-modal").on('click',function(){
                    $("#schedule-modal").hide();
                });

                $("#myTrailerModal").on('click',function(){
                    $("#myTrailerModal").hide();
                    $("#video").attr('src',"");
                });

                $('#myMovies').on('slid.bs.carousel', function () {
                    // $('.resize-see-details').autoSizr();
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-cps").hide();
                        $(".control-prev-cps").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-cps").hide();
                        $(".control-next-cps").show();
                    }else{
                        $(".control-prev-cps").show();
                        $(".control-next-cps").show();
                    }
                });
            });
        },
    };

</script>
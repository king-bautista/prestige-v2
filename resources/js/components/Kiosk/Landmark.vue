<template>
    <div v-bind:class="(site_orientation == 'Portrait') ? 'router-page-portrait': 'router-page'" style="width: 100%;">
        <div v-if="site_name == 'Parqal'" class="row">
            <div class="col-md-6">
                <div class="datetime-holder text-left m-5">
                    <span class="separator">{{ current_time }}</span><span class="ml-3">{{ current_date }}</span>
                </div>                
            </div>
            <div class="col-md-6 text-right">
                <div class="m-5">
                    <button type="button" class="btn btn-custom">{{ page_title }}</button>
                </div>
            </div>
        </div>
        <div v-else class="row">
            <div class="col-md-6">
                <div id="page-title" class="translateme" :data-en="page_title">{{page_title}}</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder" @click="callHomeMethod">
            </div>
        </div>
        <div v-show="landmark_page">
            <div class="row col-md-10 offset-md-1">
                <div id="landmarkCarousel" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false" v-show="landmark_list.length > 0">
                        
                    <!-- The slideshow -->
                    <div class="carousel-inner carousel-mh-830 custom-p-0-39">
                        <div class="carousel-item" v-for="(landmarks, index) in landmark_list" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_landmark_list_count? 'last-item':'']">
                            <div class="row mb-3">
                                <div v-for="landmark in landmarks" v-bind:class="(site_orientation == 'Portrait') ? 'col-sm-6 mt-3 mb-3': 'col-sm-4 mt-45 mb-3'">
                                    <div v-bind:class="(site_orientation == 'Portrait') ? 'landmark-container-portrait': 'landmark-container'" class="text-center" @click="showLandmark(landmark)">
                                        <img class="landmark-tiles" :src="landmark.image_thumbnail_url" :alt="landmark.name" />
                                        <div class="landmark-name">
                                            {{landmark.landmark}}
                                        </div>
                                        <div class="landmark-info">
                                            Info
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev control-prev-pp p-l-z-p ct-110" href="#landmarkCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next control-next-pp n-l-z-p ct-110" href="#landmarkCarousel" data-slide="next" v-show="current_landmark_list_count >= 1">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>
                <img v-show="no_record_found" src="images/no-results-2.png" style="margin: -14.4rem auto auto;">
            </div>
        </div>
        <div v-show="show_landmark">
            <div class="row">
                <div v-bind:class="(site_orientation == 'Portrait') ? 'col-sm-4 offset-sm-1 mt-100': 'col-sm-3 offset-sm-1'">
                    <img :src="landmark_details.image_url_path" class="lanmark-img">
                </div>
                <div v-bind:class="(site_orientation == 'Portrait') ? 'col-sm-6 mt-50': 'col-sm-7'" >
                    <div class="lanmark-detail-holder m-5 mt-100">
                        <h1>{{ landmark_details.landmark }}</h1>
                        <p>{{ landmark_details.descriptions }}</p>
                        <p>{{ landmark_details.name }}</p>
                        <p>{{ landmark_details.title }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>
    </div>
</template>
<script> 
	export default {
        name: "Landmark",
        data() {
            return {
                landmark_list: [],
                site_name: '',
                site_logo: '',
                site_orientation: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'landmarks',
                no_record_found: false,
                landmark_image: '',
                helper: new Helpers(),
                current_landmark_list_count: 0,
                show_landmark: false,
                landmark_details: [],
                landmark_page: true,
                current_date: '',
                current_time: '',
            };
        },

        created() {
            this.getSite();
            this.getLandmarks();
            setInterval(this.getDateNow, 1000);
        },

        methods: {
            getDateNow: function() {
                const today = new Date();
                const date = today.toLocaleString([], { day:"numeric", month:"long", year:"numeric"});
                const time = today.toLocaleString([], {hour: '2-digit', minute:'2-digit'});
                this.current_date = date;
                this.current_time = time;
            },

            resetPage: function() {
                this.page_title = 'landmarks';
                this.show_landmark = false;
                this.landmark_page = true;

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);
            },

            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_name = response.data.data.name
                    this.site_logo = response.data.data.site_logo
                    this.site_orientation = response.data.data.site_orientation
                });
			},

			getLandmarks: function() {
				axios.get('/api/v1/landmark')
                .then(response => {
                    this.landmark_list = response.data.data
                    this.current_landmark_list_count = this.landmark_list.length -1
                    if(!this.landmark_list.length > 0) {
                        this.no_record_found = true;         
                    }
                });
			},

            showLandmark: function(landmark) {
                this.landmark_details = landmark;
                this.show_landmark = true;
                this.landmark_page = false;
            },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','landmark')
            },

            goBack: function() {
                if (this.show_landmark == true) {
                    this.show_landmark = false;
                    this.landmark_page = true;

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }else {
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                    this.$root.$emit('MainCategories');
                }
            },

        },

        mounted() {
            $(function() {
                $(".btn-close-modal,#modal-schedule-landmark").on('click',function(){
                    $("#mylandmark,#modal-schedule-landmark").hide();
                });

                $("#mylandmark,#modal-schedule,#modal-schedule-landmark").on('click',function(){
                    $("#mylandmark,#modal-schedule,#modal-schedule-landmark").hide();
                });

                $(".control-prev-pp").hide();
                $(".control-next-pp").hide();

                $('#landmarkCarousel').on('slid.bs.carousel', function () {
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-pp").hide();
                        $(".control-prev-pp").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-pp").hide();
                        $(".control-next-pp").show();
                    }else{
                        $(".control-prev-pp").show();
                        $(".control-next-pp").show();
                    }
                });
            })
        },

    };

</script>
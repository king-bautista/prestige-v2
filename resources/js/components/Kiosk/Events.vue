<template>
    <div v-bind:class="(site_orientation == 'Portrait') ? 'router-page-portrait': 'router-page'" style="width: 100%;">
        <div v-if="site_name == 'Parqal'" class="row">
            <div class="col-md-6">
                <div class="datetime-holder text-left ml-5 mt-2 mb-5 pt-3">
                    <span class="separator">{{ current_time }}</span><span class="ml-3">{{ current_date }}</span>
                </div>                
            </div>
            <div class="col-md-6 text-right">
                <div class="mr-5 mt-2 mb-5">
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
        <div v-show="event_page">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'col-md-12': 'col-md-10 offset-md-1'" class="row" v-if="event_list.length > 0">
                <div id="eventsListCarousel" v-bind:class="(site_orientation == 'Portrait') ? 'event-carousel-portrait': ''" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false" v-if="event_list[0].length > 3">
                        
                    <!-- Control dots -->
                    <ul class="carousel-indicators carousel-indicators-event z-1">
                        <li data-target="#eventsListCarousel" v-for="(events, index) in event_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner carousel-mh-690 custom-p-0-39">
                        <div class="carousel-item" v-for="(events, index) in event_list" v-bind:class = "[(index == 0 ? 'first-item active':''), (index == current_event_list_count? 'last-item':'')]">
                            <div class="row mb-3">
                                <div v-for="event in events" v-bind:class="(events.length == 3) ? 'col-sm-4 mt-10 mb-6': 'col-sm-3 mt-10 mb-6'" class="">
                                    <div v-bind:class="(site_orientation == 'Portrait') ? 'text-center event-container-portrait mb-3': 'text-center event-container mb-3'" @click="helper.saveLogs(event, 'Events'); showEvent(event.image_url_path)">
                                        <img :src="event.image_url_path" :alt="event.name" />
                                        <div class="event-name-holder">
                                            {{event.event_name}} <br/>
                                            {{event.event_date}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-- Left and right controls -->
                    <a v-bind:class="(site_orientation == 'Portrait') ? 'carousel-control-prev-event-portrait': ''" class="carousel-control-prev control-prev-event p-l-z-p ct-110" href="#eventsListCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a v-bind:class="(site_orientation == 'Portrait') ? 'carousel-control-next-event-portrait': ''" class="carousel-control-next control-next-event n-l-z-p ct-110" href="#eventsListCarousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>
                
                <div v-if="event_list[0].length <= 3" class="row event-item-holder">
                    <template v-for="(events, index) in event_list">
                        <div class="m-auto" v-for="event in events">
                            <div class="event-container-custom" @click="helper.saveLogs(event, 'Events'); showEvent(event.image_url_path)">
                                <img v-bind:class="(event_list[0].length <= 3) ? 'event-custom-img': ''" class="" :src="event.image_url_path" :alt="event.name" />
                                <div class="event-name-holder">
                                    {{event.event_name}} <br/>
                                    {{event.location}}
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <div v-show="no_record_found" class="row mb-23 mt-5">
                <div class="col-md-12 home-title text-center">
                    <div><span class="translateme" data-en="Stay tuned for more!">Stay tuned for more!</span>
                    </div>            
                </div>
            </div>
            <img v-show="no_record_found" src="images/no-results-2.png" v-bind:class="(site_orientation == 'Portrait') ? 'no-record-found-portrait': ''" class="no-record-found">
        </div>

        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>
        
        <!-- MODAL -->
        <div class="custom-modal p-l-490" id="myevent">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'custom-modal-position-portrait set-width': 'custom-modal-position set-width'" class="">                    
                <img class="my-product-image" :src="event_image">
                <div class="text-center parqal-color">
                    <span class="btn-close-modal"><i class="far fa-times-circle"></i></span>
                </div>
            </div>
        </div>

    </div>
</template>
<script> 
	export default {
        name: "Events",
        data() {
            return {
                event_list: [],
                site_name: '',
                site_logo: '',
                site_orientation: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'events',
                no_record_found: false,
                event_image: '',
                helper: new Helpers(),
                current_event_list_count: 0,
                show_tenant: false,
                tenant_details: [],
                event_page: true,
                current_date: '',
                current_time: '',
            };
        },

        created() {
            this.getSite();
            this.getEvents();
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
                this.page_title = 'events';
                this.show_tenant = false;
                this.event_page = true;

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);
                $('.first-item').trigger('click');
            },

            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_name = response.data.data.name
                    this.site_logo = response.data.data.site_logo
                    this.site_orientation = response.data.data.site_orientation
                });
			},

			getEvents: function() {
				axios.get('/api/v1/events')
                .then(response => {
                    this.event_list = response.data.data
                    this.current_event_list_count = this.event_list.length -1
                    if(!this.event_list.length) {
                        this.no_record_found = true;         
                    }

                    var obj = this;

                    $(function() {
                        $('.control-prev-event').hide();
                        $('.control-next-event').hide();
                        if(obj.current_event_list_count >= 1)
                            $('.control-next-event').show();

                        $('#eventsListCarousel').on('slid.bs.carousel', function () {
                            if($(this).find('.active').hasClass('last-item')){
                                $(".control-next-event").hide();
                                $(".control-prev-event").show();
                            }else if($(this).find('.active').hasClass('first-item')){
                                $(".control-prev-event").hide();
                                $(".control-next-event").show();
                            }else{
                                $(".control-prev-event").show();
                                $(".control-next-event").show();
                            }
                        });
                    })

                    
                });
                
                
			},

            showEvent: function(event) {
                this.event_image = event;
                $("#myevent").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                $('.set-width').addClass('product-size');
           },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','event')
            },

            goBack: function() {
                $('.h-button').removeClass('active');
                $('.home-button').addClass('active');
                this.$router.push("/").catch(()=>{});
                this.$root.$emit('MainCategories');
            },

        },

        mounted() {
            $(function() {
                $(".btn-close-modal,#modal-schedule-event").on('click',function(){
                    $("#myevent, #modal-schedule-event").hide();
                });

                $("#myevent,#modal-schedule,#modal-schedule-event").on('click',function(){
                    $("#myevent,#modal-schedule,#modal-schedule-event").hide();
                });

            })
        },

    };

</script>
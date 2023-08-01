<template>
    <div class="router-page" style="width: 100%;">
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
        <div v-show="event_page">
            <div class="row col-md-10 offset-md-1" v-if="event_list.length > 0">
                <div id="eventCarousel" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false" v-if="event_list[0].length > 3">
                        
                    <!-- Control dots -->
                    <ul class="carousel-indicators z-1">
                        <li data-target="#eventCarousel" v-for="(events, index) in event_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner carousel-mh-690 custom-p-0-39">
                        <div class="carousel-item" v-for="(events, index) in event_list" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_event_list_count? 'last-item':'']">
                            <div class="row mb-3">
                                <div v-for="event in events" class="col-sm-3 mt-10 mb-6">
                                    <div class="text-center event-container mb-3" @click="helper.saveLogs(event, 'Events'); showEvent(event.image_url_path)">
                                        <img :src="event.image_url_path" :alt="event.name" />
                                        <div class="event-name-holder">
                                            {{event.event_name}} <br/>
                                            {{event.location}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev control-prev-pp p-l-z-p ct-110" href="#eventCarousel" data-slide="prev" v-show="current_event_list_count >= 1">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next control-next-pp n-l-z-p ct-110" href="#eventCarousel" data-slide="next" v-show="current_event_list_count >= 1">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>
                
                <div v-if="event_list[0].length <= 3" class="row event-item-holder">
                    <template v-for="(events, index) in event_list">
                        <div class="m-auto" v-for="event in events">
                            <div class="event-container-custom" @click="helper.saveLogs(event, 'Events'); showEvent(event.image_url_path)">
                                <img :src="event.image_url_path" :alt="event.name" />
                                <div class="event-name-holder">
                                    {{event.event_name}} <br/>
                                    {{event.location}}
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: 0.6rem auto auto;">
            </div>
        </div>
        
        <!-- MODAL -->
        <div class="custom-modal p-l-490" id="myevent">
            <div class="custom-modal-position set-width">                    
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
                });
			},

			getEvents: function() {
				axios.get('/api/v1/events')
                .then(response => {
                    this.event_list = response.data.data
                    this.current_event_list_count = this.event_list.length -1
                    if(!this.event_list.length > 0) {
                        this.no_record_found = true;         
                    }
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

        },

        mounted() {
            $(function() {
                $(".btn-close-modal,#modal-schedule-event").on('click',function(){
                    $("#myevent, #modal-schedule-event").hide();
                });

                $("#myevent,#modal-schedule,#modal-schedule-event").on('click',function(){
                    $("#myevent,#modal-schedule,#modal-schedule-event").hide();
                });

                $(".control-prev-pp").hide();
                $(".control-next-pp").hide();

                $('#eventCarousel').on('slid.bs.carousel', function () {
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
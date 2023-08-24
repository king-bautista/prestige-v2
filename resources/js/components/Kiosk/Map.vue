<template>
    <div v-bind:class="(site_orientation == 'Portrait') ? 'router-page-portrait': 'router-page'" style="width: 100%;">
        <div v-if="site_name == 'Parqal'" class="row">
            <div class="col-md-6">
                <div class="datetime-holder mt-2 mb-5 mr-5 ml-5 pt-3">
                    <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                </div>                
            </div>
            <div class="col-md-6 text-right">
                <div class="mr-5 mb-5 mt-3">
                    <div v-bind:class="[(site_orientation == 'Portrait' ? 'btn-custom btn-custom-portrait ': 'btn btn-custom'), page_title.length > 20 ? 'f-size-28' : '']">{{ page_title }}</div>
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
        <div class="row">
            <div class="col-md-12 mb-3 pl-0">
                <template v-if="tenant_details">
                    <template v-if="site_orientation == 'Portrait'">
                        <div class="row tenant-details-portrait map-tenant-portrait">
                            <div class="col-sm-3 text-center">
                                <div class="my-auto pt-3">
                                    <img class="map-tenant-details-logo" :src="tenant_details.brand_logo">
                                    <div class="tenant-details-views-portrait"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;<span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                                </div>
                            </div>
                            <div class="col-sm-6 text-center p-3">
                                <div class="map-tenant-details-name">{{ tenant_details.brand_name }}</div>
                                <div v-if="tenant_details.tenant_details" class="tenant-details-floor mt-2">{{ tenant_details.tenant_details.address }}</div>
                                <div v-else class="map-tenant-details-floor mt-2">{{ tenant_details.floor_name }}, {{ tenant_details.building_name }}</div>
                                <div v-bind:class="(site_orientation == 'Portrait') ? '': 'mt-4'">
                                    <span class="btn-schedule" v-if="tenant_details.operational_hours" @click="showSchedule">
                                        <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                        <span v-else class="text-danger"><strong>Closed</strong></span>
                                        | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3 text-center">
                                <div class="text-left ml-4 social-holder-portrait" v-if="tenant_details.tenant_details">
                                    <div v-if="tenant_details.tenant_details.facebook && tenant_details.tenant_details.facebook != 'null'" class="mb-4 mt-2"><img src="assets/images/parqal-facebook.png" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                    <div v-if="tenant_details.tenant_details.twitter && tenant_details.tenant_details.twitter != 'null'" class="mb-4 mt-2"><img src="assets/images/parqal-twitter.png" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                    <div v-if="tenant_details.tenant_details.instagram && tenant_details.tenant_details.instagram != 'null'" class="mb-4 mt-2"><img src="assets/images/parqal-instagram.png" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="p-2 tenant-details map-tenant-landscape">
                            <div class="map-tenant-landscape-info">
                                <div class="my-auto p-1 text-center">
                                    <img class="tenant-details-logo" :src="tenant_details.brand_logo">
                                    <div class="tenant-details-name">{{ tenant_details.brand_name }}</div>
                                    <div v-if="tenant_details.tenant_details" class="tenant-details-floor mt-2">{{ tenant_details.tenant_details.address }}</div>
                                    <div v-else class="tenant-details-floor mt-2">{{ tenant_details.floor_name }}, {{ tenant_details.building_name }}</div>
                                    <div>
                                        <span class="btn-schedule" v-if="tenant_details.operational_hours" @click="showSchedule">
                                            <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                            <span v-else class="text-danger"><strong>Closed</strong></span>
                                            | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                        </span>
                                    </div>
                                    <div class="tenant-details-views"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;<span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span class="text-danger ml-2 btn-like" @click="updateLikeCount(tenant_details.id,tenant_details.like_count)">
                                            <i class="far fa-heart btn-heart"></i>
                                            <a class="btn-like-display">{{ tenant_details.like_count }}
                                                <span class="translateme" data-en="Likes">Likes</span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-1 mb-4 h-130">
                                    <div class="text-left ml-4 social-holder" v-if="tenant_details.tenant_details">
                                        <div v-if="tenant_details.tenant_details.facebook && tenant_details.tenant_details.facebook != 'null'" class="mb-2 w-500"><img src="assets/images/parqal-facebook.png" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                        <div v-if="tenant_details.tenant_details.twitter && tenant_details.tenant_details.twitter != 'null'" class="mb-2 w-500" ><img src="assets/images/parqal-twitter.png" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                        <div v-if="tenant_details.tenant_details.instagram  && tenant_details.tenant_details.instagram != 'null'" class="mb-2 w-500"><img src="assets/images/parqal-instagram.png" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
                                    </div>
                                </div>
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions" @click="find_store(tenant_details,current_page);">Get Directions</button>

                            </div>
                        </div>
                    </template>
                </template>

                <div id="tenant-details" v-bind:class="(site_orientation == 'Portrait') ? 'card mb-3 label-3 tenant-details-portrait-text-info': 'card mb-3 label-3'">
                    <div class="card-body text-info text-center p-0">
                        <div class="guide-title"><div style="margin-top:27px;margin-right: 5px;font-weight: 600;display: inline-block;" class="translateme" data-en="Directions to:">Directions to:</div><span id="mapguide-destination" class="tenant-name" style="display: inline-block;"></span></div>
                        <div class="guide-steps">
                            <img src="images/parqal-walk.png" style="width:20px;" v-if="site_name == 'Parqal'">
                            <img src="images/man-walk.svg" style="width:20px;" v-else>
                            <span style="font-weight: bold;">
                                <span class="map-minutes">0 minutes</span>,
                                <span class="map-steps">0 steps,</span>,
                            </span>
                            <span class="map-distance my-auto" style="color:#8c8d8d;font-size: .7em;">0m distance</span>			
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="assist p-0">

                        </ul>              
                    </div>

                    <div>
                        <div class="helpful-holder">
                            <span class="helpful-label translateme" data-en="Was this helpful?">Was this helpful?</span>
                            <a href="#" class="response-btn btn-helpful" @click="updateFeedback()">
                                <span class="far fa-thumbs-up"></span>
                            </a> 
                            <a href="#" class="response-btn btn-nothelpful">
                                <span class="far fa-thumbs-down"></span>
                            </a> 
                            <span class="translateme" v-show="feedback_response" data-en="Thank you!">Thank you!</span>
                        </div>             
                    </div>
                </div>

                <div v-bind:class="(site_orientation == 'Portrait') ? 'map-holder-portrait': 'map-holder'">
                    <div class="zoomable-container" id="zoomable-container"></div>
                    <img src="images/parqal-pinch.gif" class="pinch"/>
                </div>

                <!-- you are here-->
                <div class="marker-you-are-here">
                    <img :src="'images/darker-you-are-here-01.png?'+current_time" id="marker-you-are-here">
                </div>

                <!-- escalator up-->
                <div class="marker-escalator-up">
                    <img :src="'images/escalator-up-sprite.png?'+current_time" id="marker-escalator-up">
                </div>

                <!-- escalator down-->
                <div class="marker-escalator-down">
                    <img :src="'images/escalator-down-sprite.png?'+current_time"  id="marker-escalator-down">
                </div>

                <!-- door-->
                <div class="marker-escalator-up">
                    <img :src="'images/door-sprite.png?'+current_time" id="marker-door">
                </div>

                <!-- store here-->
                <div class="marker-store-here hidden">
                    <img :src="'images/store-here-sprite.png?'+current_time" id="marker-store-here">
                </div>
            </div>            
        </div>
        <!-- TABS -->
        <div v-bind:class="(site_orientation == 'Portrait') ? 'tabs-container tabs-container-portrait cb-0 z-1': 'tabs-container cb-0 z-1'">
            <div class="row">
                <div v-bind:class="(site_orientation == 'Portrait') ? 'col-sm-6 offset-md-1': 'col-sm-5 offset-md-2'">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'input-group map-input-group map-input-group-portrait': 'input-group map-input-group'">
                        <multiselect v-model="map_form.tenant"
                            class="custom-select map-tenant-option" 
                            ref="multiselectTenant"
                            :max-height="180"
                            :options="tenant_list"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            @select="find_store"
                            id="search-input"
                            name="tenant-search"
                            placeholder="Input Destination"
                            label="name"
                            track-by="name">
                            <span slot="noOptions">
                                Loading.. Please wait
                            </span>
                            <span slot="noResult">
                                List is empty
                            </span>
                        </multiselect>
                        <div class="input-group-append">
                            <button id="withDisabilityButton" @click="(with_disability) ? with_disability = 0 : with_disability = 1" v-bind:class="(with_disability) ? 'disability-active': ''" class="btn btn-outline-secondary is-pwd-button custom-color last-border-radius" type="button">
                                <i class="fa fa-wheelchair fa-2x" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="label-3 directions-to translateme" data-en="Directions to:">Directions to:</span>
                        <span class="destination"></span>
                    </div>
                </div>
                <div v-bind:class="(site_orientation == 'Portrait') ? 'col-sm-4': 'custom-col-sm-3'">
                    <div class="input-group map-input-group">
                        <multiselect v-model="map_form.floor_id"
                            class="custom-select map-floor-option" 
                            ref="multiselectFloor"
                            :max-height="180"
                            :options="site_floors"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            @select="toggleSelectedMap"
                            id="floor-input"
                            name="floor-search"
                            placeholder="Select Floor"
                            label="building_floor_name"
                            track-by="building_floor_name">
                            <span slot="noOptions">
                                Loading.. Please wait
                            </span>
                            <span slot="noResult">
                                List is empty
                            </span>
                        </multiselect>
                        <div class="input-group-append">
                            <button id="zoomOutButton" class="btn btn-outline-secondary map-bc custom-color map-control-zoomout" type="button">
                                <i class="fa fa-minus fa-2x" aria-hidden="true"></i>
                            </button>
                            <button id="zoomInButton" class="btn btn-outline-secondary map-bc custom-color map-control-zoomin" type="button">
                                <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                            </button>
                            <button id="zoomResetButton" class="btn btn-outline-secondary map-bc custom-color map-control-fit r-br-10" type="button">
                                <i class="fas fa-expand fa-2x"></i>
                            </button>
                            <button id="repeatButton" class="btn btn-outline-secondary map-bc custom-color map-control-fit r-br-10" type="button">
                                <i class="fas fa-history fa-2x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="guide-button" v-show="guide_button" v-bind:class="(site_orientation == 'Portrait') ? 'guide-button-portrait': ''">
            <div class="toggle-arrow mt-7"><i class="arrow up"></i></div>   
            <div id="toggle-updown-text" class="translateme" data-en="Show Text Guide">Show Text Guide</div>
            <template v-if="tenant_details">
                <template v-if="site_orientation == 'Portrait'">
                    <i class="far fa-heart btn-heart map-btn-heart-portrait" aria-hidden="true"></i>
                </template>
            </template>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490 map-search-modal">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'map-search-modal-position-portrait': 'map-search-modal-position'" class="pt-2">                           
                <div class="softkeys-tenant mt-20" data-target="input[name=tenant-search]"></div>
            </div>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490 feedback-search-modal" v-show="feedback_modal">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'feedback-search-modal-position-portrait': 'feedback-search-modal-position'" class="">                      
                <div class="feedback-section">
                    <div class="mb-2"><span class="label-2">How can we improve?</span></div>
                    <div class="feedback-flex mb-18">
                        <div class="feedback-child">
                            <input type="radio" id="option1" value="Incorrect info" v-model="feedback_picked" />
                            <label class="label-0 ml-10" for="option1">Incorrect info</label>
                        </div>
                        <div class="feedback-child">
                            <input type="radio" id="option2" value="Not what I'm looking for" v-model="feedback_picked" />
                            <label class="label-0 ml-10" for="option2">Not what I'm looking for</label>
                        </div>         
                    </div>
                    <div class="feedback-flex mb-18">
                        <div class="feedback-child">
                            <input type="radio" id="option3" value="Confusing direction" v-model="feedback_picked" />
                            <label class="label-0 ml-10" for="option3">Confusing direction</label>
                        </div> 
                        <div class="feedback-child mb-41">
                            <input type="radio" id="option4" value="Others" v-model="feedback_picked" />
                            <label class="label-0 ml-10" for="option4"></label>
                            <textarea name="feedback" id="feedback-textarea" class="po-a" for="option4" v-bind:class="[disable? 'disabled':'']" v-model="feedback_others"  placeholder="Others"></textarea>
                        </div>                    
                    </div>
                    <div class="c-submit-holder">
                        <button class="c-submit"  v-bind:class="[submit_disable? 'disabled-btn':'']" @click="updateFeedback()">Submit</button>
                        <i class="btn-close-modal c-close-modal far fa-times-circle" style="color:#E0BD69;"></i>
                    </div>
                </div>     
                <div class="softkeys-feedback mt-20" data-target="textarea[name=feedback]" v-bind:class="[disable? 'disabled':'']" v-show="softkeysFeedback"></div>
            </div>
        </div>

        <div class="custom-modal p-l-490" id="map-modal-schedule">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'custom-modal-position-portrait': ''" class="custom-modal-position set-width-schedule">                    
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="label-1">Operating Hours</div>
                        <div class="modal-body-schedule-days">
                            <div class="m-15-0" v-for="day in days">{{day}}</div>
                        </div>
                        <div class="modal-body-schedule-time">
                            <div class="m-15-0" v-for="schedule in tenantSchedule">{{schedule}}</div>
                        </div>   
                    </div>   
                </div>
                <div class="text-center parqal-color">
                    <span class="btn-close-schedule"><i class="far fa-times-circle"></i></span>
                </div>       
            </div>
        </div>

        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>
    </div>
</template>
<script>
    var site_maps = [];
    import Multiselect from 'vue-multiselect';
	
    export default {
        name: "Wayfinding",
        data() {
            return {
                map_form: {
                    tenant: '',
                    is_pwd: false,
                    floor_id: '',
                },
                site_logo: '',
                site_name: '',
                site_orientation: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Map',
                tenant_list: [],
                tenant_details: '',
                site_floors: [],
                wayfindings: '',
                current_time: Date.now(),
                helper: new Helpers(),
                active_map_details: '',
                guide_button: false,
                softkeysTenant: false,
                softkeysFeedback: true,
                feedback_helpful: 'Yes',
                feedback_picked: '',
                feedback_others: '',
                disable: true,
                feedback_modal: false,
                submit_disable: true,
                feedback_response: false,
                called_from: '',
                panzoom: '',
                with_disability: 0,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule :[],
            };
        },

        created() {
            this.getSite();
            this.getTenants();
            this.getFloors();
            setInterval(this.getDateNow, 1000);
        },

        watch: {
            feedback_picked(value) {
                if(value == 'Others') {
                    this.disable = false
                    this.submit_disable = true
                }else{
                    this.disable = true
                    this.feedback_others = ''
                    this.submit_disable = false
                }
            },
            feedback_modal(value) {
                if(value) {
                    this.submit_disable = true
                }else{
                    this.feedback_picked = ''
                    this.feedback_others = ''
                }
            },
            feedback_others(value) {
                if (value.length > 0) {
                    this.submit_disable = false
                } else {
                    this.submit_disable = true
                }
            },
        },

        methods: {
            getDateNow: function() {
                const today = new Date();
                //const date = today.toLocaleString([], { weekday:"long", day:"numeric", month:"long", year:"numeric"});
                const date = today.toLocaleString([], { day:"numeric", month:"long", year:"numeric"});
                const time = today.toLocaleString([], {hour: '2-digit', minute:'2-digit'});
                this.current_date = date;
                this.current_time = time;
            },

            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_name = response.data.data.name
                    this.site_logo = response.data.data.site_logo
                    this.site_orientation = response.data.data.site_orientation
                });
			},

            getTenants: function() {
                this.tenant_list = [];
                axios.get('/api/v1/tenants/all')
                .then(response => {
                    this.tenant_list = response.data.data
                });
            },

            getFloors: function() {
                this.site_floors = [];
                axios.get('/api/v1/site/floors')
                .then(response => {
                    this.site_floors = response.data.data
                    this.map_form.floor_id = this.site_floors.find(option => option.is_default === 1);
                    this.active_map_details = this.site_floors.find(option => option.is_default === 1);
                });
            },

            goBack: function() {
                if (this.called_from == 'home') {
                    this.$root.$emit('callAboutFrom',this.called_from);
                } else if (this.called_from == 'search') {
                    this.$root.$emit('callAboutFrom',this.called_from);
                } else if (this.called_from == 'promo') {
                    this.$root.$emit('callAboutFrom',this.called_from);
                } else if (this.called_from == 'cinema') {
                    this.$root.$emit('callAboutFrom',this.called_from);
                } else {
                    this.$root.$emit('MainCategories');
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                }       
            },

            toggleSelectedMap: function(value, id) {
                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearAmenitiesLayer();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.showmap(value);
                });
                // set default map zoom level each map
                this.panzoom.zoom(value.default_scale)
                setTimeout(() => this.panzoom.pan(value.default_x, value.default_y))
			},

            find_store: function(value, called_from) {
                this.tenant_details = '';
                if(called_from == 'home' || called_from == 'search' || called_from == 'bannerAds') {
                    this.map_form.tenant = '';
                    this.tenant_details = value;
                    this.buildSchedule(this.tenant_details);
                }

                this.called_from = called_from;
                this.helper.saveLogs(value, 'Map');
                this.feedback_response = false;

                var obj = this;

                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearAmenitiesLayer();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.drawpoints_stop();
                    this.wayfindings.drawline(value.id, value, obj.with_disability);
                    
                    $('#guide-button').show();
                    $('.map-search-modal').hide();
                    $('.pinch').hide();
                    $('.response-btn').removeClass('disabled-response');
                    $('.response-btn').removeClass('response-active-color');
                    $('.destination').html($('.map-tenant-option .multiselect__single').html());
                    $('.map-tenant-option .multiselect__single').html($('.directions-to').html().concat(" ", $('.destination').html()));
                });
                $('.map-floor-option .multiselect__tags .multiselect__single').html(this.active_map_details.building_floor_name);

                if ($("#app").attr('app-env') == 'local') {
                    $('#guide-button').trigger('click');
                }

                this.panzoom.zoom(this.active_map_details.default_zoom)
                setTimeout(() => this.panzoom.pan(this.active_map_details.default_x, this.active_map_details.default_y))

            },

            updateFeedback: function(id) {
                var obj = this;
                let payload = {
                    helpful: this.feedback_helpful,
                    reason: this.feedback_picked,
                    reason_other: this.feedback_others
                }

                $.post( "/api/v1/feedback", payload ,function(response) {
                    obj.feedback_modal = false
                    obj.feedback_response = true
                });

                $('.response-btn').addClass('disabled-response');
            },

            resetPage: function() {
                this.tenant_details = '';
                var obj = this;
                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearAmenitiesLayer();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.showmap(obj.active_map_details,'start');
                });              
                $('#repeatButton').hide();
                $('#tenant-details').hide();
                $('#zoomResetButton').addClass('last-border-radius');
                $(".map-search-modal").hide();
                this.feedback_modal = false;
                this.feedback_response = false;
                this.called_from = '';
                $('#guide-button .toggle-arrow .arrow').removeClass('down');
                $('#guide-button .toggle-arrow .arrow').addClass('up');
                $('#guide-button').hide();
                $('.response-btn').removeClass('disabled-response');
                $('.response-btn').removeClass('response-active-color');
                $(".pinch").show();
                $('.map-tenant-option .multiselect__single').html('Input Destination');
                $('.map-floor-option .multiselect__tags .multiselect__single').html(this.active_map_details.building_floor_name);

                obj.$refs.multiselectFloor.value = this.active_map_details;

                // Reset Start Scale
                obj.panzoom.zoom(obj.active_map_details.start_scale)
                setTimeout(() => obj.panzoom.pan(obj.active_map_details.start_x, obj.active_map_details.start_y))
            },

            softkeys: function() {
                $(function() {
                    $('.softkeys-tenant').softkeys({
                        target : $('.softkeys-tenant').data('target'),
                        layout : [
                        [
                                '1','2','3','4','5','6','7','8','9','0',
                            ],
                            [
                                ['Q','~'],
                                ['W','!'],
                                ['E','@'],
                                ['R','#'],
                                ['T','$'],
                                ['Y','%'],
                                ['U','^'],
                                ['I','&'],
                                ['O','*'],
                                ['P','('],
                                ['-',')'],
                            ],
                            [
                                ['A','['],
                                ['S',']'],
                                ['D','-'],
                                ['F','+'],
                                ['G','='],
                                ['H',':'],
                                ['J',';'],
                                ['K','&bsol;'],
                                ['L','&#34;'],
                                ['\''],
                            ],
                            [
                                'shift',
                                ['Z','{'],
                                ['X','}'],
                                ['C','<'],
                                ['V','>'],
                                ['B','?'],
                                ['N','_'],
                                ['M','/'],
                                'delete',
                            ],
                            [
                                [','],
                                'space',
                                ['.'],
                                ['Enter','Enter'],
                            ]
                        ]
                    });
                    $('.softkeys-feedback').softkeys({
                        target : $('.softkeys-feedback').data('target'),
                        layout : [
                        [
                                '1','2','3','4','5','6','7','8','9','0',
                            ],
                            [
                                ['Q','~'],
                                ['W','!'],
                                ['E','@'],
                                ['R','#'],
                                ['T','$'],
                                ['Y','%'],
                                ['U','^'],
                                ['I','&'],
                                ['O','*'],
                                ['P','('],
                                ['-',')'],
                            ],
                            [
                                ['A','['],
                                ['S',']'],
                                ['D','-'],
                                ['F','+'],
                                ['G','='],
                                ['H',':'],
                                ['J',';'],
                                ['K','&bsol;'],
                                ['L','&#34;'],
                                ['\''],
                            ],
                            [
                                'shift',
                                ['Z','{'],
                                ['X','}'],
                                ['C','<'],
                                ['V','>'],
                                ['B','?'],
                                ['N','_'],
                                ['M','/'],
                                'delete',
                            ],
                            [
                                [','],
                                'space',
                                ['.'],
                                ['Enter','Enter'],
                            ]
                        ]
                    });

                    $(".softkeys__btn").each(function(){
                        if($(this).attr('data-type') === "shift"){                  
                            $(this).first().addClass('ABC');
                            $(this).first().html("#+=");
                        };
                        if($(this).attr('data-type') === "delete"){
                            $(this).addClass('delete');
                        };
                        if($(this).attr('data-type') === "space"){
                            $(this).addClass('space-key');
                            $(this).first().html("SPACE");
                        };
                        if($(this).attr('data-type') === "delete"){
                            $(this).addClass('delete-key');
                        };
                        if($(this).children().eq(1).html() === "null"){
                            $(this).addClass('hidden-on-alt');
                        }
                        if($(this).attr('data-type') === "symbol" && $(this).children().eq(0).html() === "Enter"){                  
                            $(this).addClass('enter-key');
                        };
                    });


                })
            },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','map')
            },

            updateLikeCount: function(id) {
                this.tenant_details.like_count = parseInt(this.tenant_details.like_count) + 1;

                let params = {
                    id: this.tenant_details.id,
                    like_count: this.tenant_details.like_count
                }

                $(".btn-like-display").addClass('disabled-response');
                $(".btn-heart").removeClass('far').addClass('fas');

                $.post( "/api/v1/like-count", params ,function(response) {
                    
                });
                
                this.$refs.callPromo.updatePromoList(params);
            },

            buildSchedule: function (data) {       
                let tempSchedule = [];
                var currentSchedule = eval(data.tenant_details['schedules']);
                    if (currentSchedule) {
                        Object.keys(this.days).forEach(day => {
                            currentSchedule.forEach(obj => {
                                Object.keys(obj).forEach(key => {
                                    if (key == 'schedules') {
                                        if (obj['schedules'].match(day)) {
                                            var start_time = new Date('7/10/2013 '+obj['start_time']).toLocaleString([], { hour: 'numeric', minute: 'numeric', hour12: true });
                                            var end_time = new Date('7/10/2013 '+obj['end_time']).toLocaleString([], { hour: 'numeric', minute: 'numeric', hour12: true });
                                            tempSchedule.push(start_time + " - " + end_time);
                                            
                                            //tempSchedule.push(obj['start_time'] + " - " + obj['end_time']);
                                        }                               
                                    }
                                });
                            });
                        });
                    }  
                this.tenantSchedule = tempSchedule;              
            },

            showSchedule: function() {
                $("#map-modal-schedule").show();
            },

        },

        mounted() {
            this.softkeys();
            var vm = this;
            $(function() {    
                var obj = this;        
                this.wayfindings = new WayFinding({mapcontainer:'zoomable-container'});
                this.wayfindings.animate_marker_here_stop();
                // $('.map-tenant-option:not(:last-child)').css({'border-top-right-radius': '18px','border-bottom-right-radius': '18px'});

                axios.get('/api/v1/site/maps')
                .then(response => {
                    site_maps = response.data.data;
                    for (var i = 0; i < site_maps.length; i++){
                        this.wayfindings.addMaps(site_maps[i]);
                    }
                }).finally(() => {
                    // Initialize Panzoom JS
                    const elem = document.getElementById('zoomable-container')
                    const parent = elem.parentElement
                    vm.panzoom = Panzoom(elem, {
                        maxScale: 5,
                        canvas: true,
                        startScale: vm.active_map_details.default_scale,
                        startX: vm.active_map_details.default_x,
                        startY: vm.active_map_details.default_y
                    })

                    // Zoom In / Zoom Out Controls
                    $('#zoomInButton').get(0).addEventListener('click', vm.panzoom.zoomIn)
                    $('#zoomOutButton').get(0).addEventListener('click', vm.panzoom.zoomOut)
                    // $('#zoomResetButton').get(0).addEventListener('click', vm.panzoom.reset)

                    // No function bind needed
                    parent.addEventListener('wheel', vm.panzoom.zoomWithWheel)

                    // This demo binds to shift + wheel
                    parent.addEventListener('wheel', function(event) {
                        if (!event.shiftKey) return
                        vm.panzoom.zoomWithWheel(event)
                    })
                });

                $('.pinch, .map-control-fit, .zoomable-container').on('click',function(){
                    var container_width = $('.map-holder').innerWidth();
                    var body_width = 3000;
                    var scale = container_width / body_width; 
                    var left_position = (container_width-$('.zoomable-container').width()) / 2;
                    $(".pinch").hide();
    			});

                $('#zoomResetButton, .pinch').on('click', function() {
                    vm.panzoom.zoom((vm.active_map_details.default_zoom));
                    setTimeout(() => vm.panzoom.pan(vm.active_map_details.default_x, vm.active_map_details.default_y))
                });

                $(".map-tenant-option").on('focusin',function(){
                    // $('.map-tenant-option:not(:last-child)').css({'border-top-left-radius': '0px','border-top-right-radius': '0px'});
                    $('.map-search-modal').show();
                    $(".pinch").hide();
                    $('#tenant-details').hide();
                    $('#guide-button .toggle-arrow .arrow').removeClass('down');
                    $('#guide-button .toggle-arrow .arrow').addClass('up');
                    $('#guide-button').hide();
                    vm.softkeysTenant = true;
                    vm.softkeysFeedback = false;
                });
                $(".map-tenant-option").on('focusout',function(){
                    //$('.map-tenant-option:not(:last-child)').css({'border-top-left-radius': '18px','border-top-right-radius': '18px'});
                });

                $(".map-floor-option").on('focusin',function(){
                    $('.map-floor-option:not(:last-child)').css({'border-top-left-radius': '0px'});
                    $('.map-search-modal').hide();
                    $(".pinch").hide();
                    $('#tenant-details').hide();
                    $('#guide-button .toggle-arrow .arrow').removeClass('down');
                    $('#guide-button .toggle-arrow .arrow').addClass('up');
                    $('#guide-button').hide();
                    $('#repeatButton').hide();
                    $('#zoomResetButton').addClass('last-border-radius');
                });
                $(".map-floor-option").on('focusout',function(){
                    $('.map-floor-option:not(:last-child)').css({'border-top-left-radius': '18px'});
                });

                $('#repeatButton').on('click',function(){
                    $('.map-floor-option .multiselect__tags .multiselect__single').html(vm.active_map_details.building_floor_name);
                    vm.panzoom.zoom((vm.active_map_details.default_zoom));
                    setTimeout(() => vm.panzoom.pan(vm.active_map_details.default_x, vm.active_map_details.default_y))
                    obj.wayfindings.replay(obj.with_disability);
    			});

                $('#guide-button').on('click',function(){
                    if ($('.arrow').hasClass('up')){
                        $('#guide-button .toggle-arrow .arrow').removeClass('up');
                        $('#guide-button .toggle-arrow .arrow').addClass('down');
                        $('#tenant-details').show();
                    }else{
                        $('#guide-button .toggle-arrow .arrow').removeClass('down');
                        $('#guide-button .toggle-arrow .arrow').addClass('up');
                        $('#tenant-details').hide();
                    }            
    			});

                $(".btn-close-modal").on('click',function(){
                    $(".map-search-modal").hide();
                    vm.feedback_modal = false;
                    vm.softkeysTenant = true;
                    vm.softkeysFeedback = false;
                    $(".btn-nothelpful").removeClass('response-active-color');
                });

                // $(".map-search-modal").on('click',function(){
                //     $(".map-search-modal").hide();
                //     vm.feedback_modal = false;
                //     vm.softkeysTenant = true;
                //     vm.softkeysFeedback = false;
                //     $(".btn-nothelpful").removeClass('response-active-color');
                // });

                

                $(".btn-close-schedule").on('click',function(){
                    $("#map-modal-schedule").hide();
                });                

                $(".map-search-modal").on('click',function(){
                    $('.map-tenant-option .multiselect__single').html($('.directions-to').html().concat(" ", $('.destination').html()));
                });

                $(".softkeys__btn").on('mousedown',function(){
                
                }).on('mouseup',function(){
                    if (vm.softkeysTenant) {
                        vm.$refs.multiselectTenant._data.search = $("#search-input").val();
                    }else if (vm.softkeysFeedback) {
                        vm.feedback_others = $("#feedback-textarea").val();
                    }  
                }).on('touchend',function(){
                    if (vm.softkeysTenant) {
                        vm.$refs.multiselectTenant._data.search = $("#search-input").val();
                    }else if (vm.softkeysFeedback) {
                        vm.feedback_others = $("#feedback-textarea").val();
                    }  
                });

                $(".btn-helpful").on('click',function(){
                    vm.feedback_helpful = 'Yes';
                    $(this).addClass('response-active-color');
                    $('.response-btn').addClass('disabled-response');
                });

                $(".btn-nothelpful").on('click',function(){
                    vm.feedback_modal = true;
                    vm.softkeysTenant = false;
                    vm.softkeysFeedback = true;
                    vm.feedback_helpful = 'No';
                    $(this).addClass('response-active-color');
                });
            });
        },

        components: {
			Multiselect
 	    }
    };
    
</script>
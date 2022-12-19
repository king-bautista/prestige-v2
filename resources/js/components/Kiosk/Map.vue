<template>
    <div style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title">{{ page_title }}</div>
            </div>
            <div class="col-md-6 text-right">
                <router-link to="/about-us">
                    <img :src="site_logo" class="logo-holder">
                </router-link>
            </div>
        </div>
        <div class="row col-md-12 mb-3">
            <div class="map-holder">
                <div class="zoomable-container" id="zoomable-container"></div>
                <img src="images/Pinch1.gif" class="pinch"/>
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
        <!-- TABS -->
        <div class="tabs-container">
            <div class="row">
                <div class="col-12 col-sm-4 offset-md-2">
                    <div class="input-group">
                        <multiselect v-model="map_form.tenant"
                            class="custom-select" 
                            :options="tenant_list"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
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
                            <button class="btn btn-outline-secondary is-pwd-button" type="button">
                                <i class="fa fa-wheelchair fa-2x" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="input-group">
                        <multiselect v-model="map_form.floor_id"
                            class="custom-select" 
                            :options="site_floors"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            @select="toggleSelectedMap"
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
                            <button class="btn btn-outline-secondary custom-color map-control-zoomout" type="button">
                                <i class="fa fa-minus fa-2x" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-outline-secondary custom-color map-control-zoomin" type="button">
                                <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-outline-secondary custom-color map-control-fit" type="button">
                                <i class="fas fa-expand fa-2x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img :src="back_button" style="z-index:999;position:absolute;top:780px;right:15px; cursor:pointer;" @click="goBack">
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
                back_button: 'assets/images/English/Back.png',
                page_title: 'Map',
                tenant_list: [],
                site_floors: [],
                wayfindings: '',
                current_time: Date.now()
            };
        },

        created() {
            this.getSite();
            this.getTenants();
            this.getFloors();
            // this.getMaps();
            // this.setMap();
        },

        methods: {
            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
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
                });
            },

            // getMaps: function() {
            //     axios.get('/api/v1/site/maps')
            //     .then(response => {
            //         site_maps = response.data.data;
            //     });
            // },

            // setMap: function() {
            //     $(function() {
            //         this.wayfindings = new WayFinding({mapcontainer:'zoomable-container'});
            //         this.wayfindings.animate_marker_here_stop();
            //         for (var i = 0; i < site_maps.length; i++){
            //             this.wayfindings.addMaps(site_maps[i]);
            //         }
            //     });
            // },

            goBack: function() {
                $('.h-button').removeClass('active');
                $('.home-button').addClass('active');
                this.$router.push("/").catch(()=>{});
            },

            toggleSelectedMap: function(value, id) {
                $(function() {
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.showmap(value);
                });
			},

            find_store: function(id) {
                $(function() {
                    this.wayfindings.drawline(id);
                });
            },
   
        },

        mounted() {
            $(document).ready(function(){
                this.wayfindings = new WayFinding({mapcontainer:'zoomable-container'});
                this.wayfindings.animate_marker_here_stop();

                axios.get('/api/v1/site/maps')
                .then(response => {
                    site_maps = response.data.data;
                    for (var i = 0; i < site_maps.length; i++){
                        this.wayfindings.addMaps(site_maps[i]);
                    }
                }).finally(() => {
                    $('#zoomable-container').ZoomArea({
                        virtualScrollbars:false,
                        externalIncrease:'.map-control-zoomin',
                        externalDecrease:'.map-control-zoomout',
                        parentOverflow:'hidden',
                    });
                });

                $('.pinch, .map-control-fit').on('click',function(){
                    var container_width = $('.map-holder').innerWidth();
                    var body_width = 3000;
                    var scale = container_width / body_width; 
                    var left_position = (container_width-$('.zoomable-container').width()) / 2;
                    $('.zoomable-container').css({'transform':'scale(' + scale + ')', 'left': left_position+'px', 'top': '-1120.5px'});
                    $(".pinch").hide();
    			});

            });
        },

        components: {
			Multiselect
 	    }
    };
    
</script>
<style>
    .multiselect__tags {
        min-height: 40px;
        display: block;
        padding: 8px 40px 0 8px;
        border-radius: 5px;
        border: none;
        background: #fff;
        font-size: 14px;
    }

    .multiselect__select {
        position: absolute;
        width: 0 !important;
        height: 0 !important;
        right: 6px !important;
        top: 40% !important;
        padding: 6px !important;
        transition: transform .2s ease;
        border: solid rgb(152, 218, 208) !important;
        display: inline-block !important;
        border-width: 0 6px 6px 0 !important;
        transform: rotate(-225deg) !important;
        -webkit-transform: rotate(225deg) !important;
    }

    .multiselect--active .multiselect__select {
        transform: rotate(-45deg) !important;
        -webkit-transform: rotate(45deg) !important;
    }

    .multiselect__select:before {
        display: none;
    }

    .multiselect__tags {
        border: none !important;
    }

    .multiselect__placeholder {
        color: #000 !important;
        font-size: 1rem;
    }

    .multiselect--above .multiselect__content-wrapper {
        margin-left: -13px;
    }

    .is-pwd-button {
        color: rgb(152, 218, 208);
        border-color: #000;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .input-group>.custom-select:not(:last-child), .input-group>.form-control:not(:last-child) {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-color: #000;
    }
    
    .custom-select {
        background: none !important;
    }

    .custom-color {
        color: rgb(152, 218, 208);
    }

</style>
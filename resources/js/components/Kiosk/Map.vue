<template>
    <div class="router-page" style="width: 100%;">
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
            
            <div id="tenant-details" class="card mb-3 label-3">
                <div class="card-body text-info text-center">
                    <div style="color:#6051e3;"><div style="margin-top:27px;margin-right: 5px;font-weight: 600;display: inline-block;" class="translateme">Directions to:</div><span id="mapguide-destination" class="tenant-name" style="display: inline-block;"></span></div>
                    <div style="padding-left: 10px;margin-top: 19px;color:#6051e3;">
                        <img src="images/man-walk.svg" style="width:20px;">
                        <span style="font-weight: bold;">
                            <span class="map-minutes">0 minutes</span>,
                            <span class="map-steps">0 steps,</span>,
                        </span>
                        <span class="map-distance my-auto" style="color:#8c8d8d;font-size: .7em;">0m distance</span>			
                    </div>
                </div>
                <div class="card-body">
                    <ul class="assist">

                    </ul>              
                </div>

                <div>
                    <div class="" style="text-align: left;padding-left: 45px;margin-top: 48px;">
                        <span class="translateme">Was this helpful?</span>
                        <a href="#" class="custom-p-0-8 btn-helpful" style="font-size:1rem;color:#6051e3;">
                            <span class="fa fa-thumbs-up"></span>
                        </a> 
                        <a href="#" class="custom-p-0-8 btn-nothelpful" style="font-size:1rem;color:#6051e3;">
                            <span class="fa fa-thumbs-down"></span>
                        </a> 
                        <span class="response-thanks translateme">Thank you!</span>
                    </div>             
                </div>
            </div>

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
        <div class="tabs-container cb-0 z-1">
            <div class="row">
                <div class="col-12 col-sm-4 offset-md-2">
                    <div class="input-group map-input-group">
                        <multiselect v-model="map_form.tenant"
                            class="custom-select map-tenant-option" 
                            ref="multiselectTenant"
                            :max-height="180"
                            :options="tenant_list"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            :reset-after="true"
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
                        <div class="input-group-append" v-show="false">
                            <button class="btn btn-outline-secondary is-pwd-button" type="button">
                                <i class="fa fa-wheelchair fa-2x" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 custom-col-sm-3">
                    <div class="input-group map-input-group">
                        <multiselect v-model="map_form.floor_id"
                            class="custom-select map-floor-option" 
                            ref="multiselectFloor"
                            :max-height="180"
                            :options="site_floors"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            :allow-empty="false"
                            :searchable="false"
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
        <div id="guide-button" v-show="guide_button">
            <div class="toggle-arrow mt-7"><i class="arrow up"></i></div>   
            <div id="toggle-updown-text" class="translateme">Show Text Guide</div>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490 map-search-modal">
            <div class="map-search-modal-position">                    
                <div class="text-right text-white custom-w-1140">
                    <span class="btn-close-modal">X</span>
                </div>        
                <div class="softkeys-tenant mt-20" data-target="input[name=tenant-search]" v-show="softkeysTenant"></div>
                <div class="softkeys-feedback mt-20" data-target="input[name=feedback-search]" v-show="softkeysFeedback"></div>
            </div>
        </div>

        <img class="back-button" :src="back_button" @click="goBack">
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
                current_time: Date.now(),
                helper: new Helpers(),
                active_map_details: '',
                guide_button: false,
                softkeysTenant: false,
                softkeysFeedback: false,
            };
        },

        created() {
            this.getSite();
            this.getTenants();
            this.getFloors();
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
                    this.map_form.floor_id = this.site_floors.find(option => option.is_default === 1);
                    this.active_map_details = this.site_floors.find(option => option.is_default === 1);
                    // this.site_floors.forEach(floors => {
                    //     if (floors.is_default == 1) {
                    //         this.map_form.floor_id = floors
                    //     }
                    // });
                });
            },

            goBack: function() {
                $('.h-button').removeClass('active');
                $('.home-button').addClass('active');
                this.$router.push("/").catch(()=>{});
            },

            toggleSelectedMap: function(value, id) {
                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.showmap(value);
                });
			},

            find_store: function(value, id) {
                this.helper.saveLogs(value, 'Map');
                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.drawpoints_stop();
                    this.wayfindings.drawline(value.id, value);
                    $('#guide-button').show();
                    $('.map-search-modal').hide();
                });
            },

            resetPage: function() {
                var obj = this;
                $(function() {
                    this.wayfindings.stopall();
                    this.wayfindings.clearTextlayer();
                    this.wayfindings.clearEscalator();
                    this.wayfindings.clearLine();
                    this.wayfindings.clearMarker();
                    this.wayfindings.showmap(obj.active_map_details);
                });              
                $('#repeatButton').hide();
                $('#tenant-details').hide();
                $('#zoomResetButton').addClass('last-border-radius');
                $('#zoomResetButton').trigger('click');
                $(".map-search-modal").hide();
                $('#guide-button .toggle-arrow .arrow').removeClass('down');
                $('#guide-button .toggle-arrow .arrow').addClass('up');
                $('#guide-button').hide();
                $(".pinch").show();
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
                                ['Q','~'],['W','!'],['E','@'],['R','#'],['T','$'],['Y','%'],['U','^'],['I','&'],['O','*'],['P','('],['-',')'],
                            ],
                            [
                                ['A','['],['S',']'],['D','-'],['F','+'],['G','='],['H',':'],['J',';'],['K','\''],['L','&#34;'],['\'','null'],
                            ],
                            [
                                'shift',['Z','['],['X',']'],['C','-'],['V','+'],['B','?'],['N',':'],['M',';'],'delete',
                            ],
                            [
                                [',','null'],'space',['.','null'],['Enter','Enter'],
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
                                ['Q','~'],['W','!'],['E','@'],['R','#'],['T','$'],['Y','%'],['U','^'],['I','&'],['O','*'],['P','('],['-',')'],
                            ],
                            [
                                ['A','['],['S',']'],['D','-'],['F','+'],['G','='],['H',':'],['J',';'],['K','\''],['L','&#34;'],['\'','null'],
                            ],
                            [
                                'shift',['Z','['],['X',']'],['C','-'],['V','+'],['B','?'],['N',':'],['M',';'],'delete',
                            ],
                            [
                                [',','null'],'space',['.','null'],['Enter','Enter'],
                            ]
                        ]
                    });
                })
            },
        },

        mounted() {
            this.softkeys();
            var vm = this;
            $(function() {    
                var obj = this;        
                this.wayfindings = new WayFinding({mapcontainer:'zoomable-container'});
                this.wayfindings.animate_marker_here_stop();
                $('.map-tenant-option:not(:last-child)').css({'border-top-right-radius': '18px','border-bottom-right-radius': '18px'});

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
                    const panzoom = Panzoom(elem, {
                        maxScale: 5,
                        canvas: true,
                        startScale: 0.5,
                        startX: -970,
                        startY: -1170
                    })

                    // Zoom In / Zoom Out Controls
                    $('#zoomInButton').get(0).addEventListener('click', panzoom.zoomIn)
                    $('#zoomOutButton').get(0).addEventListener('click', panzoom.zoomOut)
                    $('#zoomResetButton').get(0).addEventListener('click', panzoom.reset)

                    // Display Panzoom Values on Change Event
                    // elem.addEventListener('panzoomchange', (event) => {
                    //     console.log(event.detail) // => { x: 0, y: 0, scale: 1 }
                    // })

                    // No function bind needed
                    parent.addEventListener('wheel', panzoom.zoomWithWheel)

                    // This demo binds to shift + wheel
                    parent.addEventListener('wheel', function(event) {
                        if (!event.shiftKey) return
                        panzoom.zoomWithWheel(event)
                    })
                });

                $('.pinch, .map-control-fit, .zoomable-container').on('click',function(){
                    var container_width = $('.map-holder').innerWidth();
                    var body_width = 3000;
                    var scale = container_width / body_width; 
                    var left_position = (container_width-$('.zoomable-container').width()) / 2;
                    // $('.zoomable-container').css({'transform':'scale(' + scale + ')', 'left': left_position+'px', 'top': '-1120.5px'});
                    $(".pinch").hide();
    			});

                $(".map-tenant-option").on('focusin',function(){
                    $('.map-tenant-option:not(:last-child)').css({'border-top-left-radius': '0px','border-top-right-radius': '0px'});
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
                    $('.map-tenant-option:not(:last-child)').css({'border-top-left-radius': '18px','border-top-right-radius': '18px'});
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
                    obj.wayfindings.replay();
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
                });

                // $(".softkeys__btn").on('mousedown',function(){
                
                // }).on('mouseup',function(){
                //     $('.multiselect__input').trigger('keydown');
                // }).on('touchend',function(){
                //     $('.multiselect__input').trigger('keydown');
                // });

                $(".softkeys__btn").on('touchstart',function(){
                    // console.log(vm.$refs.multiselect._data.search);
                    if (vm.softkeysTenant) {
                        vm.$refs.multiselectTenant._data.search = $("#search-input").val();
                    }else if (vm.softkeysFeedback) {
                        // vm.$refs.multiselectFloor._data.search = $("#floor-input").val();
                    }              
                });
            });
        },

        components: {
			Multiselect
 	    }
    };
    
</script>
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
        <div v-show="promo_page">
            <div class="row col-md-10 offset-md-1">
                <div id="promoCarousel" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false" v-show="promo_list.length > 0">
                        
                    <!-- Control dots -->
                    <ul class="carousel-indicators z-1">
                        <li data-target="#promoCarousel" v-for="(promos, index) in promo_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner carousel-mh-830 custom-p-0-39">
                        <div class="carousel-item" v-for="(promos, index) in promo_list" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_promo_list_count? 'last-item':'']">
                            <div class="row mb-3">
                                <div v-for="promo in promos" class="col-12 col-sm-4 text-left mt-10 mb-6">
                                    <div class="text-center">
                                        <a @click="helper.saveLogs(promo, 'Promos'); showPromo(promo.material_image_path)">
                                            <img class="promo-tiles" :src="promo.material_image_path" :alt="promo.name" />
                                        </a>
                                        <a class="d-block text-left ti-30" @click="showTenant(promo.tenant_details)">
                                            <p class="label-2 cm-10-0">
                                                {{promo.brand_name}}
                                            </p>
                                            <p class="sub-text">
                                                {{promo.tenant_details.floor_name}}<span v-if="promo.tenant_details.building_name">, {{promo.tenant_details.building_name}}</span>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev control-prev-pp p-l-z-p ct-110" href="#promoCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next control-next-pp n-l-z-p ct-110" href="#promoCarousel" data-slide="next" v-show="current_promo_list_count >= 1">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: 0.6rem auto auto;">
            </div>
        </div>
        <div class="back-button" :src="back_button" @click="goBack"></div>
        <div class="back-overlay translateme" data-en="Back" @click="goBack">Back</div>

        <!-- TENANT -->
        <div v-show="show_tenant">
            <div class="row">
                <div class="col-12 col-sm-8 text-center">
                    <div v-if="tenant_details.is_subscriber && tenant_details.products.length != 0">
                        <div class="row ml-1 mt-16" v-if="tenant_details.products.banners.length > 0">
                            <div class="col-12 p-0">
                                <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid tenant_page_banner_img" @click="showProduct(tenant_details.products.banners[0].image_url_path,'banner')">
                            </div>
                        </div>
                        <div class="row subscriber-products mt-15 ml-0" v-bind:class = "(tenant_details.products.banners.length > 0) ? 'with-banner-height':'with-out-banner-height'">
                            <div v-for="product in tenant_details.products.products" class="m-15-18">
                                <img :src="product.image_url_path" class="rounded-corner box-shadowed img-promo" @click="showProduct(product.image_url_path,'product')">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="mt-83 tenant-logo box-shadowed">
                    </div>
                </div>
                <div class="col-12 col-sm-4 p-3">
                    <div class="bg-white p-3 box-shadowed tenant-details">
                        <div class="my-auto p-1">
                            <img :src="tenant_details.brand_logo" class="tenant-details-logo">
                            <div class="tenant-details-name">{{ tenant_details.brand_name }}</div>
                            <div class="tenant-details-floor">{{ tenant_details.floor_name }}, {{ tenant_details.building_name }}</div>
                            <div class="tenant-details-views"><span>{{ tenant_details.view_count }}</span>&nbsp;<span>Views</span></div>
                            <div>
                                <span class="btn-schedule" v-if="tenant_details.operational_hours" @click="showSchedule">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                    <span v-else class="text-danger"><strong>Closed</strong></span>
                                    | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row mt-31 mb-4">
                            <div class="text-left ml-36" v-if="tenant_details.tenant_details">
                                <div class="mb-24"><img src="assets/images/social-media-fb.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                <div class="mb-24"><img src="assets/images/social-media-twitter.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                <div class="mb-24"><img src="assets/images/social-media-ig.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
                            </div>
                        </div>
                        <div v-else class="row mt-3 mb-206">
                            <div class="col-6">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt" aria-hidden="true"></i> Share
                                </a>
                            </div>
                            <div class="col-6">
                                <span class="text-danger ml-2 btn-like" @click="updateLikeCount(tenant_details.id,tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span>Likes</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row p-r-t-94">
                            <div class="col-6 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop"  @click="findStore(tenant_details)">Get Directions</button>
                            </div>
                            <div class="col-6 mt-3">
                                <span class="text-danger ml-2 btn-like"  @click="updateLikeCount(tenant_details.id, tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span>Likes</span>
                                    </a>
                                </span>
                            </div>
                            <div class="col-6 mt-3">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt" aria-hidden="true"></i> Share
                                </a>
                            </div>
                            <div class="col-6 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now">Order Now</button>
                            </div>
                        </div>
                        <div v-else class="row mt-3">
                            <div class="col-12 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop"  @click="findStore(tenant_details)">Get Directions</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-pwd w-100 btn-direction-shop-pwd">Get Directions (PWD-friendly)</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now">Order Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490" id="myPromo">
            <div class="custom-modal-position set-width">                    
                <div class="text-right text-white">
                    <span class="btn-close-modal">X</span>
                </div>
                <img class="my-product-image" :src="promo_image">
            </div>
        </div>

        <div class="custom-modal p-l-490" id="modal-schedule-promo">
            <div class="custom-modal-position set-width-schedule">                    
                <div class="text-right text-white">
                    <span class="btn-close-modal">X</span>
                </div>
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
            </div>
        </div>

    </div>
</template>
<script> 
	export default {
        name: "Promo",
        data() {
            return {
                promo_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Promos',
                no_record_found: false,
                promo_image: '',
                helper: new Helpers(),
                current_promo_list_count: 0,
                show_tenant: false,
                tenant_details: [],
                promo_page: true,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule :[],
            };
        },

        created() {
            this.getSite();
            this.getPromos();
        },

        methods: {
            TitleCasePerWord: function() {

                this.promo_list.forEach(element => {
                    element.forEach(tenant => {
                        const splitBrandName = tenant.brand_name.toLocaleLowerCase().split(" ");

                        for (var i = 0; i < splitBrandName.length; i++) {
                            splitBrandName[i] = splitBrandName[i].charAt(0).toUpperCase() + splitBrandName[i].slice(1);
                        }      

                        if (splitBrandName.join(" ").match(/(\(.*\))/)) {
                            const text = splitBrandName.join(" ");
                            
                            const strToReplace = splitBrandName.join(" ").match(/(\(.*\))/)[0];

                            tenant.brand_name = text.replace(strToReplace, strToReplace.toUpperCase());
                        } else {
                            tenant.brand_name = splitBrandName.join(" ");
                        }

                    });
                });

            },

            updateLikeCount: function(id) {
                this.tenant_details.like_count = parseInt(this.tenant_details.like_count) + 1;

                this.promo_list.forEach(element => {
                    element.forEach(tenant => {
                        if (tenant.id = this.tenant_details.id) {
                            tenant.tenant_details.like_count = this.tenant_details.like_count;
                        }          
                    });
                });

                let params = {
                    id: this.tenant_details.id,
                    like_count: this.tenant_details.like_count
                }

                $(".btn-like-display").addClass('disabled-response');

                $.post( "/api/v1/like-count", params ,function(response) {
                    
                });
            },

            resetPage: function() {
                this.page_title = 'Promos';
                this.show_tenant = false;
                this.promo_page = true;

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);
                $('.first-item').trigger('click');
            },

            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

			getPromos: function() {
				axios.get('/api/v1/promos')
                .then(response => {
                    this.promo_list = response.data.data
                    this.TitleCasePerWord();
                    this.current_promo_list_count = this.promo_list.length -1
                    if(!this.promo_list.length > 0) {
                        this.no_record_found = true;         
                    }
                });
			},

            showPromo: function(promo) {
                this.promo_image = promo;
                $("#myPromo").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                $('.set-width').addClass('product-size');
            },

            buildSchedule: function (data) {
                let tempSchedule = [];
                const currentSchedule = eval(data.tenant_details['schedules']);

                Object.keys(this.days).forEach(day => {
                    currentSchedule.forEach(obj => {
                        Object.keys(obj).forEach(key => {
                            if (key == 'schedules') {
                                if (obj['schedules'].match(day)) {
                                    tempSchedule.push(obj['start_time'] + " - " + obj['end_time']);
                                }                               
                            }
                        });
                    });
                });

                this.tenantSchedule = tempSchedule;
            },

            showSchedule: function() {
                $("#modal-schedule-promo").show();
            },

            showProduct: function(product,type) {
                this.promo_image = product;
                $("#myPromo").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                if (type == 'banner'){
                    $('.set-width').addClass('banner-size');
                }
                if (type == 'product'){
                    $('.set-width').addClass('product-size');
                } 
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                // axios.get('/api/v1/get-like-count/'+tenant.id)
                // .then(response => {
                //     this.tenant_details.like_count = response.data.data
                // });
                this.show_tenant = true;
                this.promo_page = false;
                this.buildSchedule(this.tenant_details);
                this.$root.$emit('callMutateLocation','tenant');

                $(".btn-like-display").removeClass('disabled-response');

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);
            },

            goBack: function() {
                if (this.show_tenant == true) {
                    this.page_title = 'Promos';
                    this.show_tenant = false;
                    this.promo_page = true;

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

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','promo')
            },

            findStore: function(value) {
                this.$root.$emit('callFindStore',value,'promo')
			},

            updatePromoList: function(params) {
                this.promo_list.forEach(array => {
                    array.forEach(object => {
                        if (object.id = params.id) {
                            object.tenant_details.like_count = params.like_count;
                        }          
                    });
                });
			},

        },

        mounted() {
            $(function() {
                $(".btn-close-modal,#modal-schedule-promo").on('click',function(){
                    $("#myPromo,#modal-schedule-promo").hide();
                });

                $("#myPromo,#modal-schedule,#modal-schedule-promo").on('click',function(){
                    $("#myPromo,#modal-schedule,#modal-schedule-promo").hide();
                });

                $(".control-prev-pp").hide();
                $(".control-next-pp").hide();

                $('#promoCarousel').on('slid.bs.carousel', function () {
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
<template>
    <div style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title" v-if="page_title != 'Home'">{{ page_title }}</div>
            </div>
            <div class="col-md-6 text-right">
                <router-link to="/about-us">
                    <img :src="site_logo" class="logo-holder">
                </router-link>
            </div>
        </div>

        <!-- MAIN CATEGORY -->
        <div v-show="home_category">
            <div class="row mt-15 mb-55">
                <div class="col-md-12 home-title text-center">
                    Search your favorite stores
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-for="(category, index) in main_category" :class="[category.class_name, 'hc-button']" @click="helper.saveLogs(category, 'Home'); showChildren(category);">
                        <img :src="category.kiosk_image_primary_path">
                        <div id="hc-button1" class="hc-button-align">{{ category.label }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB CATEGORY -->
        <div v-show="child_category">
            <div v-if="child_category_count< 7" class="row mt-120 mb-41">
                <div class="col-md-12 home-title-sub text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div v-else-if="child_category_count< 11" class="row mb-27 mt-29">
                <div class="col-md-12 home-title-sub text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div v-else class="row mb-27">
                <div class="col-md-12 home-title-sub text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div class="row col-md-6 offset-md-3 mb-3 mw-51p">
                <div v-for="subcategory in current_category.children" class="col-12 col-sm-6 text-left mt-3 p-0-5" @click="helper.saveLogs(subcategory, 'Home'); getTenantsByCategory(subcategory)">			
                    <div class="c-button ml-0">						
                        <img class="tenant-category" :src="subcategory.kiosk_image_primary_path" style="max-width:100%">
                        <div class="c-button-align c-button-color2 translateme"><p>{{subcategory.label}}</p></div>                        
                    </div>					
                </div>
            </div>
        </div>

        <!-- SUPPLEMENTALS -->
        <div v-show="supplementals">
            <div class="row mb-27">
                <div class="col-md-12 home-title-sub text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div class="row col-md-10 offset-md-1 mb-3 w-1152">
                <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" data-wrap="false">
                    <div class="carousel-inner carousel-mh-626">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li class="current" data-target="#myCarousel" v-for="(supplementals, index) in current_supplementals.children" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(supplementals, index) in current_supplementals.children" :id="(index == 0) ? 'first-item':''" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="supplemental in supplementals" class="col-12 col-sm-4 text-left mt-3" @click="helper.saveLogs(supplemental, 'Home'); getTenantsBySupplementals(supplemental)">			
                                    <div class="c-button">						
                                        <img class="tenant-category" :src="supplemental.kiosk_image_primary_path" style="max-width:100%">
                                        <div class="c-button-align c-button-color2 translateme"><p>{{supplemental.label}}</p></div>                        
                                    </div>					
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev carousel-control-prev-pos" type="button" data-target="#myCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next carousel-control-next-pos" type="button" data-target="#myCarousel" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>                
                </div>               
            </div>
        </div>

        <!-- ALPHABETICAL -->
        <div v-show="alphabetical">
            <div :class="(category_top_banner) ? 'row mt-14 mb-18' : 'row mt-14 mb-13' ">
                <div class="col-md-12 home-title-sub text-center">
                    <div v-if="!category_top_banner">{{ category_label }}</div>
                    <div class="hts-strip" v-if="category_top_banner">
                        <img class="tenant-category-strip" :src="category_top_banner" style="width:100%">
                        <div class="hts-strip-align hts-strip-color2 translateme">{{ category_label }}</div>                                        
                    </div>                    
                </div>
            </div>
            <div class="row col-md-10 offset-md-1">
                <div id="myTenants" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="tenant_list.length > 0">
                    <div class="carousel-inner" :class="(category_top_banner) ? 'carousel-mh-596' : 'carousel-mh-626' ">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myTenants" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item tenant-store-carousel" v-for="(tenants, index) in tenant_list" :data-index="index" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="tenant in tenants" class="col-12 col-sm-4 text-left mt-3">
                                    <div class="tenant-store bg-white text-center box-shadowed ml-3" @click="helper.saveLogs(tenant, 'Home'); showTenant(tenant)">
                                        <div class="image-holder h-100">
                                            <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                        </div>
                                        <div class="text-left pta-2 brand-name">
                                            <div class="shop_name" :parent-index="index">{{ tenant.brand_name }}</div>
                                            <div style="font-size: 0.7em;color:#2a2a2a">{{ tenant.building_name }}, {{ tenant.floor_name }} </div>
                                            <div style="font-weight: bold;font-size: 0.7em">
                                                <span class="translateme text-success" v-if="tenant.active==1">Open</span>
                                                <span class="translateme text-success" v-if="tenant.active==0">Close</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <button class="carousel-control-prev carousel-control-prev-pos-alphabet" type="button" data-target="#myTenants" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next carousel-control-next-pos-alphabet" type="button" data-target="#myTenants" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" class="no-record-found">
            </div>
        </div>

        <!-- TENANT -->
        <div v-show="show_tenant">
            <div class="row">
                <div class="col-12 col-sm-8 text-center">
                    <div v-if="tenant_details.is_subscriber && tenant_details.products">
                        <div class="row ml-1" v-if="tenant_details.products.banners.length">
                            <div class="col-12">
                                <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid">
                            </div>
                        </div>
                        <div class="row ml-2 subscriber-products">
                            <div v-for="product in tenant_details.products.products" class="col-3 p-2">
                                <img :src="product.image_url_path" class="rounded-corner box-shadowed img-promo" @click="showProduct(product.image_url_path)">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="tenant-logo box-shadowed">
                    </div>
                </div>
                <div class="col-12 col-sm-4 p-3">
                    <div class="bg-white p-3 box-shadowed tenant-details">
                        <div class="my-auto p-1">
                            <img :src="tenant_details.brand_logo" class="tenant-details-logo">
                            <div class="tenant-details-name">{{ tenant_details.brand_name }}</div>
                            <div class="tenant-details-floor">{{ tenant_details.floor_name }}</div>
                            <div class="tenant-details-views"><span style="color:#000000;">{{ tenant_details.view_count }}</span>&nbsp;<span>Views</span></div>
                            <div>
                                <span class="btn-schedule" v-if="tenant_details.operational_hours">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                    <span v-else class="text-danger"><strong>Closed</strong></span>
                                     | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row mt-4 mb-4">
                            <div class="text-left ml-3" v-if="tenant_details.tenant_details">
							    <div class="mt-4"><img src="assets/images/social-media-fb.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
				                <div class="mt-4"><img src="assets/images/social-media-twitter.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
					            <div class="mt-4"><img src="assets/images/social-media-ig.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
				            </div>
                        </div>
                        <div v-else class="row mt-3" style="margin-bottom: 180px;">
                            <div class="col-6">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt" aria-hidden="true"></i> Share
                                </a>
                            </div>
                            <div class="col-6">
                                <span class="text-danger ml-2 btn-like">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">0 
                                        <span>Likes</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row mt-5">
                            <div class="col-6 mt-3">
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop">Get Directions</button>
                            </div>
                            <div class="col-6 mt-3">
                                <span class="text-danger ml-2 btn-like">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">0 
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
                                <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop">Get Directions</button>
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

        <!-- TABS -->
        <div class="tabs-container" v-show="!home_category">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">View stores by</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" id="category-tab" @click="showCategories">
                    <div>
                        <a class="translateme tenant-category">Category</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item" @click="getTenants(current_category)">
                    <div>
                        <a class="translateme tenant-alphabet">Alphabetical</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item" @click="showSupplementals();">
                    <div>
                        <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental">{{ current_category.supplemental.name }}</a>
                    </div>
                </div>
            </div>
            <div class="p-a">
                <ol class="navigation-letters" v-show="alphabetical">
                    <li v-for="letter in navigation_letters" @click="moveTo(letter)">{{letter}}</li>
                </ol>
            </div>
        </div>
        <img v-show="!home_category" :src="back_button" class="back-button" @click="goBack">

        <!-- MODAL -->
        <div class="custom-modal" id="myProduct">
            <div style="position: relative;top: 40%;transform: translateY(-50%);width: 540px; left: 50%; color:transparent;">
                <div class="text-right text-white">
                    <span style="font-size:1.5em;margin-right:-10px" class="btn-close-trailer">X</span>
                </div>
                <div class="modal-content" style="border-radius:20px;">
                    <div class="modal-body">
                        <img :src="product_image" style="width:508px;">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import { isTemplateElement } from '@babel/types';

	export default {
        name: "MainCategories",
        data() {
            return {
                main_category: [],
                tenant_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Home',
                home_category: true,
                child_category: false,
                child_category_count: 0,
                alphabetical: false,
                supplementals: false,
                current_category: '',
                current_supplementals: '',
                category_label: '',
                category_top_banner: '',
                no_record_found: false,
                tenant_details: '',
                show_tenant: false,
                product_image: '',
                previous_page: '',
                helper: new Helpers(),
                navigation_letters: ['#'],
            };
        },

        created() {
            this.getSite();
            this.getCategories();
            this.generateLetters();
        },

        methods: {
            resetCarousel: function() {
                $('#myTenants.carousel-indicators li').removeClass('active'); 
                $('#myTenants.carousel-item').removeClass('active');      
                $('#first-item').addClass('active');
                $('#myTenants.carousel-indicators li').first().addClass('active');             
            },

            moveTo: function(letter) {      
                let index = 0;
                $(".shop_name").each(function(){
                    if($(this).html().startsWith(letter, 0)){

                        $(this).addClass('letter-selected');
                        setTimeout(() => {
                            $(this).removeClass('letter-selected');
                        }, 1000);
                        index = $(this).attr('parent-index');
                    };
                });
                
                $(".carousel-indicators li").each(function(){
                    if ($(this).attr('data-slide-to') == parseInt(index)){
                        $(this).click();
                    }
                });         
            },

			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            getCategories: function() {
				axios.get('/api/v1/categories')
                .then(response =>{
                    this.main_category = response.data.data
                });
			},

            getTenants: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = '';

                this.tenant_list = category.alphabetical;
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = true;
                this.supplementals = false;
                this.show_tenant = false;
                if(this.tenant_list.length == 0) {
                    this.no_record_found = true;         
                }
            },

            getTenantsByCategory: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;

                axios.get('/api/v1/tenants/category/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    this.show_tenant = false;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                });

            },

            getTenantsBySupplementals: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;

                axios.get('/api/v1/tenants/supplemental/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    this.show_tenant = false;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                });
            },

            showCategories: function() {
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
            },

            showSupplementals: function() {
                this.previous_page = 'Supplementals';
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = false;
                this.supplementals = true;
                this.show_tenant = false;
                this.resetCarousel();       
            },     

            showChildren: function(category) {
                $('#category-tab').click();
                this.previous_page = 'Sub Category';
                this.current_category = category;
                this.child_category_count = category.children.length;
                this.current_supplementals = category.supplemental;
                this.page_title = 'Store List';
                this.category_label = category.label;
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
            },

            goBack: function() {
                if(this.show_tenant == true) {
                    this.page_title = 'Store List';
                    this.alphabetical = true;
                    this.show_tenant = false;
                }
                else if(this.child_category == true) {
                    this.page_title = 'Home';
                    this.home_category = true;
                    this.child_category = false;
                }
                else if(this.previous_page == 'Supplementals' && this.alphabetical == true) {
                    this.page_title = 'Store List';
                    this.supplementals = true;
                    this.alphabetical = false;
                } 
                else if(this.previous_page == 'Sub Category' && this.alphabetical == true) {
                    this.page_title = 'Store List';
                    this.child_category = true;
                    this.alphabetical = false;
                } 
                else if(this.alphabetical == true) {
                    this.page_title = 'Home';
                    this.home_category = true;
                    this.alphabetical = false;
                }         
                else if(this.supplementals == true) {
                    this.page_title = 'Home';
                    this.home_category = true;
                    this.supplementals = false;
                }
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                this.alphabetical = false;
                this.show_tenant = true;
            },

            showProduct: function(product) {
                this.product_image = product;
                $("#myProduct").show();
            },

            generateLetters: function () {
                for (let i = 65; i <= 90; i++) {
                    this.navigation_letters.push(String.fromCharCode(i))
                }
            }
        },

        mounted() {
            $(function() {
                $('.store-tabs-item').click(function () {
                    $('.store-tabs-item').removeClass('tab-item-selected');
                    $(this).addClass('tab-item-selected');
                });

                $(".btn-close-trailer").on('click',function(){
                    $("#myProduct").hide();
                });

                $(".hc-button").on('click', function() {
                    var category_id = $(this).data('category_id');
                    alert(category_id);
                });
            });
        },
    };
    
</script>
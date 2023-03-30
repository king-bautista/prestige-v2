<template>
    <div style="width: 100%;">
        <div class="router-page" v-show="homeIsShown">
            <div class="row">
                <div class="col-md-6">
                    <div id="page-title" v-if="page_title != 'Category'">{{ page_title }}</div>
                </div>
                <div class="col-md-6 text-right">
                    <img :src="site_logo" class="logo-holder" @click="aboutButton('home')">
                </div>
            </div>

            <!-- MAIN CATEGORY -->
            <div v-show="home_category"> 
                <div class="row mt-15 mb-55">
                    <div class="col-md-12 main-home-title text-center translateme">Search your favorite stores</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div v-for="(category, index) in main_category" :class="[category.class_name, 'hc-button']" @click="showChildren(category);">
                            <img :src="category.kiosk_image_primary_path">
                            <div id="hc-button1" class="hc-button-align translateme resize">{{ category.label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUB CATEGORY -->
            <div v-show="child_category">
                <div v-if="child_category_count< 7" class="row mt-120 mb-41">
                    <div class="col-md-12 home-title-sub text-center translateme">{{current_category.label}}</div>
                </div>
                <div v-else-if="child_category_count< 11" class="row mb-27 mt-18">
                    <div class="col-md-12 home-title-sub text-center translateme">{{current_category.label}}</div>
                </div>
                <div v-else class="row mb-27">
                    <div class="col-md-12 home-title-sub text-center translateme">{{current_category.label}}</div>
                </div>
                <div class="row col-md-6 offset-md-3 mb-3 mw-51p">
                    <div v-for="subcategory in current_category.children" class="col-12 col-sm-6 text-left mt-3 p-0-5" @click="getTenantsByCategory(subcategory)">			
                        <div class="c-button ml-0">						
                            <img class="tenant-category" :src="subcategory.kiosk_image_primary_path" style="max-width:100%">
                            <div class="c-button-align c-button-color2"><p class="translateme">{{subcategory.label}}</p></div>                        
                        </div>					
                    </div>
                </div>
            </div>

            <!-- SUPPLEMENTALS -->
            <div v-show="supplementals">
                <div class="row mb-27">
                    <div class="col-md-12 home-title-sub text-center translateme">{{ current_category.label}}</div>
                </div>
                <div class="row col-md-10 offset-md-1 mb-3 w-1152">
                    <div id="alphabeticalCarousel" class="carousel slide" data-ride="false" data-interval="false" data-wrap="false">

                        <!-- Indicators -->
                        <ul class="carousel-indicators z-1">
                            <li data-target="#alphabeticalCarousel" v-for="(supplementals, index) in current_supplementals.children" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"></li>                 
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner carousel-mh-626">
                            <div class="carousel-item" v-for="(supplementals, index) in current_supplementals.children" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_supplementals_count? 'last-item':'']">
                                <div class="row mb-3">
                                    <div v-for="supplemental in supplementals" class="col-12 col-sm-4 text-left mt-3" @click="helper.saveLogs(supplemental, 'Category'); getTenantsBySupplementals(supplemental)">			
                                        <div class="c-button">						
                                            <img class="tenant-category" :src="supplemental.kiosk_image_primary_path" style="max-width:100%">
                                            <div class="c-button-align c-button-color2"><p class="translateme">{{supplemental.label}}</p></div>                        
                                        </div>					
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev control-prev-s p-l-z-a" href="#alphabeticalCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next control-next-s n-l-z-a" href="#alphabeticalCarousel" data-slide="next" v-show="current_supplementals_count>=1">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ALPHABETICAL -->
            <div v-show="alphabetical">
                <div :class="(category_top_banner) ? 'row mt-14 mb-18' : 'row mb-27' ">
                    <div class="col-md-12 home-title-sub text-center">
                        <div v-if="!category_top_banner">{{ category_label }}</div>
                        <div class="hts-strip" v-if="category_top_banner">
                            <img class="tenant-category-strip" :src="category_top_banner" style="width:100%">
                            <div class="hts-strip-align hts-strip-color2 translateme">{{ category_label }}</div>                                        
                        </div>                    
                    </div>
                </div>
                <div class="row col-md-10 offset-md-1">
                    <div id="supplementalCarousel" class="carousel slide" data-ride="false" data-interval="false" data-wrap="false" v-show="!no_record_found">

                        <!-- Indicators -->
                        <ul class="carousel-indicators carousel-indicators-a z-1" v-show="tenant_list_count>0">
                            <li data-target="#supplementalCarousel" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'first-item active':''"></li>                    
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner" :class="(category_top_banner) ? 'carousel-mh-596' : 'carousel-mh-626' ">
                            <div class="carousel-item tenant-store-carousel" v-for="(tenants, index) in tenant_list" :data-index="index" v-bind:class = "[index == 0 ? 'first-item active':'', index == tenant_list_count? 'last-item':'']">
                                <div class="row mb-3">
                                    <div v-for="tenant in tenants" class="col-12 col-sm-4 text-left mt-3">
                                        <div class="tenant-store bg-white text-center box-shadowed ml-3" @click="helper.saveLogs(tenant, 'Category'); showTenant(tenant)">
                                            <div class="image-holder h-100">
                                                <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                            </div>
                                            <div class="text-left pta-2 brand-name">
                                                <div class="shop_name" :parent-index="index">{{ tenant.brand_name }}</div>
                                                <div style="font-size: 0.7em;color:#2a2a2a">{{ tenant.floor_name }}, {{ tenant.building_name }} </div>
                                                <div style="font-weight: bold;font-size: 0.7em">
                                                    <span class="translateme text-success" v-if="tenant.active==1">Open</span>
                                                    <span class="translateme text-success" v-if="tenant.active==0">Close</span>
                                                    <span class="featured_shop" v-if="tenant.is_subscriber==1">Featured</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev control-prev-a p-l-z-a" href="#supplementalCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next control-next-a n-l-z-a" href="#supplementalCarousel" data-slide="next" v-show="tenant_list_count>=1">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                    <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" class="no-record-found">
                </div>
            </div>

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
                            <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="tenant-logo box-shadowed mt-82 ml-94">
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
                                    <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop">Get Directions</button>
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
            <div class="tabs-container" v-show="tabs_container">
                <div class="tabs">
                    <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">View stores by</span>: </span>
                    <div class="tabs-item store-tabs-item tab-item-selected" id="category-tab" data-link="Category" @click="showCategories()">
                        <div>
                            <a class="translateme tenant-category">Category</a>
                        </div>
                    </div>
                    <div class="tabs-item store-tabs-item" id="alphabetical-tab" data-link="Alphabetical" @click="getTenants(current_category);">
                        <div>
                            <a class="translateme tenant-alphabet">Alphabetical</a>
                        </div>
                    </div>
                    <div class="tabs-item store-tabs-item" id="supplementals-tab" data-link="Supplementals" @click="showSupplementals();">
                        <div>
                            <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental">{{ current_category.supplemental.name }}</a>
                        </div>
                    </div>
                </div>
                <div class="p-a">
                    <ol class="navigation-letters" v-show="navigationLetters">
                        <li v-for="letter in navigation_letters" :class="(available_letters.includes(letter) ? '' : 'disabled')" @click="moveTo(letter)">{{letter}}</li>
                    </ol>
                </div>
            </div>
            <img v-show="!home_category" :src="back_button" class="back-button" @click="goBack">

            <!-- MODAL -->
            <div class="custom-modal p-l-490" id="myProduct">
                <div class="custom-modal-position set-width">                    
                    <div class="text-right text-white">
                        <span class="btn-close-modal">X</span>
                    </div>
                    <img class="my-product-image" :src="product_image">
                </div>
            </div>

            <div class="custom-modal p-l-490" id="modal-schedule">
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
        <about-page v-show="aboutIsShown" ref="callAbout"></about-page>
        <search-page v-show="searchIsShown" ref="callSearch"></search-page>
        <map-page v-show="mapIsShown" ref="callMap"></map-page>
        <promos-page v-show="promosIsShown" ref="callPromo"></promos-page>
        <cinema-page v-show="cinemaIsShown" ref="callCinema"></cinema-page>
        <assitant-page></assitant-page>
        <div class="row">
            <div class="col-md-12 text-center pt-2 pr-136">
                <div class="h-button widget-button home-button active logs" data-link='Home' @click="homeButton">
                    <div class="button-text-align translateme">Home</div>
                </div>
                <div class="h-button widget-button search-button logs" data-link='Search' @click="searchButton">
                    <div class="button-text-align translateme">Search</div>
                </div>
                <div class="h-button widget-button map-button logs" data-link='Map' @click="mapButton">
                    <div class="button-text-align translateme">Map</div>    
                </div>
                <div class="h-button widget-button promos-button logs" data-link='Promos' @click="promosButton">
                    <div class="button-text-align translateme resize">Promos</div>
                </div>
                <div class="h-button widget-button cinema-button logs" data-link='Cinema' @click="cinemaButton">
                    <div class="button-text-align translateme">Cinema</div>
                </div>
            </div>
        </div>

        <div class="multilanguage">
            <div class="btn-group dropup">
                <!-- MULTILANGUAGE -->
                <button type="button" class="rm-box-shadow btn-language btn btn-prestige dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ current_language }}
                </button>
                <div class="dropdown-menu">
                    <a v-for="(language, index) in languages" class="dropdown-item dropdown-item-language" href="#" :data-language="index" @click="changeLanguage(index,language)">{{ index }}</a>	
                </div>
            </div>
            <div style="font-size:0.58em;">LANGUAGE SELECT</div>
        </div>
    </div>
</template>
<script>
    import { isTemplateElement } from '@babel/types';
    import about from './About.vue';
    import search from './Search.vue';
    import map from './Map.vue';
    import promos from './Promos.vue';
    import cinema from './Cinema.vue';
    import assitant from './Assistant.vue';

	export default {
        name: "Home",
        components: {
            'about-page': about,
            'search-page': search,
            'map-page': map,
            'promos-page': promos,
            'cinema-page': cinema,
            'assitant-page': assitant
        },
        data() {
            return {
                main_category: [],
                tenant_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Category',
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
                tenant_list_count: 0,
                current_supplementals_count: 0,
                navigationLetters: false,
                available_letters: [],
                tabs_container: false,
                isAlphabeticalClicked: false,
                homeIsShown: true,
                aboutIsShown: false,
                searchIsShown: false,
                mapIsShown: false,
                promosIsShown: false,
                cinemaIsShown: false,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule :[],
                trigger_from: '',
                current_language: 'ENGLISH',
                current_language_set: '',
                languages: {'한국어':'korean','日本人':'japanese','中文':'chinese','FILIPINO':'filipino','ENGLISH':'english'},
                translations: '',
                translated: '',
                translations_by_language: '',
            };
        },

        created() {
            this.getSite();
            this.getCategories();
            this.generateLetters();
            this.getTranslation();
        },

        methods: {
            aboutButton: function (event) {
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = true;
                this.$refs.callAbout.setPage(event);
            },

            homeButton: function (event) {
                this.home_category = true;
                this.child_category = false;
                this.tabs_container = false;
                this.isAlphabeticalClicked = false;
                this.show_tenant = false;
                this.alphabetical = false;
                this.supplementals = false;
                this.homeIsShown = true;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
            },

            searchButton: function (event) {
                // console.log(event.target.getAttribute("data-link"));
                this.homeIsShown = false;
                this.searchIsShown = true;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.$refs.callSearch.resetPage();
                this.$refs.callPromo.resetPage();
                this.$refs.callCinema.resetPage();
            },

            mapButton: function (event) {
                // console.log(event.target.getAttribute("data-link"));
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = true;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.$refs.callMap.resetPage();
            },

            promosButton: function (event) {
                // console.log(event.target.getAttribute("data-link"));
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = true;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.$refs.callPromo.resetPage();
            },

            cinemaButton: function (event) {
                // console.log(event.target.getAttribute("data-link"));
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = true;
                this.aboutIsShown = false;
                this.$refs.callCinema.resetPage();
            },

            returnFromAbout: function (event) {
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                if (event == 'home') {
                    this.homeIsShown = true;
                }
                if (event == 'search') {
                    this.searchIsShown = true;
                }
                if (event == 'map') {
                    this.mapIsShown = true;
                }
                if (event == 'promo') {
                    this.promosIsShown = true;
                }
                if (event == 'cinema') {
                    this.cinemaIsShown = true;
                }
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

            updateLikeCount: function(id) {
                this.tenant_details.like_count = parseInt(this.tenant_details.like_count) + 1;

                let params = {
                    id: this.tenant_details.id,
                    like_count: this.tenant_details.like_count
                }

                $.post( "/api/v1/like-count", params ,function(response) {
                    
                });
            },

            resetCarousel: function() {
                $(".control-prev-s").hide();
                $(".control-prev-a").hide();
                if(this.current_supplementals_count>1){
                    $(".control-next-s").show();
                }
                if(this.tenant_list_count>1){
                    $(".control-next-a").show();
                }  
            },

            TitleCasePerWord: function() {

                this.tenant_list.forEach(element => {
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

            moveTo: function(letter) {   
                $(".shop_name").removeClass('letter-selected'); 
                $(".navigation-letters li").removeClass('active'); 
                let index = 0;
                $(".shop_name").each(function(){
                    if($(this).html().startsWith(letter, 0)){
                        index = $(this).attr('parent-index');
                        return false;
                    };
                    if ($(this).html().match(/^\d/) && letter=="#") {
                        index = $(this).attr('parent-index');
                        return false;
                    };
                });
                $(".shop_name").each(function(){
                    if($(this).html().startsWith(letter, 0)){
                        $(this).addClass('letter-selected');
                    };
                    if ($(this).html().match(/^\d/) && letter=="#") {
                        $(this).addClass('letter-selected');
                    };
                });

                $(".navigation-letters li").each(function(){
                    if($(this).html().startsWith(letter, 0)){
                        $(this).addClass('active');
                    };
                });
                
                $(".carousel-indicators-a li").each(function(){
                    if ($(this).attr('data-slide-to') == parseInt(index)){
                        $(this).trigger('click');
                    }
                });         
            },

            initializeSwipe: function() {
				setTimeout(() => {
                    $('.first-item').trigger('click');
                }, 500);
			},

            filterLetterNavigator: function() {
				let letter_container = [];

                $(".shop_name").each(function(){
                    let tenant_name = $(this).html().charAt(0);
                    if (tenant_name.match(/^\d/)) {
                        letter_container.push("#");
                    }else{
                        letter_container.push(tenant_name);
                    };
                });

                this.available_letters = [...new Set(letter_container)];
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
                this.previous_page = 'Alphabetical';

                this.tenant_list = category.alphabetical;
                this.tenant_list_count = this.tenant_list.length -1;
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = true;
                this.supplementals = false;
                this.show_tenant = false;
                this.navigationLetters = true;
                this.isAlphabeticalClicked = true;
                if(this.tenant_list.length == 0) {
                    this.no_record_found = true;  
                        
                }
                this.initializeSwipe();
                this.resetCarousel();
                setTimeout(() => {
                    this.filterLetterNavigator();
                }, 500);
                this.TitleCasePerWord();
            },

            getTenantsByCategory: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;
                this.helper.saveLogs(category, 'Category');
                this.navigationLetters = false;
                this.previous_page = 'Sub Category';

                axios.get('/api/v1/tenants/category/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    this.show_tenant = false;
                    this.tenant_list_count = this.tenant_list.length -1;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                    this.initializeSwipe();
                    this.resetCarousel();
                    this.TitleCasePerWord();
                });       
            },

            getTenantsBySupplementals: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;
                this.previous_page = 'Supplementals';

                axios.get('/api/v1/tenants/supplemental/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    this.show_tenant = false;
                    this.tenant_list_count = this.tenant_list.length -1;
                    this.navigationLetters = false;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                    this.TitleCasePerWord();
                    this.resetCarousel();
                });               
            },

            showCategories: function() {
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
                this.navigationLetters = false;
                this.initializeSwipe();
                this.resetCarousel();
            },

            showSupplementals: function() {
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = false;
                this.supplementals = true;
                this.show_tenant = false; 
                this.navigationLetters = false;
                this.initializeSwipe();     
            },     

            showChildren: function(category) {
                $('#category-tab').trigger('click');
                this.previous_page = 'Sub Category';
                this.current_category = category;
                this.child_category_count = category.children.length;
                this.current_supplementals = category.supplemental;
                this.current_supplementals_count = this.current_supplementals.children.length - 1;
                this.page_title = 'Store List';
                this.category_label = category.label;
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
                this.helper.saveLogs({category_id: category.id}, 'Category');
                this.initializeSwipe();
                this.tabs_container = true;
            },

            goBack: function() {
                if(this.show_tenant == true) {
                    this.page_title = 'Store List';
                    this.alphabetical = true;
                    this.show_tenant = false;
                    this.tabs_container = true;
                }
                else if(this.child_category == true) {
                    this.page_title = 'Category';
                    this.home_category = true;
                    this.child_category = false;
                    this.tabs_container = false;
                    this.isAlphabeticalClicked = false;
                }
                else if(this.previous_page == 'Supplementals' && this.alphabetical == true) {
                    this.page_title = 'Store List';
                    this.supplementals = true;
                    this.alphabetical = false;

                } 
                else if(this.previous_page == 'Alphabetical' && this.alphabetical == true) {
                    this.page_title = 'Store List';
                    this.child_category = true;
                    this.alphabetical = false;
                    $('#category-tab').trigger('click');
                    this.isAlphabeticalClicked = false;
                } 
                else if(this.previous_page == 'Sub Category' && this.alphabetical == true) {
                    this.page_title = 'Store List';
                    this.child_category = true;
                    this.alphabetical = false;
                    $('#category-tab').trigger('click');

                } 
                else if(this.alphabetical == true) {
                    this.page_title = 'Category';
                    this.home_category = true;
                    this.alphabetical = false;
                }  
                else if(this.previous_page == 'Alphabetical' && this.supplementals == true) {
                    this.page_title = 'Category';
                    this.home_category = false;
                    this.supplementals = false;
                    $('#alphabetical-tab').trigger('click');
                    this.previous_page = 'Sub Category';
                }       
                else if(this.supplementals == true) {
                    this.page_title = 'Category';
                    this.home_category = true;
                    this.supplementals = false;
                    if (this.previous_page == 'Alphabetical'){
                        $('#category-tab').trigger('click');
                    }
                    if (this.previous_page == 'Supplementals'){                   
                        if (this.previous_page == 'Supplementals' && this.isAlphabeticalClicked == false){
                            $('#category-tab').trigger('click');
                        }else{
                            $('#alphabetical-tab').trigger('click');
                        }
                    }
                    if (this.previous_page == 'Sub Category'){
                        $('#category-tab').trigger('click');
                    }
                    
                }            
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                this.alphabetical = false;
                this.show_tenant = true;
                this.tabs_container = false;
                this.buildSchedule(this.tenant_details);
            },

            showSchedule: function() {
                $("#modal-schedule").show();
            },

            showProduct: function(product,type) {
                this.product_image = product;
                $("#myProduct").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                if (type == 'banner'){
                    $('.set-width').addClass('banner-size');
                }
                if (type == 'product'){
                    $('.set-width').addClass('product-size');
                } 
            },

            generateLetters: function () {
                for (let i = 65; i <= 90; i++) {
                    this.navigation_letters.push(String.fromCharCode(i))
                }
            },

            changeLanguage: function (index,language) {
                this.current_language = index
                this.current_language_set = language
                this.setTranslation(language);
            },

            getTranslation: function() {
				axios.get('/api/v1/translation')
                .then(response => {
                    this.translations = response.data.data
                });   
			},

            setTranslation: function(language) {
                if (language != 'english') {
                    this.translations_by_language = this.translations.filter(option => option.language == language);
                }
                
                var vm = this;

                if (vm.translated == '') {
                    $(".translateme").each(function(){
                        let english_word = $(this).html().replace(/&amp;/g, '&');
                        if (vm.translations_by_language.find(option => option.english == english_word) != null) {
                            vm.translated = vm.translations_by_language.find(option => option.english == english_word);
                            $(this).html(vm.translated.translated);
                        }
                    });
                } else if (language == 'english') {
                    $(".translateme").each(function(){
                        let english_word = $(this).html().replace(/&amp;/g, '&');
                        if (vm.translations.find(option => option.translated == english_word) != null) {
                            vm.translated = vm.translations.find(option => option.translated == english_word);
                            $(this).html(vm.translated.english);
                        }
                    });
                    this.translated = '';
                } else {
                    $(".translateme").each(function(){
                        let english_word = $(this).html().replace(/&amp;/g, '&');
                        if (vm.translations.find(option => option.translated == english_word) != null) {
                            vm.translated = vm.translations.find(option => option.translated == english_word);
                            vm.translated = vm.translations_by_language.find(option => option.english == vm.translated.english);
                            $(this).html(vm.translated.translated);
                        }
                    });
                }   
                $(function() {
                    $('.resize').autoSizr(24);     
                });    
			},

        },

        mounted() {
            var obj = this;

            $(function() {
                // Call Parent Method from Child Component
                obj.$root.$on('MainCategories', () => {
                    obj.homeButton();
                });

                obj.$root.$on('callAboutParent', (value) => {
                    obj.aboutButton(value);
                    obj.trigger_from = value
                });

                obj.$root.$on('callAboutFrom', (value) => {
                    obj.returnFromAbout(value);
                });

                $('.store-tabs-item').on('click', function () {
                    $('.store-tabs-item').removeClass('tab-item-selected');
                    $(this).addClass('tab-item-selected');
                });

                $("#myProduct,#modal-schedule").on('click',function(){
                    $("#myProduct,#modal-schedule").hide();
                });

                $(".hc-button").on('click', function() {
                    var category_id = $(this).data('category_id');
                });

                $(".control-prev-s,.control-prev-a").hide();

                $('#alphabeticalCarousel').on('slid.bs.carousel', function () {
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-s").hide();
                        $(".control-prev-s").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-s").hide();
                        $(".control-next-s").show();
                    }else{
                        $(".control-prev-s").show();
                        $(".control-next-s").show();
                    }
                });

                $('#supplementalCarousel').on('slid.bs.carousel', function () {
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-a").hide();
                        $(".control-prev-a").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-a").hide();
                        $(".control-next-a").show();
                    }else{
                        $(".control-prev-a").show();
                        $(".control-next-a").show();
                    }
                });
                
                // $('.store-tabs-item').on('click', function(){
                //     var page = $(this).data('link');
                //     obj.helper.saveLogs({action: 'click'}, 'Category');
                // });
            });
        },
    };
    
</script>
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
        <!-- SEARCH/ RESULT -->
        <div v-show="search_page">
            <div class="row keyboard-section" v-show="!search_results">
                <div v-if="site_name == 'Parqal'" class="col-md-12">
                    <form class="row form text-center" v-on:submit.prevent="onEnter">
                        <div class="input-group mb-5 mt-5" v-bind:style="(site_orientation == 'Portrait') ? 'width: 80%;': 'width: 57%;'" style="margin: auto;"> 
                            <img src="images/search-icon.png" class="search-icon">
                            <input type="text" id="code" name="code" class="form-control input-mg search-box">
                            <button class="btn search-box-button translateme" type="button" @click="onEnter" data-en="Search">Search</button>
                            <label class="notification">Please type at least two (2) letters to search.</label>
                        </div>                    
                        <div class="softkeys mt-63" data-target="input[name='code']"></div>
                    </form>
                </div>
                <div v-else class="col-md-10 offset-md-1 mt-83 pt-5">
                    <form class="row form text-center" v-on:submit.prevent="onEnter">
                        <div class="input-group mb-5 mt-5" style="width: 70%; margin: auto;"> 
                            <input type="text" id="code" name="code" class="form-control input-mg search-box">
                            <button class="btn search-box-button translateme" type="button" @click="onEnter" data-en="Search">Search</button>
                            <label class="notification">Please type at least two (2) letters to search.</label>
                        </div>                    
                        <div class="softkeys mt-63" data-target="input[name='code']"></div>
                    </form>
                </div>
            </div>
            <div class="result-section" v-show="search_results">
                <div v-bind:class="(site_orientation == 'Portrait') ? 'mt-5': ''" class="row">
                    <div class="col-md-12 home-title text-center home-title-custom mt-4">
                        <div v-if="current_tenant_list_count < 0" class="mt-150">
                            <span class="translateme" data-en="We couldn’t find a match for">We couldn’t find a match for</span>
                            &nbsp;<span>‘{{this.search.key_words}}’.</span>
                            &nbsp; <span>Please try another search.</span>
                        </div>
                        <div v-else>
                            <span class="translateme" data-en="You searched for">You searched for</span>
                            <p style="font-size: 60px !important;">‘{{this.search.key_words}}’</p>
                        </div>            
                    </div>
                </div>

                <div v-bind:class="(site_orientation == 'Portrait') ? 'mt-5': ''" class="label-4 translateme no-results-container" v-show="current_tenant_list_count < 0">
                    <img src="images/no-results.png" />
                </div>

                <div class="row col-md-12 ml-2">
                    <div id="searchCarousel" v-bind:class="(site_orientation == 'Portrait') ? 'carousel-portrait': ''" class="carousel slide" data-ride="false" data-interval="false" data-touch="true" data-wrap="false">
                                    
                        <!-- Control dots -->
                        <ul v-bind:class="[(current_subscriber_list_count > 0 ? 'carousel-indicators carousel-indicators-with-subscriber z-1': 'carousel-indicators carousel-indicators-search z-1'), (site_orientation == 'Portrait' ? 'carousel-indicators-with-subscriber-portrait carousel-indicators-search-portrait' : '')]" v-show="current_tenant_list_count>0">
                            <li data-target="#searchCarousel" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner" :class="(category_top_banner) ? 'carousel-mh-596' : 'carousel-mh-626' ">
                            <div v-bind:class="[(site_orientation == 'Portrait' ? 'carousel-item': 'carousel-item tenant-store-carousel'), (index == 0 ? 'first-item active':''), (index == tenant_list_count? 'last-item':'')]" v-for="(tenants, index) in tenant_list" :data-index="index">
                                <div v-bind:class="[(site_orientation == 'Portrait') ? 'row mb-3': 'row mb-3', (current_subscriber_list_count > 0 && site_orientation == 'Portrait') ? 'mt-100' : '']">
                                    
                                    <div v-for="tenant in tenants" v-bind:class="[tenant_list[0].length === 1 ? 'col-sm-12 text-center mt-3' : '', tenant_list[0].length === 2 ? 'col-sm-6 text-center mt-3':'', tenant_list[0].length > 2 ? 'col-sm-4 text-left mt-3' : '']">
                                        <div v-if="site_name == 'Parqal'">
                                            <div v-bind:class="[(site_orientation == 'Portrait' ? 'tenant-store tenant-store-portrait text-center': 'tenant-store text-center ml-3'), (tenant_list[0].length <= 2 && site_orientation == 'Portrait') ? 'tenant-store-custom-portrait m-auto': '', (tenant_list[0].length <= 2 && site_orientation == 'Landscape') ? 'tenant-store-custom m-auto': '']" @click="helper.saveLogs(tenant, 'Categories'); (tenant.is_subscriber==1) ? showTenant(tenant) : findStore(tenant,current_page);">
                                                <div v-bind:class="tenant_list[0].length <= 2 ? 'image-holder-custom h-100' : 'image-holder h-100'">
                                                    <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                                </div>
                                                <div v-bind:class="tenant_list[0].length <= 2 ? 'text-left pta-2 brand-name' : 'text-left pta-2 brand-name'">
                                                    <div v-bind:class="tenant_list[0].length <= 2 ? 'f-size-25' : ''"  class="shop_name" :parent-index="index">{{ tenant.brand_name }}</div>
                                                    <div v-if="tenant.tenant_details" v-bind:class="tenant_list[0].length <= 2 ? 'f-size-20' : ''" class="shop_address">{{ tenant.tenant_details.address }}</div>
                                                    <div v-else v-bind:class="tenant_list[0].length <= 2 ? 'f-size-20' : ''" class="shop_address">{{ tenant.floor_name }}, {{ tenant.building_name }} </div>
                                                    <div style="font-weight: bold;font-size: 0.7em">
                                                        <!-- <span class="translateme text-success" v-if="tenant.active==1" data-en="Open">Open</span>
                                                        <span class="translateme text-success" v-if="tenant.active==0" data-en="Close">Close</span> -->
                                                        <span class="featured_shop" v-if="tenant.is_subscriber==1">Featured</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="tenant-store bg-white text-center box-shadowed ml-3" @click="helper.saveLogs(tenant, 'Categories'); showTenant(tenant)">
                                                <div class="image-holder h-100">
                                                    <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                                </div>
                                                <div class="text-left pta-2 brand-name">
                                                    <div class="shop_name" :parent-index="index">{{ tenant.brand_name }}</div>
                                                    <div style="font-size: 0.7em;color:#2a2a2a">{{ tenant.floor_name }}, {{ tenant.building_name }} </div>
                                                    <div style="font-weight: bold;font-size: 0.7em">
                                                        <span class="translateme text-success" v-if="tenant.active==1" data-en="Open">Open</span>
                                                        <span class="translateme text-success" v-if="tenant.active==0" data-en="Close">Close</span>
                                                        <span class="featured_shop" v-if="tenant.is_subscriber==1">Featured</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>  
                        </div>

                        <!-- Left and right controls -->
                        <a v-bind:class="[(site_orientation == 'Portrait') ? 'carousel-control-prev-search-custom-portrait': 'carousel-control-prev-search-custom', (site_orientation == 'Portrait' && current_subscriber_list_count > 0) ? 'arrow-heigh': '']" class="carousel-control-prev control-prev-sp p-l-z-a" href="#searchCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a v-bind:class="[(site_orientation == 'Portrait') ? 'carousel-control-next-search-custom-portrait': 'carousel-control-next-search-custom', (site_orientation == 'Portrait' && current_subscriber_list_count > 0) ? 'arrow-heigh': '']" class="carousel-control-next control-next-sp n-l-z-a" href="#searchCarousel" data-slide="next" v-show="current_tenant_list_count>=1">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>

                    <div v-bind:class="(site_orientation == 'Portrait') ? 'want-to-try-portrait': 'want-to-try'" v-show="current_subscriber_list_count>0">
                        <div class="row">
                            <div class="col-12 pl-100">
                                <span class="translateme" data-en="You might want to try : ">You might want to try : </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 pl-100">
                                <img v-for="subscriber in subscriber_list" class="shop-logo tenant-store" :src="subscriber.subscriber_logo" :alt="subscriber.brand_name" @click="onClickSuggestedSubsriber(subscriber.id)">
                            </div>
                        </div>
                    </div>

                    <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: auto;">
                </div>
            </div>
        </div>

        <!-- TENANT PAGE -->
        <div v-show="show_tenant">
            <div v-if="site_name == 'Parqal'" class="row">
                <template v-if="site_orientation == 'Portrait'">
                    <div class="col-sm-12 mt-2">
                        <div class="row tenant-details-portrait ml-4 mr-4">
                            <div class="col-sm-3 text-center">
                                <div class="my-auto pt-3">
                                    <img class="tenant-details-logo" :src="tenant_details.brand_logo">
                                    <div class="tenant-details-views-portrait"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;<span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                                </div>
                            </div>
                            <div class="col-sm-4 offset-sm-1 text-center p-3">
                                <div class="tenant-details-name">{{ tenant_details.brand_name }}</div>
                                <div v-if="tenant_details.tenant_details" class="tenant-details-floor mt-2">{{ tenant_details.tenant_details.address }}</div>
                                <div v-else class="tenant-details-floor mt-2">{{ tenant_details.floor_name }}, {{ tenant_details.building_name }}</div>
                                <div v-bind:class="(site_orientation == 'Portrait') ? '': 'mt-4'">
                                    <span class="btn-schedule" v-if="tenant_details.operational_hours" @click="showSchedule">
                                        <span v-if="tenant_details.operational_hours.is_open" class="text-success"><strong>Open</strong></span>
                                        <span v-else class="text-danger"><strong>Closed</strong></span>
                                        | <span style="color:#2a2a2a;"><strong>{{ tenant_details.operational_hours.start_time }}&nbsp;-&nbsp;{{ tenant_details.operational_hours.end_time }}</strong></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center">
                                <div class="text-left ml-4 social-holder-portrait" v-if="tenant_details.tenant_details">
                                    <div v-if="tenant_details.tenant_details.facebook && (tenant_details.tenant_details.facebook != 'null' || tenant_details.tenant_details.facebook != 'undefined')" class="mb-2 w-500">
                                        <img src="assets/images/parqal-facebook.png" class="mr-2" width="40">
                                        <div class="social-text-container">
                                            {{ tenant_details.tenant_details.facebook }}
                                        </div>
                                    </div>
                                    <div v-if="tenant_details.tenant_details.twitter && (tenant_details.tenant_details.twitter != 'null' || tenant_details.tenant_details.twitter != 'undefined')" class="mb-2 w-500" >
                                        <img src="assets/images/parqal-twitter.png" class="mr-2" width="40">
                                        <div class="social-text-container">
                                            {{ tenant_details.tenant_details.twitter }}
                                        </div>
                                    </div>
                                    <div v-if="tenant_details.tenant_details.instagram  && (tenant_details.tenant_details.instagram != 'null' || tenant_details.tenant_details.instagram != 'undefined')" class="mb-2 w-500">
                                        <img src="assets/images/parqal-instagram.png" class="mr-2" width="40">
                                        <div class="social-text-container">
                                            {{ tenant_details.tenant_details.instagram }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center mt-3 ml-4 mr-4">
                        <div v-if="tenant_details.is_subscriber" class="tenant-products-container-portrait mb-3">
                            <div class="products-container">
                                <div class="row ml-1 mt-16" v-if="tenant_details.products.banners">
                                    <div class="col-12 p-0">
                                        <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid tenant_page_banner_img" @click="showProduct(tenant_details.products.banners[0].image_url_path,'banner')">
                                    </div>
                                </div>
                                <template v-if="tenant_details.products.length == 0">
                                    <div class="centered-container">
                                        <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="tenant-logo">
                                    </div>
                                </template>
                                <template v-else>
                                    <template v-if="tenant_details.products.product_list.length > 0">
                                        <div class="row subscriber-products-portrait ml-0" v-bind:class = "[(tenant_details.products.product_list.length > 3) ? 'with-out-height':'with-height-portrait', (tenant_details.products.product_list.length > 3) ? 'mt-5':'']">
                                            <div v-for="product in tenant_details.products.product_list" v-bind:class="(tenant_details.products.product_list.length > 3) ? 'f-left' : 'm-auto'">
                                                <img :src="product.image_url_path" v-bind:class="[tenant_details.products.product_list.length > 3 ? 'img-promo-portrait-4' : '',tenant_details.products.product_list.length == 3 ? 'img-promo-portrait-3' : '', tenant_details.products.product_list.length == 2 ? 'img-promo-portrait-2' : '', tenant_details.products.product_list.length == 1 ? 'img-promo' : '']" class="rounded-corner" @click="showProduct(product.image_url_path,'product')">
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-left">
                                <div class="btn btn-prestige-rounded w-100 bg-white btn-like-portrait ">
                                    <span class="text-danger ml-2 btn-like" @click="updateLikeCount(tenant_details.id,tenant_details.like_count)">
                                        <i class="far fa-heart btn-heart btn-heart-portrait" aria-hidden="true"></i>
                                        <a class="btn-like-display btn-like-display-portrait">{{ tenant_details.like_count }}
                                            <span class="translateme" data-en="Likes">Likes</span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <button v-if="tenant_details.is_subscriber" class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop-portrait translateme mr-5" data-en="Get Directions" @click="findStore(tenant_details,current_page);">Get Directions</button>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="col-sm-2 mr-0 p-0 pl-3">
                        <div class="p-2 tenant-details">
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
                                        <div v-if="tenant_details.tenant_details.facebook && (tenant_details.tenant_details.facebook != 'null' || tenant_details.tenant_details.facebook != 'undefined')" class="mb-2 w-500">
                                            <img src="assets/images/parqal-facebook.png" class="mr-2" width="40">
                                            <div class="social-text-container">
                                                {{ tenant_details.tenant_details.facebook }}
                                            </div>
                                        </div>
                                        <div v-if="tenant_details.tenant_details.twitter && (tenant_details.tenant_details.twitter != 'null' || tenant_details.tenant_details.twitter != 'undefined')" class="mb-2 w-500" >
                                            <img src="assets/images/parqal-twitter.png" class="mr-2" width="40">
                                            <div class="social-text-container">
                                                {{ tenant_details.tenant_details.twitter }}
                                            </div>
                                        </div>
                                        <div v-if="tenant_details.tenant_details.instagram  && (tenant_details.tenant_details.instagram != 'null' || tenant_details.tenant_details.instagram != 'undefined')" class="mb-2 w-500">
                                            <img src="assets/images/parqal-instagram.png" class="mr-2" width="40">
                                            <div class="social-text-container">
                                                {{ tenant_details.tenant_details.instagram }}
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <button v-if="tenant_details.is_subscriber" class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions" @click="findStore(tenant_details,current_page);">Get Directions</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 text-center">
                        <div v-if="tenant_details.is_subscriber" class="tenant-products-container">
                            <div class="products-container">
                                <div class="row ml-1 mt-16" v-if="tenant_details.products.length > 0">
                                    <div class="col-12 p-0" v-if="tenant_details.products.banners">
                                        <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid tenant_page_banner_img" @click="showProduct(tenant_details.products.banners[0].image_url_path,'banner')">
                                    </div>
                                </div>
                                <template v-if="tenant_details.products.length == 0">
                                    <div class="centered-container">
                                        <img :src="tenant_details.brand_logo" :alt="tenant_details.brand_name" class="tenant-logo">
                                    </div>
                                </template>
                                <template v-else>
                                    <template v-if="tenant_details.products.product_list.length > 0">
                                        <div class="row subscriber-products ml-0" v-bind:class = "[(tenant_details.products.product_list.length > 2) ? 'with-out-height':'with-height', (tenant_details.products.product_list.length > 3) ? 'mt-5':'']">
                                            <div v-for="product in tenant_details.products.product_list" v-bind:class="(tenant_details.products.product_list.length > 2) ? 'f-left' : 'm-auto'">
                                                <img :src="product.image_url_path" v-bind:class="[tenant_details.products.product_list.length > 3 ? 'img-promo-4' : '',tenant_details.products.product_list.length == 3 ? 'img-promo-3' : '', tenant_details.products.product_list.length == 2 ? 'img-promo-2' : '', tenant_details.products.product_list.length == 1 ? 'img-promo' : '']" class="rounded-corner" @click="showProduct(product.image_url_path,'product')">
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>                    
            </div>
            <div v-else class="row">
                <div class="col-12 col-sm-8 text-center">
                    <div v-if="tenant_details.is_subscriber && tenant_details.products">
                        <div class="row ml-1 mt-16" v-if="tenant_details.products">
                            <div class="col-12 p-0">
                                <img :src="tenant_details.products.banners[0].image_url_path" class="rounded-corner img-fluid tenant_page_banner_img" @click="showProduct(tenant_details.products.banners[0].image_url_path,'banner')">
                            </div>
                        </div>
                        <template v-if="tenant_details.products.length > 0">
                            <div class="row subscriber-products mt-15 ml-0" v-bind:class = "(tenant_details.products.banners.length > 0) ? 'with-banner-height':'with-out-banner-height'">
                                <div v-for="product in tenant_details.products.products" class="m-15-18">
                                    <img :src="product.image_url_path" class="rounded-corner box-shadowed img-promo" @click="showProduct(product.image_url_path,'product')">
                                </div>
                            </div>
                        </template>
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
                            <div class="tenant-details-views"><span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                            <div class="mt-2 mb-2">
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
                                <div v-if="tenant_details.tenant_details.facebook && (tenant_details.tenant_details.facebook != 'null' || tenant_details.tenant_details.facebook != 'undefined')"><img src="assets/images/parqal-facebook.png" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                <div v-if="tenant_details.tenant_details.twitter && (tenant_details.tenant_details.twitter != 'null' || tenant_details.tenant_details.twitter != 'undefined')"><img src="assets/images/parqal-twitter.png" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                <div v-if="tenant_details.tenant_details.instagram  && (tenant_details.tenant_details.instagram != 'null' || tenant_details.tenant_details.instagram != 'undefined')"><img src="assets/images/parqal-instagram.png" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
                            </div>
                        </div>
                        <div v-else class="row mt-3 mb-206">
                            <div class="col-6">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt mr-10" aria-hidden="true"></i><div class="translateme resize-share" data-en="Share">Share</div>
                                </a>
                            </div>
                            <div class="col-6">
                                <span class="text-danger ml-2 btn-like" @click="updateLikeCount(tenant_details.id,tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span class="translateme" data-en="Likes">Likes</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div v-if="tenant_details.is_subscriber" class="row p-r-t-94">
                            <div class="col-6 mt-3">
                                <button v-if="tenant_details.is_subscriber" class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop resize-get-direction translateme" data-en="Get Directions" @click="findStore(tenant_details,current_page)">Get Directions</button>
                            </div>
                            <div class="col-6 mt-3">
                                <span class="text-danger ml-2 btn-like"  @click="updateLikeCount(tenant_details.id, tenant_details.like_count)">
                                    <i class="fa fa-heart btn-heart" aria-hidden="true"></i>
                                    <a class="btn-like-display">{{ tenant_details.like_count }}
                                        <span class="translateme" data-en="Likes">Likes</span>
                                    </a>
                                </span>
                            </div>
                            <div class="col-6 mt-3">
                                <a type="button" class="btn btn-share" disabled>
                                    <i class="fa fa-share-alt mr-10" aria-hidden="true"></i><div class="translateme resize-share" data-en="Share">Share</div>
                                </a>
                            </div>
                            <div class="col-6 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now translateme" data-en="Order Now">Order Now</button>
                            </div>
                        </div>
                        <div v-else class="row mt-3">
                            <div class="col-12 mt-3">
                                <button v-if="tenant_details.is_subscriber" class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop translateme" data-en="Get Directions" @click="findStore(tenant_details,current_page);">Get Directions</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button v-if="tenant_details.is_subscriber" class="btn btn-prestige-rounded btn-prestige-pwd w-100 btn-direction-shop-pwd translateme" data-en="Get Directions (PWD-friendly)">Get Directions (PWD-friendly)</button>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn w-100 btn-prestige-rounded btn-order-now translateme" data-en="Order Now">Order Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div class="custom-modal p-l-490" id="myProductSearch">
            <div class="custom-modal-position set-width">                    
                <div class="text-right text-white">
                    <span class="btn-close-modal">X</span>
                </div>
                <img class="my-product-image" :src="product_image">
            </div>
        </div>

        <div class="custom-modal p-l-490" id="modal-schedule-search">
            <div class="custom-modal-position set-width-schedule">                    
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
                    <span class="btn-close-modal"><i class="far fa-times-circle"></i></span>
                </div>       
            </div>
        </div>

        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>
    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                category_top_banner: "",
                current_date: "",
                current_time: "",
                search: {
                    key_words: '',
                    results: '',
                    id: '',
                },
                tenant_list: [],
                tenant_list_temp: [],
                suggestion_list: '',
                subscriber_list: '',
                site_name: '',
                site_logo: '',
                site_orientation: '',
                back_button: 'assets/images/English/Back.png',
                search_page: true,
                page_title: 'Search',
                no_record_found: false,
                search_results: false,
                tenant_details: '',
                show_tenant: false,
                product_image: '',
                helper: new Helpers(),
                fromAutoSuggest: false,
                tenant_list_count: 0,
                current_tenant_list_count: 0,
                current_subscriber_list_count: 0,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule: [],
                temp: [],
                temp_subscriber_list: [],
                temp2_subscriber_list: [],
                current_page: '',
            };
        },

        created() {
            this.getSite();
            setInterval(this.getDateNow, 1000);
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
                    this.site_name = response.data.data.name;
                    this.site_logo = response.data.data.site_logo;
                    this.site_orientation = response.data.data.site_orientation;
                });
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

            onEnter: function() {
                if ($('#code').val().length >= 2) {
                    this.search.key_words = $('#code').val();
                    this.search.id = '';
                    axios.post('/api/v1/search', this.search)
                    .then(response => {
                        this.tenant_list = response.data.data[0];
                        this.subscriber_list = response.data.data[1];
                        // this.temp_subscriber_list = response.data.data[1];

                        this.TitleCasePerWord()

                        if(this.tenant_list.length == 0) {
                            this.no_record_found = false;   
                            this.search_results = true;      
                        }else {
                            this.search_results = true;
                        }

                        if (this.temp.length == 0) {
                            this.subscriber_list = this.subscriber_list.slice(0, 3);
                            this.temp = this.subscriber_list;
                        }else {
                            
                            this.subscriber_list.forEach(array => {      
                                if (this.temp.find(option => option.id == array.id)) {
                                    this.temp_subscriber_list.push(array)
                                }else {
                                    this.temp_subscriber_list.unshift(array)
                                }
                            });

                            this.subscriber_list = this.temp_subscriber_list
                            this.temp_subscriber_list = []
                            this.subscriber_list = this.subscriber_list.slice(0, 3);
                            this.temp = this.subscriber_list;
                        }

                        this.tenant_list_count = this.tenant_list.length - 1;
                        this.current_tenant_list_count = this.tenant_list.length - 1;
                        this.current_subscriber_list_count = this.subscriber_list.length;
                        this.search.results = response.data.data_count;
                        this.page_title = 'Search';
                        this.helper.saveLogs(this.search, 'Search');
                        this.resetCarousel();

                        if(this.tenant_list.length == 0) {
                            this.$root.$emit('callMutateLocation','searchnoresult');
                        }else {
                            this.$root.$emit('callMutateLocation','searchresult');
                        }

                        setTimeout(() => {
                            $('.first-item').trigger('click');
                        }, 100);

                        setTimeout(() => {
                            this.$root.$emit('callSetTranslation');
                        }, 100);
                    })
                }else{
                    $('.notification').show();
                }
            },

            onClickSuggest: function(id) {
                this.search.key_words = $('#code').val();
                this.search.id = id;
                axios.post('/api/v1/search', this.search)
				.then(response => {
                    this.tenant_details = '';
                    this.tenant_list = response.data.data;
                    this.tenant_details = response.data.data.shift();
                    this.page_title = 'Store Page';
                    this.search_page = false;
                    this.show_tenant = true;
                    this.fromAutoSuggest = true;

                    if(!this.tenant_details.is_subscriber) {
                        this.$root.$emit('showTenantMap', this.tenant_details, 'search');
                    }
                    else {
                        this.$root.$emit('callMutateLocation','tenant');
                        this.buildSchedule(this.tenant_details);
                        $('.resize-share').autoSizr(21);
                        $(".btn-like-display").removeClass('disabled-response');
                        $(".btn-heart").removeClass('fas').addClass('far');
                        $(".products-container").animate({ scrollTop: 0 });
                        $('#myProduct').hide();
                    }
				})     
            },

            onClickSuggestedSubsriber: function(id) {
                this.search.id = id;
                axios.post('/api/v1/search', this.search)
				.then(response => {
                    this.tenant_details = '';
                    this.tenant_list_temp = response.data.data;   
                    this.tenant_details = this.tenant_list_temp[0];
                    this.page_title = 'Store Page';
                    this.search_page = false;
                    this.show_tenant = true;
                    this.fromAutoSuggest = false;

                    this.$root.$emit('callMutateLocation','tenant');

                    this.buildSchedule(this.tenant_details);

                    $(".btn-like-display").removeClass('disabled-response');
                    $(".btn-heart").removeClass('fas').addClass('far');
                    $(".products-container").animate({ scrollTop: 0 });
                    $('#myProduct').hide();
				})     
            },

            goBack: function() {
                $('.ui-autocomplete').empty();

                if(this.show_tenant == true && this.fromAutoSuggest == false) {
                    this.search_page = true;
                    this.search_results = true;
                    this.show_tenant = false;
                    $("#code").val('');
                    this.page_title = 'Search';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else if(this.fromAutoSuggest == true) {
                    this.tenant_list = [];
                    this.search_page = true;
                    this.show_tenant = false;
                    this.search_results = false;
                    this.getSuggestionList();
                    $("#code").val('');
                    this.fromAutoSuggest = false;
                    this.page_title = 'Search';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else if(this.show_tenant == false && this.search_results == true) {
                    this.tenant_list = [];
                    this.search_page = true;
                    this.search_results = false;
                    this.getSuggestionList();
                    $("#code").val('');
                    this.page_title = 'Search';

                    setTimeout(() => {
                        this.$root.$emit('callSetTranslation');
                    }, 100);
                }
                else {
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                    $("#code").val('');
                    this.$root.$emit('MainCategories');
                }
            },

            getSuggestionList: function() {
                axios.get('/api/v1/tenants/suggestion/list')
                .then(response => {
                    this.suggestion_list = response.data.data;

                    const titleCase = this.suggestion_list;

                    Object.keys(this.suggestion_list).forEach(item => {    
                        const splitBrandName = this.suggestion_list[item].value.toLocaleLowerCase().split(" ");

                        for (var i = 0; i < splitBrandName.length; i++) {
                            splitBrandName[i] = splitBrandName[i].charAt(0).toUpperCase() + splitBrandName[i].slice(1);
                        }      

                        if (splitBrandName.join(" ").match(/(\(.*\))/)) {
                            const text = splitBrandName.join(" ");
                            
                            const strToReplace = splitBrandName.join(" ").match(/(\(.*\))/)[0];

                            this.suggestion_list[item].value = (text.replace(strToReplace, strToReplace.toUpperCase()));
                        } else {
                            this.suggestion_list[item].value = (splitBrandName.join(" "));
                        }
                    });

                    var obj = this;
                    $(function() {                    
                        $('#code').autocomplete({
                            classes: {
                                "ui-autocomplete": (obj.site_orientation == 'Portrait') ? "auto_complete_portrait" : "",
                            },
                            minLength: 2,
                            source: titleCase,
                            select: function(event, ui){''
                                if(ui.item.id)
                                {         
                                    obj.onClickSuggest(ui.item.id);
                                }else{
                                    $('#code').val(ui.item.value);
                                    $(".search-box-button").trigger('click');
                                }
                            }
                        }).data("uiAutocomplete")._renderItem = function (ul, item) {
                            let text = item.value;

                            var newText = String(text).replace(
                                    new RegExp(this.term, "gi"),
                                    "<span class='prestige-text-color text-bold'>$&</span>");
                            var floor = item.floor_name === null?"": ", " + item.floor_name;
                            var bldg = item.building_name === null?"": ", " + item.building_name;
                            
                            if (item.building_name == 'Main Building'){
                                var attrib = floor;
                            }else{
                                var attrib = floor + bldg;
                            }

                            if(item.address !== null || item.address !== 'undefined' || item.address !== 'null') {
                                var attrib = ", " + item.address;
                            }

                            if(attrib === null || attrib === ', null' || attrib === ', undefined')
                                attrib = '';

                            return $("<li></li>")
                                .data("item.autocomplete", item)
                                .append("<div>" + newText + attrib + "</div>")
                                .appendTo(ul);
                        };
                    })
                });
            },

            softkeys: function() {
                $(function() {
                    $('.softkeys').softkeys({
                        target : $('.softkeys').data('target'),
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
                                ['K','\''],
                                ['L','&#34;'],
                                ['&bsol;'],
                            ],
                            [
                                'shift',
                                ['Z','{'],
                                ['X','}'],
                                ['C','<'],
                                ['V','>'],
                                ['B','_'],
                                ['N','?'],
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
                })
            },

            buildSchedule: function (data) {
                let tempSchedule = [];
                const currentSchedule = eval(data.tenant_details['schedules']);
                    if (currentSchedule) {
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
                }
                this.tenantSchedule = tempSchedule;
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                this.search_page = false;
                this.show_tenant = true;
                $('.notification').hide();
                this.buildSchedule(this.tenant_details);
                this.updateViewCount(this.tenant_details.id);
                this.$root.$emit('callMutateLocation','tenant');

                setTimeout(() => {
                    this.$root.$emit('callSetTranslation');
                }, 100);

                $(".btn-like-display").removeClass('disabled-response');
                $(".btn-heart").removeClass('fas').addClass('far');
                $(".products-container").animate({ scrollTop: 0 });
                $('#myProduct').hide();
            },

            updateLikeCount: function(id) {
                if($(".btn-heart").hasClass("fas")) {
                    this.tenant_details.like_count = parseInt(this.tenant_details.like_count) - 1;
                    $(".btn-heart").removeClass('fas').addClass('far');
                }
                else {
                    this.tenant_details.like_count = parseInt(this.tenant_details.like_count) + 1;
                    $(".btn-heart").removeClass('far').addClass('fas');
                }

                let params = {
                    id: this.tenant_details.id,
                    like_count: this.tenant_details.like_count
                }


                $.post( "/api/v1/like-count", params ,function(response) {});
            },

            updateViewCount: function(id) {
                this.tenant_details.view_count = parseInt(this.tenant_details.view_count) + 1;

                let params = {
                    id: this.tenant_details.id,
                    view_count: this.tenant_details.view_count
                }
                $.post( "/api/v1/view-count", params ,function(response) {});
            },


            showSchedule: function() {
                $("#modal-schedule-search").show();
            },

            showProduct: function(product,type) {
                this.product_image = product;
                $("#myProductSearch").show();
                $('.set-width').removeClass('banner-size');
                $('.set-width').removeClass('product-size');
                if (type == 'banner'){
                    $('.set-width').addClass('banner-size');
                }
                if (type == 'product'){
                    $('.set-width').addClass('product-size');
                } 
            },

            resetPage: function(content_language) {
                $('#code').val("");
                $('.notification').hide();
                if ($('.ABC').html() === "ABC") {
                    $('.ABC').trigger('click');
                }
                this.resetCarousel
                this.search_page = true;
                this.search_results = false;
                this.show_tenant = false;
                this.page_title = 'Search';
            },

            callHomeMethod: function(){
                this.$root.$emit('callAboutParent','search')
            },

            findStore: function(value) {
                this.$root.$emit('callFindStore',value,'search')
			},

            resetCarousel: function() {
                $(".control-prev-sp").hide();
                $(".control-next-sp").hide();
                if(this.current_tenant_list_count>0){
                    $(".control-next-sp").show();
                }
            },

        },

        mounted() {
            this.softkeys();
            $(function() {
                $(".control-prev-sp").hide();
                $(".control-next-sp").hide();

                $('#searchCarousel').on('slid.bs.carousel', function () {
                    if($(this).find('.active').hasClass('last-item')){
                        $(".control-next-sp").hide();
                        $(".control-prev-sp").show();
                    }else if($(this).find('.active').hasClass('first-item')){
                        $(".control-prev-sp").hide();
                        $(".control-next-sp").show();
                    }else{
                        $(".control-prev-sp").show();
                        $(".control-next-sp").show();
                    }
                });

                $("#myProductSearch,#modal-schedule-search").on('click',function(){
                    $("#myProductSearch,#modal-schedule-search").hide();
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

                $(".softkeys__btn").on('mousedown',function(){
                
                }).on('mouseup',function(){
                    $('#code').trigger('keydown');
                    $('.notification').hide();
                }).on('touchend',function(){
                    $('.notification').hide();
                    $('#code').trigger('keydown');
                });
                
                $(".btn-close-trailer").on('click',function(){
                    $("#myProductSearch").hide();
                });

                $(".map-search-modal").on('click', function() {
                    $(".map-search-modal").hide();
                })

                $(".ABC").on('click',function(){
                    if ($(this).html() === "ABC") {
                        $(this).html("#+=");
                        $(".hidden-on-alt").show();
                    } else {
                        $(this).html("ABC");
                        $(".hidden-on-alt").hide();
                    }
                }).on('touchstart',function(){
                    if ($(this).html() === "ABC") {
                        $(this).html("#+=");
                        $(".hidden-on-alt").show();
                    } else {
                        $(this).html("ABC");
                        $(".hidden-on-alt").hide();
                    }
                });

                $(".enter-key").on('click',function(event){
                    $(".search-box-button").trigger('click');
                });

                var touchduration = 150; 
                var timerInterval;

                function timer(interval) {

                    interval--;

                    if (interval >= 0) {
                        timerInterval = setTimeout(function() {
                            timer(interval);
                        });
                    } else {
                        $("#code").val('');
                    }

                }

                $(".delete-key").on('touchstart',function(){
                    timer(touchduration);
                }).on('touchend',function(){
                    clearTimeout(timerInterval);
                });
            })
        },
    };

</script>
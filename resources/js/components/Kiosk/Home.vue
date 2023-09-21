<template>
    <div style="width: 100%;">
        <div v-bind:class="(site_orientation == 'Portrait') ? 'router-page-portrait': 'router-page'" v-show="homeIsShown">
            <div v-if="site_name == 'Parqal'" class="row">
                <template v-if="site_orientation == 'Portrait'">
                    <template v-if="home_category">
                        <div class="col-md-12 text-center" style="z-index:3;">
                            <div class="datetime-holder mt-4 pt-3">
                                <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-md-6" style="z-index:3;">
                            <div class="datetime-holder text-left ml-5 mt-2 pt-3">
                                <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                            </div>                
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="mr-5 mb-5 mt-3" style="z-index:3;">
                                <div v-bind:class="[(site_orientation == 'Portrait' ? 'btn-custom btn-custom-portrait ': 'btn btn-custom'), page_title.length > 20 ? 'f-size-28' : '']">{{ page_title }}</div>
                            </div>
                        </div>
                    </template>
                </template>
                <template v-else>
                    <div class="col-md-5" style="z-index:3;">
                        <div v-if="child_category || supplementals || alphabetical || show_tenant" class="datetime-holder mt-2 mb-5 mr-5 ml-5 pt-3">
                            <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                        </div>                
                    </div>
                    <div class="col-md-7 text-right" style="z-index:3;">
                        <div v-if="home_category" class="datetime-holder mt-2 mb-5 mr-5 pt-3">
                            <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                        </div>
                        <div v-else class="mr-5 mb-5 mt-3" style="z-index:3;">
                            <div v-bind:class="[page_title.length > 20 ? 'f-size-28' : '']" class="btn btn-custom">{{ page_title }}</div>
                        </div>
                    </div>
                </template>
            </div>
            <div v-else class="row">
                <div class="col-md-6" style="z-index:3;">
                    <div id="page-title" v-if="page_title != 'Categories'" class="translateme" :data-en="page_title">{{ page_title }}</div>
                </div>
                <div class="col-md-6 text-right" style="z-index:3;">
                    <img :src="site_logo" class="logo-holder" @click="aboutButton('home')">
                </div>
            </div>

            <!-- MAIN CATEGORY -->
            <div v-show="home_category">
                <div v-if="site_name == 'Parqal'">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'mt-150 mb-55 ml-120': 'mt-25 mb-55 ml-150'" class="row">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'col-md-12': 'col-md-10 offset-md-2 mt-5 ml-150'">
                            <div v-for="(category, index) in main_category" v-bind:class="(site_orientation == 'Portrait') ? category.class_name +' category-portrait hc-button': category.class_name+' hc-button'" @click="showChildren(category);">
                                <div class="main-category-holder">
                                    <img :src="category.kiosk_image_primary_path" width="100%">
                                </div>
                                <div id="hc-button1" class="hc-button-align translateme resize mt-4" :data-en="category.label">{{ category.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="row mt-15 mb-55">
                        <div class="col-md-12 main-home-title text-center translateme" data-en="Search your favorite stores">Search your favorite stores</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div v-for="(category, index) in main_category" :class="[category.class_name, 'hc-button']" @click="showChildren(category);">
                                    <img :src="category.kiosk_image_primary_path">
                                <div id="hc-button1" class="hc-button-align translateme resize" :data-en="category.label">{{ category.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUB CATEGORY -->
            <div v-show="child_category">
                <div v-if="site_name == 'Parqal'">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'row col-md-12 mt-120 mb-41 ml-0': 'row col-md-10 offset-md-1 mt-70 mb-41'" id="child-category-holder">
                        
                    </div>
                </div>
                <div v-else>
                    <div v-if="child_category_count< 7" class="row mt-120 mb-41">
                        <div class="col-md-12 home-title-sub text-center translateme" :data-en="current_category.label">{{current_category.label}}</div>
                    </div>
                    <div v-else-if="child_category_count< 11" class="row mb-27 mt-18">
                        <div class="col-md-12 home-title-sub text-center translateme" :data-en="current_category.label">{{current_category.label}}</div>
                    </div>
                    <div v-else class="row mb-27">
                        <div class="col-md-12 home-title-sub text-center translateme" :data-en="current_category.label">{{current_category.label}}</div>
                    </div>
                    <div class="row col-md-6 offset-md-3 mb-3 mw-51p">
                        <div v-for="subcategory in current_category.children" class="col-12 col-sm-6 text-left mt-3 p-0-5" @click="getTenantsByCategory(subcategory)">			
                            <div class="c-button ml-0">						
                                <img class="tenant-category" :src="subcategory.kiosk_image_primary_path" style="max-width:100%">
                                <div class="c-button-align c-button-color2" v-bind:class="'c-category-'+ current_category.category_id"><p class="translateme" :data-en="subcategory.label">{{subcategory.label}}</p></div>                        
                            </div>					
                        </div>
                    </div>
                </div>                
            </div>

            <!-- SUPPLEMENTALS -->
            <div v-show="supplementals">
                <div v-if="site_name == 'Parqal'">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'row col-md-12 mt-120 mb-41 ml-0': 'row col-md-10 offset-md-1 mt-70 mb-41'" id="child-supplementals-holder">
                        
                    </div>

                    <div v-show="no_record_found" class="row mb-23 mt-5">
                        <div class="col-md-12 home-title text-center" style="font-size: 40px;">
                            <div><span class="translateme" data-en="Stay tuned for more!">Stay tuned for more!</span>
                            </div>            
                        </div>
                    </div>
                    <img v-show="current_supplementals_count < 0" src="images/empty-box.png" class="no-record-found mt-3" style="top: 50%;">
                </div>
                <div v-else>
                    <div class="row mb-27">
                        <div class="col-md-12 home-title-sub text-center translateme" :data-en="current_category.label">{{ current_category.label}}</div>
                    </div>
                    <div class="row col-md-10 offset-md-1 mb-3 w-1152">
                        <div id="alphabeticalCarousel" class="carousel slide" data-ride="false" data-interval="false" data-wrap="false" v-show="current_supplementals_count >= 0">

                            <!-- Indicators -->
                            <ul class="carousel-indicators z-1" v-show="current_supplementals_count>1">
                                <li data-target="#alphabeticalCarousel" v-for="(supplementals, index) in current_supplementals.children" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active first-item':''"><span></span></li>                 
                            </ul>

                            <!-- The slideshow -->
                            <div class="carousel-inner carousel-mh-626">
                                <div class="carousel-item" v-for="(supplementals, index) in current_supplementals.children" v-bind:class = "[index == 0 ? 'first-item active':'', index == current_supplementals_count? 'last-item':'']">
                                    <div class="row mb-3">
                                        <div v-for="supplemental in supplementals" class="col-12 col-sm-4 text-left mt-3" @click="helper.saveLogs(supplemental, 'Categories'); getTenantsBySupplementals(supplemental)">			
                                            <div class="c-button">						
                                                <img class="tenant-category" :src="supplemental.kiosk_image_primary_path" style="max-width:100%">
                                                <div class="c-button-align c-button-color2" v-bind:class="'c-category-'+ current_category.category_id"><p class="translateme" :data-en="supplemental.label">{{supplemental.label}}</p></div>                        
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
                        
                        <div v-show="no_record_found" class="row mb-23 mt-5">
                            <div class="col-md-12 home-title text-center">
                                <div><span class="translateme" data-en="Stay tuned for more!">Stay tuned for more!</span>
                                </div>            
                            </div>
                        </div>
                        <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" v-bind:class="(site_orientation == 'Portrait') ? 'no-record-found-portrait': ''" class="no-record-found">

                    </div>
                </div>

            </div>

            <!-- ALPHABETICAL -->
            <div v-show="alphabetical">
                <div :class="(category_top_banner) ? 'row mt-14 mb-18' : 'row mb-27' ">
                    <div v-show="site_name != 'Parqal'" class="col-md-12 home-title-sub text-center">
                        <div v-show="!category_top_banner" class="translateme" :data-en="category_label">{{ category_label }}</div>
                        <div class="hts-strip" v-show="category_top_banner">
                            <img class="tenant-category-strip" :src="category_top_banner" style="width:100%">
                            <div class="hts-strip-align hts-strip-color2 translateme" :data-en="category_label">{{ category_label }}</div>                                        
                        </div>
                    </div>
                </div>
                <div class="row col-md-12 ml-2">
                    <div id="supplementalCarousel" v-bind:class="(site_orientation == 'Portrait') ? 'carousel-portrait': 'supplementalCarousel-ladscape'" class="carousel slide" data-ride="false" data-interval="false" data-wrap="false" v-show="!no_record_found">

                        <!-- Indicators -->
                        <ul class="carousel-indicators carousel-indicators-a z-1" v-show="tenant_list_count>0">
                            <li data-target="#supplementalCarousel" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'first-item active':''"><span></span></li>                    
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner" :class="(category_top_banner) ? 'carousel-mh-596' : 'carousel-mh-626' ">
                            <div v-bind:class="[(site_orientation == 'Portrait') ? 'carousel-item': 'carousel-item tenant-store-carousel', index == 0 ? 'first-item active':'', index == tenant_list_count? 'last-item':'']" v-for="(tenants, index) in tenant_list" :data-index="index">
                                <div v-bind:class="(site_orientation == 'Portrait') ? 'row mb-3 mt-60': 'row mb-3'">
                                    <div v-for="tenant in tenants" v-bind:class="tenant_list[0].length <= 2 ? 'col-sm-6 text-left mt-3' : 'col-sm-4 text-left mt-3'">
                                        <div v-if="site_name == 'Parqal'">
                                            <div v-bind:class="[(site_orientation == 'Portrait' ? 'tenant-store tenant-store-portrait text-center': 'tenant-store text-center ml-3 mb-3'), (tenant_list[0].length <= 2 && site_orientation == 'Portrait') ? 'tenant-store-custom-portrait': '', (tenant_list[0].length <= 2 && site_orientation == 'Landscape') ? 'tenant-store-custom': '']" @click="helper.saveLogs(tenant, 'Categories'); (tenant.is_subscriber==1) ? showTenant(tenant, current_page) : findStore(tenant,current_page);">
                                                <div v-bind:class="tenant_list[0].length <= 2 ? 'image-holder-custom h-100' : 'image-holder h-100'">
                                                    <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                                </div>
                                                <div v-bind:class="tenant_list[0].length <= 2 ? 'text-left pta-2-custom brand-name' : 'text-left pta-2 brand-name'">
                                                    <div v-bind:class="tenant_list[0].length <= 2 ? 'f-size-25' : ''" class="shop_name" :parent-index="index">{{ tenant.brand_name }}</div>
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
                                            <div class="tenant-store bg-white text-center box-shadowed ml-3" @click="helper.saveLogs(tenant, 'Categories'); showTenant(tenant, current_page)">
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
                        <a v-bind:class="(site_orientation == 'Portrait') ? 'carousel-control-prev carousel-control-prev-portrait control-prev-a p-l-z-a': 'carousel-control-prev control-prev-a p-l-z-a'" href="#supplementalCarousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a v-bind:class="(site_orientation == 'Portrait') ? 'carousel-control-next carousel-control-next-portrait control-next-a n-l-z-a': 'carousel-control-next control-next-a n-l-z-a'" href="#supplementalCarousel" data-slide="next" v-show="tenant_list_count>=1">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>

                </div>
                <div v-if="site_name == 'Parqal'" v-show="no_record_found" class="row mb-23 mt-5">
                    <div class="col-md-12 home-title text-center">
                        <div><span class="translateme" data-en="Stay tuned for more!">Stay tuned for more!</span>
                        </div>            
                    </div>
                </div>
                <img v-if="site_name == 'Parqal'" v-show="no_record_found" src="images/empty-box.png" v-bind:class="(site_orientation == 'Portrait') ? 'no-record-found-portrait': ''" class="no-record-found">
                <img v-else v-show="no_record_found" src="images/stick-around-for-future-deals.png" class="no-record-found">
            </div>

            <!-- TENANT -->
            <div v-show="show_tenant">
                <div v-if="site_name == 'Parqal'" class="row">
                    <template v-if="site_orientation == 'Portrait'">
                        <div class="col-sm-12 mt-2" style="z-index: 3;">
                            <div class="row tenant-details-portrait ml-4 mr-4">
                                <div class="col-sm-3 text-center">
                                    <div class="my-auto pt-3">
                                        <img class="tenant-details-logo" :src="tenant_details.brand_logo">
                                        <div class="tenant-details-views-portrait"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;<span>{{ tenant_details.view_count }}</span>&nbsp;<span class="translateme" data-en="Views">Views</span></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center p-3">
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
                                <div class="col-sm-3 text-center">
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
                        <div v-if="tenant_details.is_subscriber && tenant_details.products.length > 0">
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
                                    <div class="mb-24"><img src="assets/images/social-media-fb.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.facebook }}</div>
                                    <div class="mb-24"><img src="assets/images/social-media-twitter.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.twitter }}</div>
                                    <div class="mb-24"><img src="assets/images/social-media-ig.svg" class="mr-2" width="40">{{ tenant_details.tenant_details.instagram }}</div>
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
                                    <button class="btn btn-prestige-rounded btn-prestige-color w-100 btn-direction-shop resize-get-direction translateme" data-en="Get Directions" @click="findStore(tenant_details,current_page)">Get Directions</button>
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

            <!-- TABS -->
            <div v-bind:class="(site_orientation == 'Portrait') ? 'tabs-container tabs-container-portrait ': 'tabs-container'" v-show="tabs_container">
                <div v-if="site_name == 'Parqal'">
                    <div v-show="!no_record_found" v-if="child_category || supplementals" v-bind:class="(site_orientation == 'Portrait') ? 'swipe-to-see-more-portrait': 'swipe-to-see-more'">
                        <img src="images/swipe.png" >
                        <p style="margin-top: 18px;">SWIPE TO SEE MORE</p>
                    </div>

                    <div class="btn-group dropup dropdown-menu-right float-right mr-5">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            View stores by
                        </button>
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'dropdown-menu dropdown-menu-portrait': 'dropdown-menu'">
                            <a class="dropdown-item" id="alphabetical-tab" data-link="Alphabetical" @click="getTenants(current_category, 'Alphabetical');">Alphabetical</a>
                            <a class="dropdown-item" id="category-tab" data-link="Category" @click="showCategories()">Category</a>
                            <a class="dropdown-item" id="supplementals-tab" data-link="Supplementals" @click="showSupplementals(current_category.supplemental.name);">
                                <span id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental" :data-en="current_category.supplemental.name">{{ current_category.supplemental.name }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="tabs">
                        <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme" data-en="View stores by">View stores by</span>: </span>
                        <div class="tabs-item store-tabs-item tab-item-selected" id="category-tab" data-link="Category" @click="showCategories()">
                            <div>
                                <a class="translateme tenant-category" data-en="Category">Categories</a>
                            </div>
                        </div>
                        <div class="tabs-item store-tabs-item" id="alphabetical-tab" data-link="Alphabetical" @click="getTenants(current_category);">
                            <div>
                                <a class="translateme tenant-alphabet" data-en="Alphabetical">Alphabetical</a>
                            </div>
                        </div>
                        <div class="tabs-item store-tabs-item" id="supplementals-tab" data-link="Supplementals" @click="showSupplementals(current_category.supplemental.name);">
                            <div>
                                <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental" :data-en="current_category.supplemental.name">{{ current_category.supplemental.name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-a" v-if="site_name != 'Parqal'">
                    <ol class="navigation-letters" v-show="navigationLetters">
                        <li v-for="letter in navigation_letters" :class="(available_letters.includes(letter) ? '' : 'disabled')" @click="moveTo(letter)">{{letter}}</li>
                    </ol>
                </div>
            </div>
            <div v-show="!home_category" v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
            <div v-show="!home_category" v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>

            <!-- MODAL -->
            <div class="custom-modal p-l-490" id="myProduct">
                <div v-bind:class="(site_orientation == 'Portrait') ? 'custom-modal-position-portrait': ''" class="custom-modal-position set-width">                    
                    <img class="my-product-image" :src="product_image">
                    <div class="text-center parqal-color">
                        <span class="btn-close-modal"><i class="far fa-times-circle"></i></span>
                    </div> 
                </div>                 
            </div>

            <div class="custom-modal p-l-490" id="modal-schedule">
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
                        <span class="btn-close-modal"><i class="far fa-times-circle"></i></span>
                    </div>       
                </div>
            </div>
        </div>
        <about-page v-show="aboutIsShown" ref="callAbout"></about-page>
        <search-page v-show="searchIsShown" ref="callSearch"></search-page>
        <map-page v-show="mapIsShown" ref="callMap"></map-page>
        <promos-page v-show="promosIsShown" ref="callPromo"></promos-page>
        <cinema-page v-show="cinemaIsShown" ref="callCinema"></cinema-page>
        <landmark-page v-show="landmarkIsShown" ref="callLandmark"></landmark-page>
        <events-page v-show="eventsIsShown" ref="callEvents"></events-page>
        <div class="row">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'pt-2': 'pt-2 ml-4 ml-5'" class="col-md-12 text-center bg-color">
                <div v-bind:style="(site_orientation == 'Portrait') ? '': 'margin-left: -75px;'">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'h-button h-button-portrait widget-button home-button-portrait logs active': 'h-button widget-button home-button logs active'" data-link='Home' @click="homeButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Home">Home</div>
                    </div>
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'h-button h-button-portrait widget-button search-button-portrait logs': 'h-button widget-button search-button logs'" data-link='Search' @click="searchButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Search">Search</div>
                    </div>
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'h-button h-button-portrait widget-button map-button-portrait logs': 'h-button widget-button map-button logs'" data-link='Map' @click="mapButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Map">Map</div>    
                    </div>
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'h-button h-button-portrait widget-button landmark-button-portrait logs': 'h-button widget-button landmark-button logs'" data-link='Landmarks' @click="landmarksButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Landmarks">Landmarks</div>    
                    </div>
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'h-button h-button-portrait widget-button events-button-portrait logs': 'h-button widget-button events-button logs'" data-link='Events' @click="eventsButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Events">Events</div>    
                    </div>
                    <div v-show="site_name != 'Parqal'" class="h-button widget-button promos-button logs" data-link='Promos' @click="promosButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Promos">Promos</div>
                    </div>
                    <div v-show="site_name != 'Parqal'" class="h-button widget-button cinema-button logs" data-link='Cinema' @click="cinemaButton">
                        <div v-bind:class="(site_orientation == 'Portrait') ? 'button-text-align button-text-align-portrait translateme': 'button-text-align translateme'" data-en="Cinema">Cinema</div>
                    </div>
                </div>
            </div>
        </div>
        <assitant-page ref="callAssist"></assitant-page>
        <div v-bind:style="(site_orientation == 'Portrait') ? 'm-0': 'margin-left: 6px;'" class="row col-12 text-center">
            <span v-bind:class="(site_orientation == 'Portrait') ? 'client-website-holder-portrait': ''" class="client-website-holder" @click="aboutButton('home')">{{site_website}}</span>
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
    import landmark from './Landmark.vue';
    import events from './Events.vue';
    import carousel from 'vue-owl-carousel';

	export default {
        name: "Home",
        components: {
            'about-page': about,
            'search-page': search,
            'map-page': map,
            'promos-page': promos,
            'cinema-page': cinema,
            'assitant-page': assitant,
            'landmark-page': landmark,
            'events-page': events,
            carousel,
        },
        data() {
            return {
                current_date: "",
                current_time: "",
                main_category: [],
                tenant_list: [],
                site_name: '',
                site_logo: '',
                site_orientation: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Categories',
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
                landmarkIsShown: false,
                eventsIsShown: false,
                days: {'Mon':"Monday",'Tue':"Tuesday",'Wed':"Wednesday",'Thu':"Thursday",'Fri':"Friday",'Sat':"Saturday",'Sun':"Sunday"},
                tenantSchedule :[],
                trigger_from: '',
                current_language: 'ENGLISH',
                current_language_set: 'english',
                languages: {'한국어':'korean','日本人':'japanese','中文':'chinese','FILIPINO':'filipino','ENGLISH':'english'},
                translations: '',
                translated: '',
                translations_by_language: '',
                current_nav_dot: '',
                current_page: 'home',
                current_location: '',
                see_details_font_size: '',
                multilanguage: '',
                site_website: '',
                current_supplemental_title: '',
                called_from: '',
                current_subcategory: '',
            };
        },

        created() {
            this.getSite();
            this.getCategories();
            this.generateLetters();
            this.getTranslation();
            setInterval(this.getDateNow, 1000);
        },

        watch: {
            current_language_set(value) {
                if (value == 'korean') {
                    $('.resize-see-details').css({'font-size':'19px'});
                } 
                if (value == 'japanese') {
                    $('.resize-see-details').css({'font-size':'19px'});
                } 
                if (value == 'chinese') {
                    $('.resize-see-details').css({'font-size':'16px'});
                } 
                if (value == 'filipino') {
                    $('.resize-see-details').css({'font-size':'12px'});
                } 
                if (value == 'english') {
                    $('.resize-see-details').css({'font-size':'21px'});
                }
            },
            current_nav_dot(value) {
                if (value > 13) {                
                    let text_indent = (this.tenant_list_count - 14) * -55;
                    $(".carousel-indicators-a").css({'text-indent':text_indent+'px'});
                } else if (value < 13) {     
                    $(".carousel-indicators-a").css({'text-indent':'0'});
                }
            }
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

            aboutButton: function (event) {
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = true;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;

                this.$refs.callAbout.setPage(this.current_page);
                this.$refs.callAssist.filterAssist('about',this.current_language_set);
            },

            homeButton: function (event) {
                if($('#myProduct, #myProductSearch, #myevent').is(':visible')) {
                    $('#myProduct, #myProductSearch, #myevent').hide();
                }

                this.current_page = 'home';
                this.no_record_found = false;
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
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.page_title = 'Categories';
                this.$refs.callAssist.filterAssist('tenantcategory',this.current_language_set);
            },

            searchButton: function (event) {
                if($('#myProduct, #myProductSearch, #myevent').is(':visible')) {
                    $('#myProduct, #myProductSearch, #myevent').hide();
                }

                this.tenant_details = '';
                this.current_page = 'search';
                this.homeIsShown = false;
                this.show_tenant = false;
                this.searchIsShown = true;        
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.$refs.callSearch.getSuggestionList();
                this.$refs.callSearch.resetPage(this.current_language_set);
                this.$refs.callAssist.filterAssist('searchbox',this.current_language_set);
            },

            mapButton: function (event) {
                if($('#myProduct, #myProductSearch, #myevent').is(':visible')) {
                    $('#myProduct, #myProductSearch, #myevent').hide();
                }

                this.current_page = 'map';
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = true;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.$refs.callMap.resetPage();
                this.$refs.callAssist.filterAssist('map',this.current_language_set);
            },

            promosButton: function (event) {
                this.current_page = 'promo';
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = true;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.$refs.callPromo.resetPage();
                this.$refs.callAssist.filterAssist('promo',this.current_language_set);
            },

            cinemaButton: function (event) {
                this.current_page = 'cinema';
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = true;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.$refs.callCinema.resetPage(this.current_language_set);
            },

            eventsButton: function (event) {
                if($('#myProduct, #myProductSearch, #myevent').is(':visible')) {
                    $('#myProduct, #myProductSearch, #myevent').hide();
                }
                
                this.current_page = 'event';
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = true;
                this.$refs.callPromo.resetPage();               
                this.$refs.callAssist.filterAssist('event',this.current_language_set);
            },

            landmarksButton: function (event) {
                if($('#myProduct, #myProductSearch, #myevent').is(':visible')) {
                    $('#myProduct, #myProductSearch, #myevent').hide();
                }

                this.current_page = 'landmark';
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = true;
                this.eventsIsShown = false;
                this.$refs.callLandmark.resetPage();
                this.$refs.callAssist.filterAssist('landmark',this.current_language_set);
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
                if (event == 'event') {
                    this.eventsIsShown = true;
                }
                if (event == 'landmark') {
                    this.landmarkIsShown = true;
                }
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
                
                this.$refs.callPromo.updatePromoList(params);
            },

            updateViewCount: function(id) {
                this.tenant_details.view_count = parseInt(this.tenant_details.view_count) + 1;

                let params = {
                    id: this.tenant_details.id,
                    view_count: this.tenant_details.view_count
                }
                $.post( "/api/v1/view-count", params ,function(response) {});
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
                .then(response => {
                    this.site_name = response.data.data.name
                    this.site_logo = response.data.data.site_logo
                    this.site_website = response.data.data.details.website
                    this.site_orientation = response.data.data.site_orientation
                    this.multilanguage = (response.data.data.details.multilanguage == '1') ? true : false

                    if (this.multilanguage === false){         
                        $(".multilanguage").addClass('disabled-response');
                    }
                });
			},

            getCategories: function() {
				axios.get('/api/v1/categories')
                .then(response =>{
                    this.main_category = response.data.data
                });
			},

            getTenants: function(category, name = null) {
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

                if(this.site_name == 'Parqal' && name) {
                    this.page_title = name;
                }

                this.initializeSwipe();
                this.resetCarousel();

                setTimeout(() => {
                    this.filterLetterNavigator();
                }, 500);

                this.TitleCasePerWord();

                setTimeout(() => {
                    this.setTranslation(this.current_language_set);
                }, 100);

                this.$refs.callAssist.filterAssist('alphabet',this.current_language_set);
            },

            getTenantsByCategory: function(category) {
                this.current_subcategory = category;
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;
                this.helper.saveLogs(category, 'Categories');
                this.navigationLetters = false;
                this.previous_page = 'Sub Category';

                if(this.site_name == 'Parqal') {
                    this.page_title = (category.label) ? category.label : category.category_name;
                }

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

                    setTimeout(() => {
                        this.setTranslation(this.current_language_set);
                    }, 100);
                });       
            },

            getTenantsBySupplementals: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = category.kiosk_image_top_path;
                this.previous_page = 'Supplementals';

                if(this.site_name == 'Parqal') {
                    this.page_title = category.category_name;
                }

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

                    setTimeout(() => {
                        this.setTranslation(this.current_language_set);
                    }, 100);

                    this.$refs.callAssist.filterAssist('supplemental',this.current_language_set);
                });               
            },

            showCategories: function() {
                this.no_record_found = false;
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
                this.navigationLetters = false;
                if(this.site_name == 'Parqal') {
                    this.page_title = 'Categories';
                }
                
                // this.initializeSwipe();
                // this.resetCarousel();

                this.$refs.callAssist.filterAssist('tenantcategory',this.current_language_set);

                $(".navigation-letters li").removeClass('active'); 

                var obj = this;

                $(document).ready(function(){
                    $('#child-category-holder').html('<div id="sub-category-carousel" class="owl-carousel"></div>');
                    $.each(obj.current_category.children, function(index, sub_category) {
                        var ïtem_holder_class = 'ïtem-holder';
                        var rounded_container_class = 'rounded-container';
                        var category_name_holder_class = 'category-name-holder';
                        var font_size_class = 'f-size-45';

                        if(obj.site_orientation == 'Portrait') {
                            ïtem_holder_class = 'ïtem-holder-portrait';
                            rounded_container_class = 'rounded-container-portrait';
                            category_name_holder_class = 'category-name-holder-portrait';
                        }

                        if(sub_category.label.length >= 10) {
                            font_size_class = 'f-size-35';
                        }

                        if(sub_category.label.length >= 20) {
                            font_size_class = 'f-size-28';
                        }

                        var html = '<div class="'+ïtem_holder_class+'">';
                            html += '<div class="'+rounded_container_class+'" id="category_'+sub_category.id+'">';
                            html += '<img src="'+sub_category.kiosk_image_primary_path+'" style="max-width:100%">';
                            html += '<div class="'+category_name_holder_class+' '+font_size_class+'"><p class="translateme" data-en="'+sub_category.label+'">'+sub_category.label+'</p></div>';
                            html += '</div';
                            html += '</div';
                        
                        $("#sub-category-carousel").append(html);
                        $("#category_"+sub_category.id).click(function(){
                            obj.getTenantsByCategory(sub_category);
                        });
                    });

                    var loop = true;
                    if(obj.current_category.children.length < 3) {
                        loop = false;
                    }

                    var owl = $("#sub-category-carousel");
                    owl.owlCarousel({
                        center: true,
                        items:3,
                        loop:loop,
                        margin:0,
                    });
                });
            },

            showSupplementals: function(name = null) {
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = false;
                this.supplementals = true;
                this.show_tenant = false; 
                this.navigationLetters = false;
                if(this.site_name == 'Parqal') {
                    this.page_title = name;
                    this.current_supplemental_title = name;
                }

                if(this.current_supplementals.children.length <= 0) {
                    this.no_record_found = true;
                }

                // this.initializeSwipe();     

                this.$refs.callAssist.filterAssist('supplementalcat',this.current_language_set);

                $(".navigation-letters li").removeClass('active'); 

                setTimeout(() => {
                    this.setTranslation(this.current_language_set);
                }, 100);

                var obj = this;

                $(document).ready(function(){
                    $('#child-supplementals-holder').html('<div id="supplementals-carousel" class="owl-carousel"></div>');
                    $.each(obj.current_supplementals.children, function(index, supplementals) {
                        $.each(supplementals, function(index, supplemental){
                            var ïtem_holder_class = 'ïtem-holder';
                            var rounded_container_class = 'rounded-container';
                            var category_name_holder_class = 'category-name-holder';
                            var font_size_class = 'f-size-35';
                            if(obj.site_orientation == 'Portrait') {
                                ïtem_holder_class = 'ïtem-holder-portrait';
                                rounded_container_class = 'rounded-container-portrait';
                                category_name_holder_class = 'category-name-holder-portrait';
                            }

                            if(supplemental.label.length >= 10 && supplemental.label.indexOf(' ') === -1) {
                                font_size_class = 'f-size-35';
                            }

                            if(supplemental.label.length >= 12 && supplemental.label.indexOf(' ') === -1) {
                                font_size_class = 'f-size-25';
                            }

                            var html = '<div class="'+ïtem_holder_class+'">';
                                html += '<div class="'+rounded_container_class+'" id="supplemental_'+supplemental.id+'">';
                                html += '<img src="'+supplemental.kiosk_image_primary_path+'" style="max-width:100%">';
                                html += '<div class="'+category_name_holder_class+' '+font_size_class+'"><p class="translateme" data-en="'+supplemental.label+'">'+supplemental.label+'</p></div>';
                                html += '</div';
                                html += '</div';
                            
                            $("#supplementals-carousel").append(html);
                            $("#supplemental_"+supplemental.id).click(function(){
                                obj.getTenantsBySupplementals(supplemental);
                            });
                        })                     
                    });


                    var loop = true;
                    if(obj.current_supplementals.children.length < 3) {
                        loop = false;
                    }

                    var owl = $("#supplementals-carousel");
                    owl.owlCarousel({
                        center: true,
                        items:3,
                        loop:loop,
                        margin:0,
                    });
                });
            },     

            showChildren: function(category) {
                $('#category-tab').trigger('click');
                this.previous_page = 'Sub Category';
                this.current_category = category;
                this.child_category_count = category.children.length;
                this.current_supplementals = category.supplemental;
                if(this.current_supplementals) {
                    this.current_supplementals_count = this.current_supplementals.children.length - 1;
                }
                this.page_title = 'Categories';
                this.category_label = category.label;
                this.home_category = false;
                this.child_category = true;                
                this.alphabetical = false;
                this.supplementals = false;
                this.show_tenant = false;
                this.helper.saveLogs({category_id: category.id}, 'Categories');

                // this.initializeSwipe();
                this.tabs_container = true;

                setTimeout(() => {
                    this.setTranslation(this.current_language_set);
                }, 200);

                var obj = this;

                $(document).ready(function(){
                    $('#child-category-holder').html('<div id="sub-category-carousel" class="owl-carousel"></div>');
                    $.each(obj.current_category.children, function(index, sub_category) {
                        var ïtem_holder_class = 'ïtem-holder';
                        var rounded_container_class = 'rounded-container';
                        var category_name_holder_class = 'category-name-holder';
                        var font_size_class = 'f-size-45';

                        if(obj.site_orientation == 'Portrait') {
                            ïtem_holder_class = 'ïtem-holder-portrait';
                            rounded_container_class = 'rounded-container-portrait';
                            category_name_holder_class = 'category-name-holder-portrait';
                        }

                        if(sub_category.label.length >= 10) {
                            font_size_class = 'f-size-35';
                        }

                        if(sub_category.label.length > 20) {
                            font_size_class = 'f-size-28';
                        }

                        var html = '<div class="'+ïtem_holder_class+'">';
                            html += '<div class="'+rounded_container_class+' category_'+sub_category.id+'">';
                            html += '<img src="'+sub_category.kiosk_image_primary_path+'" style="max-width:100%">';
                            html += '<div class="'+category_name_holder_class+' '+font_size_class+'"><p class="translateme" data-en="'+sub_category.label+'">'+sub_category.label+'</p></div>';
                            html += '</div';
                            html += '</div';
                        
                        $("#sub-category-carousel").append(html);
                        $(".category_"+sub_category.id).click(function(){
                            obj.getTenantsByCategory(sub_category);
                        });
                    });

                    var loop = true;
                    if(obj.current_category.children.length < 3) {
                        loop = false;
                    }

                    var owl = $("#sub-category-carousel");
                    owl.owlCarousel({
                        center: true,
                        items:3,
                        loop:loop,
                        margin:0,
                    });
                });
                
            },

            goBack: function() {
                if($('#myProduct, #myevent').is(':visible')) {
                    $('#myProduct, #myevent').hide();
                    return false;
                }   
                
                console.log('test');
                console.log(this.called_from);
                console.log(this.previous_page);                
                console.log(this.alphabetical);

                if(this.called_from == 'bannerAd' && this.show_tenant == true) {
                    this.home_category = true;
                    this.show_tenant = false;
                }
                if(!this.called_from && this.show_tenant == true) {
                    this.page_title = 'Store List';
                    this.alphabetical = true;
                    this.show_tenant = false;
                    this.tabs_container = true;
                }
                if(this.called_from == 'home' && this.show_tenant == true) {
                    this.page_title = 'Store List';
                    this.alphabetical = true;
                    this.show_tenant = false;
                    this.tabs_container = true;
                }
                else if(this.child_category == true) {
                    this.page_title = 'Categories';
                    this.home_category = true;
                    this.child_category = false;
                    this.tabs_container = false;
                    this.isAlphabeticalClicked = false;
                }
                else if(this.previous_page == 'Supplementals' && this.alphabetical == true) {
                    this.page_title = this.current_supplemental_title;
                    this.supplementals = true;
                    this.alphabetical = false;
                } 
                else if(this.previous_page == 'Alphabetical' && this.alphabetical == true) {
                    this.page_title = 'Categories';
                    this.child_category = true;
                    this.alphabetical = false;
                    $('#category-tab').trigger('click');
                    this.isAlphabeticalClicked = false;
                } 
                else if(this.previous_page == 'Sub Category' && this.alphabetical == true) {
                    this.page_title = 'Categories';
                    this.child_category = true;
                    this.alphabetical = false;
                    $('#category-tab').trigger('click');
                } 
                else if(this.alphabetical == true) {
                    this.page_title = 'Categories';
                    this.home_category = true;
                    this.alphabetical = false;
                }  
                else if(this.previous_page == 'Alphabetical' && this.supplementals == true) {
                    this.page_title = 'Categories';
                    this.home_category = false;
                    this.supplementals = false;
                    $('#alphabetical-tab').trigger('click');
                    this.previous_page = 'Sub Category';
                }       
                else if(this.supplementals == true) {
                    this.page_title = 'Categories';
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
                else if(this.called_from == 'home' && this.tenant_details.is_subscriber && this.show_tenant == true) {
                    this.home_category = false;
                    this.show_tenant = false;
                    this.alphabetical = true;
                }

                $('#myProduct').hide();
                setTimeout(() => {
                    this.setTranslation(this.current_language_set); 
                }, 100);        
            },

            showTenant: function(tenant, current_page) {
                this.tenant_details = '';
                this.called_from = current_page;
                this.page_title = 'Store Page';   
                this.tenant_details = tenant;
                this.alphabetical = false;
                this.show_tenant = true;
                this.tabs_container = false;
                this.buildSchedule(this.tenant_details);
                this.updateViewCount(this.tenant_details.id);

                setTimeout(() => {
                    this.setTranslation(this.current_language_set);
                }, 100);

                this.$refs.callAssist.filterAssist('tenant',this.current_language_set);

                $(".btn-like-display").removeClass('disabled-response');
                $(".btn-heart").removeClass('fas').addClass('far');
                $(".products-container").animate({ scrollTop: 0 });
                $('#myProduct').hide();
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
                }else {
                    this.translations_by_language = this.translations;
                }
                
                var vm = this;

                    $(".translateme").each(function(){
                        let data_en = $(this).attr('data-en');

                        vm.translated = vm.translations_by_language.find(option => option.english == data_en);

                        if (vm.translated != null) {                         
                            if (language != 'english') {
                                $(this).html(vm.translated.translated);
                            } else {
                                $(this).html(vm.translated.english);
                            }
                        }

                    });

                this.$refs.callAssist.filterAssist(null,vm.current_language_set);
            
                $('.resize').autoSizr(24);  
                $('.resize-get-direction').autoSizr(21);
                // $('.resize-see-details').autoSizr(21);

                if (this.current_language_set == 'japanese') {
                    $(".resize-share").css({'width':'124'});
                    $('.resize-share').autoSizr(21);
                }else {
                    $(".resize-share").css({'width':'64'});
                    $('.resize-share').autoSizr(21);
                }

                $('.map-tenant-option .multiselect__single').html($('.directions-to').html().concat(" ", $('.destination').html()));
			},

            findStore: function(tenant_details,current_page) {
                this.current_page = current_page;
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.mapIsShown = true;
				this.$refs.callMap.find_store(tenant_details,this.current_page);
                this.$refs.callAssist.filterAssist('maptenant',this.current_language_set);
			},

            updateMainCategory: function(params) {
                this.main_category.forEach(object => {
                    object.alphabetical.forEach(array => {
                        array.forEach(object => {
                            if (object.id == params.id) {
                                object.like_count = params.like_count
                            }
                        });
                    });
                });
			},

        },

        mounted() {
            this.$root.$on('showTenantMap', (tenant, called_from) => {
                this.homeIsShown = false;
                this.searchIsShown = false;
                this.mapIsShown = true;
                this.promosIsShown = false;
                this.cinemaIsShown = false;
                this.aboutIsShown = false;
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.$refs.callMap.with_disability = 0;
                console.log(tenant);
                console.log(called_from);
                
                this.findStore(tenant,called_from);                
            });

            this.$root.$on('showTenantSubscriber', (tenant, called_from) => {
                this.current_page = 'home';
                this.no_record_found = false;
                this.home_category = false;
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
                this.landmarkIsShown = false;
                this.eventsIsShown = false;
                this.called_from = called_from;
                this.$refs.callMap.with_disability = 0;
                this.showTenant(tenant, called_from);          
            });

            var obj = this;

            $(function() {
                // --start Call Parent Method from Child Component
                obj.$root.$on('MainCategories', () => {
                    obj.homeButton();
                });

                obj.$root.$on('callSearch', (value) => {
                    obj.searchButton(value);
                });

                obj.$root.$on('callAboutParent', (value) => {
                    obj.aboutButton(value);
                    obj.trigger_from = value
                });

                obj.$root.$on('callAboutFrom', (value) => {
                    obj.returnFromAbout(value);
                });

                obj.$root.$on('callFindStore', (value,current_page) => {
                    obj.findStore(value,current_page);
                });

                obj.$root.$on('callSetTranslation', () => {
                    obj.setTranslation(obj.current_language_set);
                });

                obj.$root.$on('callUpdateMainCategory', (params) => {
                    obj.updateMainCategory(params);
                });

                obj.$root.$on('callShowTenant', (params, called_from) => {
                    obj.home_category = false;
                    obj.child_category = false;
                    obj.tabs_container = false;
                    obj.isAlphabeticalClicked = false;
                    obj.supplementals = false;
                    obj.homeIsShown = true;
                    obj.mapIsShown = false;
                    obj.searchIsShown = false;
                    obj.promosIsShown = false;
                    obj.cinemaIsShown = false;
                    obj.aboutIsShown = false;
                    obj.landmarkIsShown = false;
                    obj.eventsIsShown = false;
                    obj.page_title = params.category_name;

                    console.log(called_from);

                    if(params.is_subscriber) {
                        console.log('1');
                        obj.show_tenant = true;
                        obj.home_category = false;
                        obj.alphabetical = false;
                    }
                    else if(obj.tenant_list.length > 0 && called_from == 'home') {
                        console.log('2');
                        obj.tenant_details = '';
                        obj.show_tenant = false;
                        obj.home_category = false;
                        obj.alphabetical = true;
                    }
                    else if(called_from == 'bannerAd') {
                        console.log('1');
                        obj.tenant_details = '';
                        obj.show_tenant = false;
                        obj.home_category = true;
                        obj.alphabetical = false;
                    }
                    // else {
                    //     obj.tenant_details = '';
                    //     obj.show_tenant = false;
                    //     obj.home_category = true;
                    //     obj.alphabetical = false;
                    // }

                    obj.$refs.callAssist.filterAssist('tenantcategory',obj.current_language_set);
                });
                
                obj.$root.$on('callSearchBack', (value, called_from) => {
                    obj.tenant_details = '';
                    obj.current_page = 'search';
                    obj.homeIsShown = false;
                    obj.show_tenant = false;
                    obj.searchIsShown = true;        
                    obj.mapIsShown = false;
                    obj.promosIsShown = false;
                    obj.cinemaIsShown = false;
                    obj.aboutIsShown = false;
                    obj.landmarkIsShown = false;
                    obj.eventsIsShown = false;

                    if(obj.$refs.callSearch.search_results == false && obj.$refs.callSearch.tenant_details.is_subscriber) {
                        obj.show_tenant = true;
                    }
                    else if(obj.$refs.callSearch.search_results == false && !obj.$refs.callSearch.tenant_details.is_subscriber) {
                        obj.$refs.callSearch.tenant_details = '';
                        obj.$refs.callSearch.search_page = true;
                        obj.$refs.callSearch.resetPage();
                    }
                    obj.$refs.callSearch.getSuggestionList();
                    obj.$refs.callAssist.filterAssist('searchbox',this.current_language_set);
                });
                //-- end

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
                    obj.current_nav_dot = $(this).find('.active').attr('data-slide-to');
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
                    obj.current_nav_dot = $(this).find('.active').attr('data-slide-to');
                });
            });            
        },
    };
    
</script>
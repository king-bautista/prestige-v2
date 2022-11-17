<template>
    <div style="width: 100%;">
        <div class="row">
            <div class="col-md-6">
                <div id="page-title">{{ page_title }}</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder">
            </div>
        </div>
        <!-- MAIN CATEGORY -->
        <div v-show="home_category">
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    Search your favorite stores
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-for="(category, index) in main_category" :class="[category.class_name, 'hc-button']" @click="showChildren(category)">
                        <img :src="category.kiosk_image_primary_path">
                        <div id="hc-button1" class="hc-button-align">{{ category.label }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MAIN CATEGORY -->

        <!-- SUB CATEGORY -->
        <div v-show="child_category">
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div class="row col-md-6 offset-md-3 mb-3">
                <div v-for="category in current_category.children" class="col-12 col-sm-6 text-left mt-3" @click="getTenantsByCategory(category)">			
                    <div class="c-button">						
                        <img class="tenant-category" :src="category.kiosk_image_primary_path" style="max-width:100%">
                        <div class="c-button-align c-button-color2 translateme">{{category.label}}</div>                        
                    </div>					
                </div>
            </div>
        </div>
        <!-- END SUB CATEGORY -->

        <!-- SUPPLEMENTALS -->
        <div v-show="supplementals">
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    {{ current_category.label}}
                </div>
            </div>
            <div class="row col-md-10 offset-md-1 mb-3">
                <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" v-for="(supplementals, index) in current_supplementals.children" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(supplementals, index) in current_supplementals.children" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="supplemental in supplementals" class="col-12 col-sm-4 text-left mt-3" @click="getTenantsBySupplementals(supplemental)">			
                                    <div class="c-button">						
                                        <img class="tenant-category" :src="supplemental.kiosk_image_primary_path" style="max-width:100%">
                                        <div class="c-button-align c-button-color2 translateme">{{supplemental.label}}</div>                        
                                    </div>					
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#myCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myCarousel" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>                
                </div>               
            </div>
        </div>
        <!-- END SUPPLEMENTALS -->

        <!-- ALPHABETICAL -->
        <div v-show="alphabetical">
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    <div v-if="!category_top_banner">{{ category_label }}</div>
                    <div class="hts-strip" v-if="category_top_banner">
                        <img class="tenant-category-strip" :src="category_top_banner" style="width:100%">
                        <div class="hts-strip-align hts-strip-color2 translateme">{{ category_label }}</div>                                        
                    </div>                    
                </div>
            </div>
            <div class="row col-md-10 offset-md-1">
                <div id="myTenants" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="tenant_list.length > 0">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myTenants" v-for="(tenants, index) in tenant_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(tenants, index) in tenant_list" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="tenant in tenants" class="col-12 col-sm-4 text-left mt-3">
                                    <div class="tenant-store bg-white text-center box-shadowed">
                                        <div class="image-holder h-100">
                                            <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                        </div>
                                        <div class="text-left pta-2 brand-name">
                                            <div class="shop_name">{{ tenant.brand_name }}</div>
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
                    <button class="carousel-control-prev" type="button" data-target="#myTenants" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myTenants" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: auto;">
            </div>
        </div>
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
                <div class="tabs-item store-tabs-item" @click="showSupplementals">
                    <div>
                        <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental">{{ current_category.supplemental.name }}</a>
                    </div>
                </div>
            </div>
        </div>
        <img v-show="!home_category" :src="back_button" style="z-index:999;position:absolute;top:780px;right:15px; cursor:pointer;" @click="goBack">

    </div>
</template>
<script> 
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
                alphabetical: false,
                supplementals: false,
                current_category: '',
                current_supplementals: '',
                category_label: '',
                category_top_banner: '',
                no_record_found: false,
            };
        },

        created() {
            this.getSite();
            this.getCategories();
        },

        methods: {
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
                axios.get('/api/v1/tenants/alphabetical/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                });
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
            },

            showSupplementals: function() {
                this.home_category = false;
                this.child_category = false;
                this.alphabetical = false;
                this.supplementals = true;
            },

            showChildren: function(category) {
                $('#category-tab').click();
                this.current_category = category;
                this.current_supplementals = category.supplemental;
                this.page_title = 'Store List';
                this.category_label = category.label;
                this.home_category = false;
                this.child_category = true;
                this.alphabetical = false;
                this.supplementals = false;
            },

            goBack: function() {
                this.page_title = 'Home';
                this.home_category = true;
                this.child_category = false;
                this.alphabetical = false;
                this.supplementals = false;
            },
        },

        mounted() {
            $(function() {
                $('.store-tabs-item').click(function () {
                    $('.store-tabs-item').removeClass('tab-item-selected');
                    $(this).addClass('tab-item-selected');
                });

                $(".carousel").carousel({
                    interval: false,
                    pause: true,
                    touch:true,
                });
            });
        },
    };
    
</script>
<style lang="scss" scoped>
    .carousel-control-prev {
        width: 2rem;
        height: 578px;
        border: none;
        background: url('/assets/images/Left.png') no-repeat;
        background-position: center;
    }
    .carousel-control-prev {
        left: -70px;
    }

    .carousel-control-next {
        width: 2rem;
        height: 578px;
        border: none;
        background: url('/assets/images/Right.png') no-repeat;
        background-position: center;
    }

    .carousel-control-next {
        right: -55px;
    }


</style>
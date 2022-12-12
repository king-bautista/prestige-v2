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
        <div v-if="search_page">
            <div class="row" v-if="!search_results">
                <div class="col-md-10 offset-md-1 mt-5 pt-5">
                    <form class="row form text-center" v-on:submit.prevent="onEnter">
                        <div class="input-group mb-5 mt-5" style="width: 70%; margin: auto;"> 
                            <input type="text" id="code" name="code" class="form-control input-mg serach-box">
                            <button class="btn search-box-button" type="button" @click="onEnter">Search</button>
                        </div>                    
                        <div class="softkeys" data-target="input[name='code']"></div>
                    </form>
                </div>
            </div>
            <div v-show="search_results">
                <div class="row">
                    <div class="col-md-12 home-title text-center">
                        <div>Results</div>                  
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
                                        <div class="tenant-store bg-white text-center box-shadowed ml-3" @click="showTenant(tenant)">
                                            <div class="image-holder h-100">
                                                <img :src="tenant.brand_logo" :alt="tenant.brand_name">
                                            </div>
                                            <div class="text-left pta-2 brand-name">
                                                <div class="shop_name">{{ tenant.brand_name }}</div>
                                                <div style="font-size: 0.7em;color:#2a2a2a">{{ tenant.building_name }}, {{ tenant.floor_name }}</div>
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

        <img :src="back_button" style="z-index:999;position:absolute;top:780px;right:15px; cursor:pointer;" @click="goBack">
    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                search: {
                    key_words: '',
                },
                tenant_list: [],
                suggestion_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                search_page: true,
                page_title: 'Search',
                no_record_found: false,
                search_results: false,
                tenant_details: '',
                show_tenant: false,
                product_image: '',
            };
        },

        created() {
            this.getSite();
            this.getSuggestionList();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            onEnter: function() {
                this.search.key_words = $('#code').val();
                axios.post('/api/v1/search', this.search)
				.then(response => {
                    this.tenant_list = response.data.data;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = false;         
                    }else {
                        this.search_results = true;
                    }
				})
            },

            goBack: function() {
                if(this.show_tenant == true) {
                    this.search_page = true;
                    this.search_results = true;
                    this.show_tenant = false;
                }
                else if(this.show_tenant == false && this.search_results == true) {
                    this.tenant_list = [];
                    this.search.key_words = '';
                    this.search_page = true;
                    this.search_results = false;
                    this.softkeys();
                    this.getSuggestionList();
                }
                else {
                    $('.h-button').removeClass('active');
                    $('.home-button').addClass('active');
                    this.$router.push("/").catch(()=>{});
                }
            },

            getSuggestionList: function() {
                axios.get('/api/v1/tenants/suggestion/list')
                .then(response => {
                    var suggestion_list = response.data.data;
                    $(function() {
                        $('#code').autocomplete({
                            minLength: 4,
                            source: suggestion_list,
                        });
                    })
                });
            },

            softkeys: function() {
                $(function() {
                    $('.softkeys').softkeys({
                        target : $('.softkeys').data('target'),
                        layout : [
                            [
                                ['`','~'],
                                ['1','!'],
                                ['2','@'],
                                ['3','#'],
                                ['4','$'],
                                ['5','%'],
                                ['6','^'],
                                ['7','&'],
                                ['8','*'],
                                ['9','('],
                                ['0',')'],
                                ['-', '_'],
                                ['=','+'],
                                'delete',
                            ],
                            [
                                'q','w','e','r','t','y','u','i','o','p',
                                ['[','{'],
                                [']','}']
                            ],
                            [
                                'capslock',
                                'a','s','d','f','g','h','j','k','l',
                                [';',':'],
                                ["'",'&quot;'],
                                ['\\','|']
                            ],
                            [
                                'shift',
                                'z','x','c','v','b','n','m',
                                [',','&lt;'],
                                ['.','&gt;'],
                                ['/','?'],
                                ['@'],
                                'space',
                            ]
                        ]
                    });
                })
            },

            showTenant: function(tenant) {
                this.page_title = 'Store Page';
                this.tenant_details = tenant;
                this.search_page = false;
                this.show_tenant = true;
            },

            showProduct: function(product) {
                this.product_image = product;
                $("#myProduct").show();
            },

        },

        mounted() {
            this.softkeys();
            $(function() {
                $(".softkeys__btn").on('mousedown',function(){
                
                }).on('mouseup',function(){
                    $('#code').trigger('keydown');
                });
                
                $(".btn-close-trailer").on('click',function(){
                    $("#myProduct").hide();
                });

            })
        },
    };

</script>
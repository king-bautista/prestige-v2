<template>
    <div>
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
    </div>
</template>
<script>
	export default {
        name: "MainCategories",
        data() {
            return {
                show_tenant: false,
                tenant_details: '',
                product_image: '',
            };
        },

        methods: {
            showProduct: function(product) {
                this.product_image = product;
                $("#myProduct").show();
            },
        },

        mounted() {
            $(function() {
                $(".btn-close-trailer").on('click',function(){
                    $("#myProduct").hide();
                });
            });
        },
    };
    
</script>
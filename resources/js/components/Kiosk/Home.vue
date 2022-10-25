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
        <div class="row" v-show="home_category">
            <div class="col-md-12">
                <div class="row">
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
        </div>
        <div class="row" v-show="child_category">
            <div class="col-md-6 offset-md-3">
                <div class="row">
                    <div class="col-md-12 home-title text-center">
                        {{ current_category.label}}
                    </div>
                </div>
                <div class="row mb-3">
                    <div v-for="category in current_category.children" class="col-12 col-sm-6 text-left mt-3">			
                        <div class="c-button">						
                            <img class="tenant-category" :src="category.kiosk_image_primary_path" style="max-width:100%">
                            <div class="c-button-align c-button-color2 translateme">{{category.label}}</div>                        
                        </div>					
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-show="supplementals">
            <div class="col-md-9 offset-md-2">
                <div class="row">
                    <div class="col-md-12 home-title text-center">
                        {{ current_category.label}}
                    </div>
                </div>
                <div class="row mb-3">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true">
                        <div class="carousel-inner">
                            
                            <!-- Control dots -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" v-for="(supplementals, index) in current_supplementals.children" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                    </ol>

                            <div class="carousel-item" v-for="(supplementals, index) in current_supplementals.children" v-bind:class = "(index == 0) ? 'active':''">
                                <div class="row mb-3">
                                    <div v-for="supplemental in supplementals" class="col-12 col-sm-4 text-left mt-3">			
                                        <div class="c-button">						
                                            <img class="tenant-category" :src="supplemental.kiosk_image_primary_path" style="max-width:100%">
                                            <div class="c-button-align c-button-color2 translateme">{{supplemental.label}}</div>                        
                                        </div>					
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
        <div class="tabs-container" v-show="!home_category">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">View stores by</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" id="category-tab">
                    <div>
                        <a class="translateme tenant-category" @click="showCategories">Category</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item">
                    <div>
                        <a class="translateme tenant-alphabet" >Alphabetical</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item">
                    <div>
                        <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" @click="showSupplementals" v-if="current_category.supplemental">{{ current_category.supplemental.name }}</a>
                    </div>
                </div>
            </div>
        </div>
        <img v-show="!home_category" :src="back_button" style="z-index:999;position:absolute;top:690px;right:15px; cursor:pointer;" @click="goBack">

    </div>
</template>
<script> 
	export default {
        name: "MainCategories",
        data() {
            return {
                main_category: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Home',
                home_category: true,
                child_category: false,
                alphabetical: false,
                supplementals: false,
                current_category: '',
                current_supplementals: '',
            };
        },

        created() {
            this.getCategories();
            this.getSite();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            getCategories: function() {
				axios.get('/api/v1/categories')
                .then(response => this.main_category = response.data.data);
			},

            getTenants: function() {

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
                this.current_category = category;
                this.current_supplementals = category.supplemental;
                this.page_title = 'Store List';
                $('#category-tab').click();
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

        components: {

 	   }
    };
    
    $(document).ready(function () {
        $('.store-tabs-item').click(function () {
            $('.store-tabs-item').removeClass('tab-item-selected');
            $(this).addClass('tab-item-selected');
        });

        $(".carousel").carousel({
            interval: false,
            pause: true,
            touch:true,
        });
        
        // $( ".carousel .carousel-inner" ).swipe( {
        //     swipeLeft: function ( event, direction, distance, duration, fingerCount ) {
        //         this.parent( ).carousel( 'next' );
        //     },

        //     swipeRight: function ( ) {
        //         this.parent( ).carousel( 'prev' );
        //     },

        //     threshold: 0,

        //     // tap: function(event, target) {
        //     // // get the location: in my case the target is my link
        //     //     window.location = $(this).find('.carousel-item.active a').attr('href');
        //     // },
        //     //เอา  a ออกถ้าต้องการให้ slide ที่เป็น tag a สามารถคลิกได้
        //     excludedElements:"label, button, input, select, textarea, .noSwipe"
        // } );
        
        // $('.carousel .carousel-inner').on('dragstart', 'a', function () {
        //     return false;
        // });
    });

</script>
<style lang="scss" scoped>
    .carousel-control-prev {
        width: 2rem;
        height: 674px;
        border: none;
        background: url('/assets/images/Left.png') no-repeat;
        background-position: center;
    }
    .carousel-control-prev {
        left: -70px;
    }

    .carousel-control-next {
        width: 2rem;
        height: 674px;
        border: none;
        background: url('/assets/images/Right.png') no-repeat;
        background-position: center;
    }

    .carousel-control-next {
        right: -35px;
    }


</style>
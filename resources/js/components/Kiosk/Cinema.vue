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
        <div>
            <div class="row mt-5 mb-5">
                <div class="col-md-12 home-title text-center">
                    {{ tab_title }}
                </div>
            </div>
            <div v-show="cinema_locator" class="row col-md-9 offset-md-2">
                <div id="myCinemas" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="cinema_list.length > 0">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCinemas" v-for="(cinemas, index) in cinema_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(cinemas, index) in cinema_list" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="cinema in cinemas" class="col-12 col-sm-6 text-left mt-3">
                                    <div class="cinema-store bg-white text-center box-shadowed ml-3">
                                        <div class="image-holder h-100">
                                            <img :src="cinema.brand_logo" :alt="cinema.brand_name">
                                        </div>
                                        <div class="text-left pta-2 brand-name">
                                            <div class="shop_name">{{ cinema.brand_name }}</div>
                                            <div style="font-size: 0.7em;color:#2a2a2a">{{ cinema.building_name }}, {{ cinema.floor_name }} </div>
                                            <div style="font-weight: bold;font-size: 0.7em">
                                                <span class="translateme text-success" v-if="cinema.active==1">Open</span>
                                                <span class="translateme text-success" v-if="cinema.active==0">Close</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#myCinemas" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myCinemas" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: auto;">
            </div>
        </div>
        <div class="tabs-container">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">Select to view</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" @click="tab_title='Cinema Locator'">
                    <div>
                        <a class="translateme tenant-category">Cinema</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item" @click="tab_title='Now Showing'">
                    <div>
                        <a class="translateme tenant-alphabet">Schedule</a>
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
                cinema_list: [],
                tab_title: 'Cinema Locator',
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Cinema',
                cinema_locator: true,
                cinema_schedule: false,
                no_record_found: false,
            };
        },

        created() {
            this.getSite();
            this.getCinemaList();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            goBack: function() {
            },

            getCinemaList: function() {
                axios.get('/api/v1/cinemas')
                .then(response => {
                    this.cinema_list = response.data.data;
                });
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
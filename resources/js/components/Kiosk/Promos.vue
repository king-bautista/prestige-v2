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
        <div>
            <div class="row col-md-10 offset-md-1 mt-5">
                <div id="myPromos" class="carousel slide" data-ride="carousel" data-interval="false" data-touch="true" v-if="promo_list.length > 0">
                    <div class="carousel-inner">
                        
                        <!-- Control dots -->
                        <ol class="carousel-indicators">
                            <li data-target="#myPromos" v-for="(promos, index) in promo_list" :data-slide-to="index" v-bind:class = "(index == 0) ? 'active':''"></li>
                        </ol>

                        <div class="carousel-item" v-for="(promos, index) in promo_list" v-bind:class = "(index == 0) ? 'active':''">
                            <div class="row mb-3">
                                <div v-for="promo in promos" class="col-12 col-sm-4 text-left mt-3">
                                    <div class="bg-white text-center">
                                        <a @click="showPromo(promo.image_url_path)">
                                            <img :src="promo.image_url_path" :alt="promo.name" style="width:70%; border: solid 2px; border-radius: 15px;" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#myPromos" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#myPromos" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
                <img v-show="no_record_found" src="images/stick-around-for-future-deals.png" style="margin: -6rem auto auto;">
            </div>
        </div>
        <img :src="back_button" class="back-button" @click="goBack">

        <div class="custom-modal" id="myPromo">
            <div style="position: relative;top: 40%;transform: translateY(-50%);width: 540px; left: 50%; color:transparent;">
                <div class="text-right text-white">
                    <span style="font-size:1.5em;margin-right:-10px" class="btn-close-trailer">X</span>
                </div>
                <div class="modal-content" style="border-radius:20px;">
                    <div class="modal-body">
                        <img :src="promo_image" style="width:508px;">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                promo_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Promos',
                no_record_found: false,
                promo_image: '',
            };
        },

        created() {
            this.getSite();
            this.getPromos();
        },

        methods: {
            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

			getPromos: function() {
				axios.get('/api/v1/promos')
                .then(response => {
                    this.promo_list = response.data.data
                    if(!this.promo_list.length > 0) {
                        this.no_record_found = true;         
                    }
                });
			},

            showPromo: function(promo) {
                this.promo_image = promo;
                $("#myPromo").show();
            },

            goBack: function() {
                $('.h-button').removeClass('active');
                $('.home-button').addClass('active');
                this.$router.push("/").catch(()=>{});
            },

        },

        mounted() {
            $(function() {
                $(".btn-close-trailer").on('click',function(){
                    $("#myPromo").hide();
                });
            })
        },

    };

</script>
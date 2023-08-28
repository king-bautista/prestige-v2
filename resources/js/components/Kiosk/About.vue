<template>
    <div v-bind:class="(site_orientation == 'Portrait') ? 'router-page-portrait': 'router-page'" style="width: 100%;">
        <div v-if="site_name == 'Parqal'" class="row">
            <template v-if="site_orientation == 'Portrait'">
                <div class="col-md-12 text-center">
                    <div class="datetime-holder mt-4 pt-3">
                        <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="col-md-12 text-right">
                    <div class="datetime-holder mt-2 mb-5 mr-5 ml-5 pt-3">
                        <span class="separator">{{ current_time }}</span><span class="ml-2">{{ current_date }}</span>
                    </div>
                </div>
            </template>
        </div>
        <div class="row">
            <div v-bind:class="(site_orientation == 'Portrait') ? 'pt-5 mt-5': ''" class="col-md-12 pl-5 pr-5">
                <div class="about-banner-holder">
                    <img :src="site_details.site_banner_path" style="width: 100%;">
                </div>
            </div>
        </div>
        <div class="row">
            <template v-if="site_name == 'Parqal'">
                <div class="col-md-12 pl-5 pr-5">
                    <div v-bind:class="(site_orientation == 'Portrait') ? 'mall-details-portrait': 'pt-3'" class="mall-details">
                        <pre>{{ site_details.descriptions }}</pre>
                    </div>
                    <div class="social-media-holder">
                        <div class="row">
                            <div class="col-4">
                                <div class="socmediconcontainer">
                                    <img src="assets/images/parqal-facebook.png" class="socmedicon mr-2"> {{ facebook }}
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="socmediconcontainer">
                                    <img src="assets/images/tiktok-parqal.png" class="socmedicon mr-2"> {{ twitter }}
                                </div>
                            </div>
                            <div class="col-4 text-right">
                                <div class="socmediconcontainer">
                                    <img src="assets/images/parqal-instagram.png" class="socmedicon mr-2"> {{ instagram }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </template>
            <template v-else>
                <div class="col-6 col-sm-4 mt-50">
                    <div class="text-center" style="margin: 80px 0 80px 0;">
                        <img :src="site_details.site_logo_path">
                    </div>
                    <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-facebook.svg" class="socmedicon mr-2"> {{ facebook }}</div>            
                    <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-twitter.svg" class="socmedicon mr-2"> {{ twitter }}</div>
                    <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-instagram.svg" class="socmedicon mr-2"> {{ instagram }}</div>
                    <div class="socmediconcontainer" style="font-size: 1.3em"><img src="assets/images/about-website.svg" class="socmedicon mr-2"> {{ website }}</div>
                </div>
                <div class="col-6 col-sm-8">
                    <div class="mall-details" v-html="site_details.descriptions">
                    </div>
                </div>
            </template>
        </div>

        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-button back-button-portrait ': 'back-button'" :src="back_button" @click="goBack"></div>
        <div v-bind:class="(site_orientation == 'Portrait') ? 'back-overlay back-overlay-portrait translateme': 'back-overlay translateme'" data-en="Back" @click="goBack">Back</div>

    </div>
</template>
<script> 
	export default {
        name: "AboutUs",
        data() {
            return {
                site_details: '',
                back_button: 'assets/images/English/Back.png',
                facebook: '',
                twitter: '',
                instagram: '',
                website: '',
                trigger_from: '',
                site_name: '',
                site_orientation: '',
                current_date: '',
                current_time: '',
            };
        },

        created() {
            this.getSite();
            setInterval(this.getDateNow, 1000);
        },

        methods: {
            getDateNow: function() {
                const today = new Date();
                const date = today.toLocaleString([], { day:"numeric", month:"long", year:"numeric"});
                const time = today.toLocaleString([], {hour: '2-digit', minute:'2-digit'});
                this.current_date = date;
                this.current_time = time;
            },

            getSite: function() {
				axios.get('/api/v1/site')
                .then(response => {
                    this.site_details = response.data.data
                    if(this.site_details.details) {
                        this.facebook = this.site_details.details.facebook;
                        this.twitter = this.site_details.details.twitter;
                        this.instagram = this.site_details.details.instagram;
                        this.website = this.site_details.details.website;
                        this.site_name = response.data.data.name
                        this.site_orientation = response.data.data.site_orientation
                    }
                });
			},

            setPage: function(value) {
				this.trigger_from = value
                $(".theme-bubble").addClass("theme-bubble-none");
			},

            goBack: function() {
                if (this.trigger_from) {
                    this.$root.$emit('callAboutFrom',this.trigger_from);
                    $(".theme-bubble").removeClass("theme-bubble-none");
                }                
            },

        },
    };

</script>
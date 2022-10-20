<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div id="page-title">Home</div>
            </div>
            <div class="col-md-6 text-right">
                <img :src="site_logo" class="logo-holder">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 home-title text-center">
                        Search your favorite stores
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div v-for="(category, index) in main_category" :class="[category.class_name, 'hc-button']">
                            <img :src="category.kiosk_image_primary_path">
                            <div id="hc-button1" class="hc-button-align">{{ category.name }}</div>
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
                main_category: [],
                site_logo: '',
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
        },

        components: {

 	   }
    };
    
</script>
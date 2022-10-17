<template>
    <div>
        <div class="row">
            <div class="col-md-12 home-title text-center">
                Search your favorite stores
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div v-for="(category, index) in main_category" :class="[category.css_class, 'hc-button']">
                    <img :src="category.kiosk_image_primary_path">
                    <div id="hc-button1" class="hc-button-align">{{ category.name }}</div>
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
            };
        },

        created() {
            this.getCategories();
        },

        methods: {
			getCategories: function() {
				axios.get('/api/v1/categories')
                .then(response => this.main_category = response.data.data);
			},
        },

        components: {

 	   }
    };
</script>
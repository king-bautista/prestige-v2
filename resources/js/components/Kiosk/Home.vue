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
        <div class="tabs-container" v-show="child_category">
            <div class="tabs">
                <span class="mr-4 my-auto" style="color:#2a2a2a"><span class="translateme">View stores by</span>: </span>
                <div class="tabs-item store-tabs-item tab-item-selected" id="category-tab">
                    <div>
                        <a class="translateme tenant-category" data-target="1/0">Category</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item">
                    <div>
                        <a class="translateme tenant-alphabet" data-target="1/num/0">Alphabetical</a>
                    </div>
                </div>
                <div class="tabs-item store-tabs-item">
                    <div>
                        <a class="tenant-supplementals translateme" id="tenant-supplemental-tabtext1" data-target="1" style="font-size: 1em;" v-if="current_category.supplemental">{{ current_category.supplemental.name }}</a>
                    </div>
                </div>
            </div>
        </div>
        <img :src="back_button" style="z-index:999;position:absolute;top:690px;right:15px; cursor:pointer;" @click="goBack">

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

            getSupplementals: function() {

            },

            showChildren: function(category) {
                this.current_category = category;
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
    });
</script>
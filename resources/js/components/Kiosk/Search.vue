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
            <div class="row col-md-9 offset-md-2">
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
        <img v-show="search_results" :src="back_button" style="z-index:999;position:absolute;top:690px;right:15px; cursor:pointer;" @click="goBack">
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
                page_title: 'Search',
                no_record_found: false,
                search_results: false,
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
                        this.no_record_found = true;         
                    }else {
                        this.search_results = true;
                    }
				})
            },

            goBack: function() {
                this.softkeys();
                this.getSuggestionList();
                this.search.key_words = '';
                this.tenant_list = [];
                this.search_results = false;
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
                                ['7','&amp;'],
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
        },

        mounted() {
            this.softkeys();
            $(function() {
                $(".softkeys__btn").on('mousedown',function(){
                }).on('mouseup',function(){
                    $('#code').trigger('keydown');
                });
            })
        },

        components: {
 	    }
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
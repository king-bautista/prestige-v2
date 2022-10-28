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
        <div class="row">
            <div class="col-md-10 offset-md-1 mt-5 pt-5">
                <form class="row form text-center">
                    <div class="input-group mb-5 mt-5" style="width: 70%; margin: auto;"> 
                        <input type="text" name="code" class="form-control input-mg serach-box">
                        <button class="btn search-box-button" type="button">Search</button>
                    </div>                    
                    <div class="softkeys" data-target="input[name='code']"></div>
                </form>
            </div>
        </div>
    </div>
</template>
<script> 
	export default {
        name: "Search",
        data() {
            return {
                tenant_list: [],
                site_logo: '',
                back_button: 'assets/images/English/Back.png',
                page_title: 'Search',
                no_record_found: false,
            };
        },

        created() {
            this.getSite();
        },

        methods: {
			getSite: function() {
				axios.get('/api/v1/site')
                .then(response => this.site_logo = response.data.data.site_logo);
			},

            getTenantsSearch: function(category) {
                this.no_record_found = false;
                this.tenant_list = [];
                this.category_label = category.label;
                this.category_top_banner = '';
                axios.get('/api/v1/tenants/alphabetical/'+category.id)
                .then(response => {
                    this.tenant_list = response.data.data
                    this.home_category = false;
                    this.child_category = false;
                    this.alphabetical = true;
                    this.supplementals = false;
                    if(this.tenant_list.length == 0) {
                        this.no_record_found = true;         
                    }
                });
            },

            goBack: function() {
            },
        },

        mounted() {
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

        components: {
 	   }
    };

</script>
<style lang="scss" scoped>
    img.keyboard-key, img.keyboard-del, img.keyboard-chars
    {
        transition: transform 0.25s ease;
    }
    input.keyboard-checkbox:checked ~ label > img {
        transform: scale(1.5);
    }

</style>
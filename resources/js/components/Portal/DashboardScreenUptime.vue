<template>
    <div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar">
                <div class="container-xl m-0 p-0">
                    <h3 class="card-title"><i class="fa fa-desktop" aria-hidden="true"></i> Screen Uptime</h3>
                    <form class="form-inline ml-auto input-group-sm m-0">
                        <div class="input-group m-0">
                            <select class="form-select form-select-sm me-2" v-model="filter.site_id" @change="filterChart()">
                                <option value="">Select Site</option>
                                <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                            </select>
                            <!-- <a href="/admin/reports/uptime-history">
                                <button class="btn btn-outline-primary btn-sm" type="button">View Report</button>
                            </a> -->
                        </div>
                    </form>
                </div>
            </nav>
          </div>
          <div class="card-body">
            <div class="row">
                <div :class="screen.screen_status" class="col-1 screen-holder text-center" v-for="screen in screens">
                    <i class="fa fa-desktop" aria-hidden="true"></i><br/>
                    <span>{{ screen.screen_location }}</span>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script> 
	export default {
        name: "ScrrenUptime",
        data() {
            return {
                filter: {
                    site_id: '',
                },
                current_date: new Date().toLocaleString(),
                sites: [],
                screens: [],
            }
        },

        created(){
            this.getSites();
            this.getScreenUptime();
            setInterval(() => {
                this.getScreenUptime();
            }, (60*60*100));            
        },

        methods: {
            getSites: function() {
                axios.get('/portal/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			getScreenUptime: function() {
                axios.get('/portal/reports/screen-uptime')
                .then(response => this.screens = response.data.data);
            },
        }

    };
</script> 

<template>
	<div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <h3 class="card-title">Screen Uptime</h3>
                    <form class="form-inline ml-auto input-group-sm">
                        <a href="/admin/reports/uptime-history">
                            <button class="btn btn-outline-primary btn-sm" type="button">View Report</button>
                        </a>
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
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			getScreenUptime: function() {
                axios.get('/admin/reports/screen-uptime')
                .then(response => this.screens = response.data.data);
            },
        }

    };
</script> 

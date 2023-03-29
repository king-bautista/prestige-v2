<template>
	<div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <h3 class="card-title">Kiosk Usage</h3>
                    <form class="form-inline ml-auto input-group-sm">
                        <select class="custom-select mr-2" v-model="filter.site_id" @change="filterReport()">
                            <option value="">Select Site</option>
                            <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                        </select>
                        <a href="/admin/reports/kiosk-usage">
                            <button class="btn btn-outline-primary btn-sm" type="button">View Report</button>
                        </a>
                    </form>
                </div>
            </nav>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <Table 
						:dataFields="dataFields"
						:dataUrl="dataUrl"
						:primaryKey="primaryKey"
                        :showHeader=false
						ref="dataTable">
                    </Table>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script> 
    import Table from '../Helpers/Table';
	
    export default {
        name: "Dashboard_Top_Tenant_Search",
        data() {
            return {
                filter: {
                    site_id: '',
                },
                sites: [],
                dataFields: {
            		screen_name: "Screen Name", 
                    screen_location: "Location/Details",
                    screen_count: "Usage",
					total_average: "%",
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/kiosk-usage/list",
            }
        },

        created(){
            this.getSites();
        },

        methods: {
            getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

            filterReport: function() {
				this.$refs.dataTable.filters = this.filter;
				this.$refs.dataTable.fetchData();
			},

        },

        components: {
        	Table
 	    }

    };
</script> 

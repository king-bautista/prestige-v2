<template>
	<div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <h3 class="card-title">Top Tenant Search</h3>
                    <form class="form-inline ml-auto input-group-sm">
                        <select class="custom-select mr-2" v-model="filter.site_id" @change="filterReport()">
                            <option value="">Select Site</option>
                            <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                        </select>
                        <a href="/portal/reports/top-tenant-search">
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
                        :rowPerPage=5
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
					brand_logo: {
            			name: "Brand Logo", 
            			type:"logo", 
            		},
            		brand_name: "Brand Name", 
                    main_category_name: "Category",
					category_percentage: "% Total Over Category",
					tenant_percentage: "% Total Over Tenant"
            	},
            	primaryKey: "id",
            	dataUrl: "/portal/reports/top-tenant-search/list",
            }
        },

        created(){
            this.getSites();
        },

        methods: {
            getSites: function() {
                axios.get('/portal/site/get-all')
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

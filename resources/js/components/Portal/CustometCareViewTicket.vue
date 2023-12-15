<template>
	<div>
        <div class="row">
            <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4><i class="nav-icon fas fa-chart-line"></i>&nbsp;&nbsp;Screen Usage</h4>
                        </div>
                        <div class="col-4">
                            <div class="form-group row m-0">
                                <label class="col-sm-3 mt-1"><strong>Filter By: </strong></label>
                                <div class="col-sm-9">
                                    <select class="form-select form-select-sm" v-model="filter.site_id" @change="filterReport()">
                                        <option value="">Select Site</option>
                                        <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
        name: "Reports",
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
            	dataUrl: "/portal/reports/kiosk-usage/list",
            };
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

            downloadCsv: function() {
              axios.get('/portal/reports/kiosk-usage/download-csv', {params: {filters: this.filter}})
              .then(response => {
                const link = document.createElement('a');
                link.href = response.data.data.filepath;
                link.setAttribute('download', response.data.data.filename); //or any other extension
                document.body.appendChild(link);
                link.click();
              })
            },
        },

        components: {
        	Table
 	   }
    };
</script> 

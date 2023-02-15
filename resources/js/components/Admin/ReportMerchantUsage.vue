<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
	    			<div class="card-body">
						<Table 
						:dataFields="dataFields"
						:dataUrl="dataUrl"
						:otherButtons="otherButtons"
						:primaryKey="primaryKey"
						v-on:reportModal="reportModal"
						v-on:downloadCsv="downloadCsv"
						ref="dataTable">
						</Table>
		          	</div>
		        </div>
	          </div>
	        </div>
	        <!-- /.row -->
	      </div><!-- /.container-fluid -->
	    </section>
	    <!-- /.content -->

        <!-- Batch Upload -->
		<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		      <div class="modal-content">
		      <div class="modal-header">
		          <h5 class="modal-title" id="filterModalLabel">Filter By</h5>
		          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		          </button>
		      </div>
		      <div class="modal-body">
		          <form>
		              <div class="form-group col-md-12">
                            <label>Site: <span class="text-danger">*</span></label>
                            <select class="custom-select" v-model="filter.site_id">
                                <option value="">Select Site</option>
                                <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                            </select>
		              </div>
		          </form>
		      </div>
		      <div class="modal-footer justify-content-between">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		          <button type="button" class="btn btn-primary" @click="filterReport">Filter</button>
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
					brand_logo: {
            			name: "Brand Logo", 
            			type:"logo", 
            		},
            		brand_name: "Tenant Name", 
                    category_name: "Tenant Category",
                    search_count: "Search",
                    tenant_count: "Category",
                    banner_count: "Banner Ad",
                    promo_count: "Promo Wall",
                    total_count: "Total",
					category_percentage: "% Total Over Category",
					tenant_percentage: "% Total Over Tenant"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/merchant-usage/list",
				otherButtons: {
					addNew: {
						title: 'Filter',
						v_on: 'reportModal',
						icon: '<i class="fa fa-filter" aria-hidden="true"></i> Filter',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
					download: {
						title: 'Download',
						v_on: 'downloadCsv',
						icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				}
            };
        },

        created(){
            this.getSites();
        },

        methods: {
            getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			reportModal: function() {
                this.filter.site_id = '';
              	$('#filterModal').modal('show');
            },

			filterReport: function() {
				this.$refs.dataTable.filters = this.filter;
				this.$refs.dataTable.fetchData();
				$('#filterModal').modal('hide');
			},

            downloadCsv: function() {
              axios.get('/admin/reports/merchant-usage/download-csv', {params: {filters: this.filter}})
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

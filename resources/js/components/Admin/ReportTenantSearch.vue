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
            		brand_name: "Brand Name", 
                    main_category_name: "Category",
                    tenant_count: "Tenant Count",
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/top-tenant-search/list",
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
				this.filterChart();
				$('#filterModal').modal('hide');
			},

			filterChart: function() {
				var filter = this.filter;
				$(function() {
					$.get( "/admin/reports/merchant-population/list", filter, function( data ) {
						let labels = [];
						let data_value = [];
						let randomBackgroundColor = [];
						let usedColors = new Set();

						let dynamicColors = function() {
							let r = Math.floor(Math.random() * 255);
							let g = Math.floor(Math.random() * 255);
							let b = Math.floor(Math.random() * 255);
							let color = "rgb(" + r + "," + g + "," + b + ")";

							if (!usedColors.has(color)) {
								usedColors.add(color);
								return color;
							} else {
								return dynamicColors();
							}
						};

						$.each(data.data, function(key,value) {
							labels.push(value.category_parent_name);
							data_value.push(value.tenant_count);
							randomBackgroundColor.push(dynamicColors());
						});

						var donutData = {
							labels: labels,
							datasets: [
								{
									data: data_value,
									backgroundColor : randomBackgroundColor,
								}
							]
						}

						var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
						var pieData        = donutData;
						var pieOptions     = {
							maintainAspectRatio : false,
							responsive : true,
						}

						new Chart(pieChartCanvas, {
							type: 'pie',
							data: pieData,
							options: pieOptions
						})
					});
				});
			},

            downloadCsv: function() {
				console.log(this.filter);
              axios.get('/admin/reports/merchant-population/download-csv', {params: {filters: this.filter}})
              .then(response => {
                const link = document.createElement('a');
                link.href = response.data.data.filepath;
                link.setAttribute('download', response.data.data.filename); //or any other extension
                document.body.appendChild(link);
                link.click();
              })
            },
        },

		mounted() {
           this.filterChart();
        },

        components: {
        	Table
 	   }
    };
</script> 

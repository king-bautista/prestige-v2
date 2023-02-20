<template>
	<div>
        <!-- Main content -->
	    <section class="content">
	      <div class="container-fluid">
	        <div class="row">
	          <div class="col-md-12">
	          	<div class="card">
	    			<div class="card-body">
						<div class="row">
							<div class="col-md-12">
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
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<div class="chart-responsive">
												<canvas id="stackedBarChart" style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
											</div>
											<!-- ./chart-responsive -->
										</div>
									</div>
									<!-- /.row -->
								</div>
							</div>
						</div>
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
            		page: "Merchant Usage", 
            		jan_count: "Jan", 
            		feb_count: "Feb", 
            		mar_count: "Mar", 
            		apr_count: "Apr", 
            		may_count: "May", 
            		jun_count: "Jun", 
            		jul_count: "Jul", 
            		aug_count: "Aug", 
            		sep_count: "Sep", 
            		oct_count: "Oct", 
            		nov_count: "Nov", 
            		dec_count: "Dec", 
            		total_count: "Total", 
            		ave_count: "Monthly Ave.", 
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/monthly-usage/list",
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
					var areaChartData = {
						labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
						datasets: [
							{
							label               : 'Digital Goods',
							backgroundColor     : 'rgba(60,141,188,0.9)',
							borderColor         : 'rgba(60,141,188,0.8)',
							pointRadius          : false,
							pointColor          : '#3b8bba',
							pointStrokeColor    : 'rgba(60,141,188,1)',
							pointHighlightFill  : '#fff',
							pointHighlightStroke: 'rgba(60,141,188,1)',
							data                : [28, 48, 40, 19, 86, 27, 90, 65, 59, 80, 81, 56]
							},
							{
							label               : 'Electronics',
							backgroundColor     : 'rgba(210, 214, 222, 1)',
							borderColor         : 'rgba(210, 214, 222, 1)',
							pointRadius         : false,
							pointColor          : 'rgba(210, 214, 222, 1)',
							pointStrokeColor    : '#c1c7d1',
							pointHighlightFill  : '#fff',
							pointHighlightStroke: 'rgba(220,220,220,1)',
							data                : [65, 59, 80, 81, 56, 55, 40, 28, 48, 40, 19, 86]
							},
						]
					};
						
					var barChartData = $.extend(true, {}, areaChartData);
					//---------------------
					//- STACKED BAR CHART -
					//---------------------
					var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
					var stackedBarChartData = $.extend(true, {}, barChartData)

					var stackedBarChartOptions = {
					responsive              : true,
					maintainAspectRatio     : false,
					scales: {
						xAxes: [{
						stacked: true,
						}],
						yAxes: [{
						stacked: true
						}]
					}
					}

					new Chart(stackedBarChartCanvas, {
					type: 'bar',
					data: stackedBarChartData,
					options: stackedBarChartOptions
					})
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

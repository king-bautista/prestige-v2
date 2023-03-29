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
							<div class="col-md-6">
								<Table 
                                    :dataFields="dataFields"
                                    :dataUrl="dataUrl"
                                    :otherButtons="otherButtons"
                                    :primaryKey="primaryKey"
                                    v-on:reportModal="reportModal"
                                    v-on:downloadCsv="downloadCsv"
                                    ref="dataTable">
								</Table>
                                <Table 
                                    :dataFields="responceDataFields"
                                    :dataUrl="responceDataUrl"
                                    :primaryKey="responcePrimaryKey"
                                    :showHeader=false
                                    :rowPerPage=100
                                    ref="responseDataTable">
                                </Table>
								<Table 
                                    :dataFields="otherDataFields"
                                    :dataUrl="otherDataUrl"
                                    :primaryKey="otherPrimaryKey"
                                    :showHeader=false
                                    :rowPerPage=100
                                    ref="otherDataTable">
                                </Table>
							</div>
							<div class="col-md-6">
								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<div class="chart-responsive">
												<canvas id="donutChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
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
            		helpful: "Response", 
            		count: "Count", 
                    percentage: "% Percentage Share"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/is-helpful/list",
				otherButtons: {
					download: {
						title: 'Download',
						v_on: 'downloadCsv',
						icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
                responceDataFields: {
            		reason: "Reasons for 'No'", 
            		count: "Count", 
                    percentage: "% Percentage Share"
            	},
            	responcePrimaryKey: "id",
            	responceDataUrl: "/admin/reports/is-helpful/response",
				otherDataFields: {
            		updated_at: "Date", 
            		reason_other: "List of Other reasons", 
            	},
            	otherPrimaryKey: "id",
            	otherDataUrl: "/admin/reports/is-helpful/other-response",
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
					$.get( "/admin/reports/is-helpful/list", filter, function( data ) {
						let labels = [];
						let data_value = [];
						let yes_vote = '';

						$.each(data.data, function(key,value) {
							labels.push(value.helpful);
							data_value.push(value.percentage);
							if(value.helpful == 'Yes') 
								yes_vote = value.percentage;
						});

						var donutData = {
							labels: labels,
							datasets: [
								{
									data: data_value,
									backgroundColor : ['#FE5E80', '#899AE8'],
								}
							]
						}

						var pieChartCanvas = $('#donutChart').get(0).getContext('2d')
						var pieData        = donutData;
						var pieOptions     = {
							maintainAspectRatio : false,
							responsive : true,
						}

						new Chart(pieChartCanvas, {
							type: 'doughnut',
							data: pieData,
							plugins: [{ //plugin added for this chart
                                beforeDraw: function(chart) {
                                    var width = chart.chart.width,
                                        height = chart.chart.height,
                                        ctx = chart.chart.ctx;

                                    ctx.restore();
                                    var fontSize = 2.5;
                                    ctx.font = fontSize + "em sans-serif";
                                    ctx.textBaseline = "middle";

                                    var text = yes_vote+" %",
                                        textX = Math.round((width - ctx.measureText(text).width) / 2),
                                        textY = height / 2;

                                    ctx.fillText(text, textX, textY);

                                    ctx.restore();
                                    var fontSize = 2;
                                    ctx.font = fontSize + "em sans-serif";
                                    ctx.textBaseline = "middle";

                                    ctx.fillText("voted 'Yes'", textX, textY+45);

                                    ctx.save();
                                }
                            }],
							options: pieOptions
						});
					});
				});
			},

            downloadCsv: function() {
              axios.get('/admin/reports/is-helpful/download-csv', {params: {filters: this.filter}})
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

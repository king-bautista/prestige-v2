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
								<div :style="{ 'font-size': 20 + 'px'}">Site(s): {{ site_name }}</div>
								<div :style="{ 'font-size': 16 + 'px' }">{{ date_range }}</div>
								<Table 
                                    :dataFields="dataFields"
                                    :dataUrl="dataUrl"
                                    :otherButtons="otherButtons"
                                    :primaryKey="primaryKey"
                                    v-on:reportModal="reportModal"
                                    v-on:downloadCsv="downloadCsv"
                                    ref="dataTable">
								</Table>
								<div :style="{ 'font-size': 20 + 'px'}">Site(s): {{ reason_site_name }}</div>
								<div :style="{ 'font-size': 16 + 'px' }">{{ reason_date_range }}</div>
                                <Table 
                                    :dataFields="reasonDataFields"
                                    :dataUrl="reasonDataUrl"
									:otherButtons="reasonOtherButtons"
                                    :primaryKey="reasonPrimaryKey"
									v-on:reasonReportModal="reasonReportModal"
                                    v-on:reasonDownloadCsv="reasonDownloadCsv"
                                    :rowPerPage=100
                                    ref="responseDataTable">
                                </Table>
								<div :style="{ 'font-size': 20 + 'px'}">Site(s): {{ oreason_site_name }}</div>
								<div :style="{ 'font-size': 16 + 'px' }">{{ oreason_date_range }}</div>
								<Table 
								    :dataFields="oreasonDataFields"
                                    :dataUrl="oreasonDataUrl"
									:otherButtons="oreasonOtherButtons"
                                    :primaryKey="oreasonPrimaryKey"
									v-on:oreasonReportModal="oreasonReportModal"
                                    v-on:oreasonDownloadCsv="oreasonDownloadCsv"
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
                            <select class="custom-select" v-model="filter.site_id" @change="getSiteName">
                                <option value="">Select Site</option>
                                <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                            </select>
		              </div>
					  <div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">Start Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.start_date" placeholder="YYYY/MM/DD" :config="options"
										id="date_from" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">End Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.end_date" placeholder="YYYY/MM/DD" :config="options"
										id="date_to" autocomplete="off"></date-picker>
								</div>
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

		<div class="modal fade" id="reasonFilterModal" tabindex="-1" role="dialog" aria-labelledby="reasonFilterModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		      <div class="modal-content">
		      <div class="modal-header">
		          <h5 class="modal-title" id="reasonFilterModalLabel">Filter By</h5>
		          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		          </button>
		      </div>
		      <div class="modal-body">
		          <form>
		              <div class="form-group col-md-12">
                            <label>Reason Site: <span class="text-danger">*</span></label>
                            <select class="custom-select" v-model="filter.reason_site_id" @change="getReasonSiteName">
                                <option value="">Select Reason Site</option>
                                <option v-for="reason_site in reason_sites" :value="reason_site.id"> {{ reason_site.name }}</option>
                            </select>
		              </div>
					  <div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">Start Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.reason_start_date" placeholder="YYYY/MM/DD" :config="options"
										id="reason_date_from" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">End Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.reason_end_date" placeholder="YYYY/MM/DD" :config="options"
										id="reason_date_to" autocomplete="off"></date-picker>
								</div>
							</div>
		          </form>
		      </div>
		      <div class="modal-footer justify-content-between">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		          <button type="button" class="btn btn-primary" @click="reasonFilterReport">Filter</button>
		      </div>
		      </div>
		  </div>
		</div>

		<div class="modal fade" id="oreasonFilterModal" tabindex="-1" role="dialog" aria-labelledby="oreasonFilterModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		      <div class="modal-content">
		      <div class="modal-header">
		          <h5 class="modal-title" id="oreasonFilterModalLabel">Filter By</h5>
		          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		          </button>
		      </div>
		      <div class="modal-body">
		          <form>
		              <div class="form-group col-md-12">
                            <label>Other Reason Site: <span class="text-danger">*</span></label>
                            <select class="custom-select" v-model="filter.oreason_site_id" @change="getOreasonSiteName">
                                <option value="">Select Other Reason Site</option>
                                <option v-for="oreason_site in oreason_sites" :value="oreason_site.id"> {{ oreason_site.name }}</option>
                            </select>
		              </div>
					  <div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">Start Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.oreason_start_date" placeholder="YYYY/MM/DD" :config="options"
										id="oreason_date_from" autocomplete="off"></date-picker>
								</div>
							</div>
							<div class="form-group row">
								<label for="userName" class="col-sm-4 col-form-label">End Date</label>
								<div class="col-sm-8">
									<date-picker v-model="filter.oreason_end_date" placeholder="YYYY/MM/DD" :config="options"
										id="oreason_date_to" autocomplete="off"></date-picker>
								</div>
							</div>
		          </form>
		      </div>
		      <div class="modal-footer justify-content-between">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		          <button type="button" class="btn btn-primary" @click="oreasonFilterReport">Filter</button>
		      </div>
		      </div>
		  </div>
		</div>
    </div>
</template>
<script> 
	import Table from '../Helpers/Table';
	import datePicker from 'vue-bootstrap-datetimepicker';
	// Import date picker css
	import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
	export default {
        name: "Reports",
        data() {
            return {
                filter: {
                    site_id: '',
					start_date: '',
					end_date:'',
					reason_site_id: '',
					reason_start_date: '',
					reason_end_date:'',
					oreason_site_id: '',
					oreason_start_date: '',
					oreason_end_date:'',
                },
				site_name: 'All',
				site_name_temp: 'All',
				date_range: '',
				from: '',
				to:'',
				sites: [],
				options: {
					format: 'YYYY/MM/DD',
					useCurrent: false,
				},
				dataFields: {
            		helpful: "Response", 
            		count: "Count", 
                    percentage: "% Percentage Share"
            	},
            	primaryKey: "id",
            	dataUrl: "/admin/reports/is-helpful/list",
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
				},
				reason_site_name: 'All',
				reason_site_name_temp: 'All',
				reason_date_range: '',
				reason_from: '',
				reason_to:'',
				reason_sites: [],
                
				reasonDataFields: {
            		reason: "Reasons for 'No'", 
            		count: "Count", 
                    percentage: "% Percentage Share"
            	},
            	reasonPrimaryKey: "id",
            	reasonDataUrl: "/admin/reports/is-helpful/response",
				reasonOtherButtons: {
					addNew: {
					title: 'Filter',
					v_on: 'reasonReportModal',
					icon: '<i class="fa fa-filter" aria-hidden="true"></i> Filter',
					class: 'btn btn-primary btn-sm',
					method: 'add'
					},
					download: {
						title: 'Download',
						v_on: 'reasonDownloadCsv',
						icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
				oreason_site_name: 'All',
				oreason_site_name_temp: 'All',
				oreason_date_range: '',
				oreason_from: '',
				oreason_to:'',
				oreason_sites: [],
				oreasonDataFields: {
            		updated_at: "Date", 
            	    reason_other: "List of Other reasons", 
            	},
            	oreasonPrimaryKey: "id",
            	oreasonDataUrl: "/admin/reports/is-helpful/other-response",
				oreasonOtherButtons: {
					addNew: {
					title: 'Filter',
					v_on: 'oreasonReportModal',
					icon: '<i class="fa fa-filter" aria-hidden="true"></i> Filter',
					class: 'btn btn-primary btn-sm',
					method: 'add'
					},
					download: {
						title: 'Download',
						v_on: 'oreasonDownloadCsv',
						icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
						class: 'btn btn-primary btn-sm',
						method: 'add'
					},
				},
				
				// otherDataFields: {
            	// 	updated_at: "Date", 
            	// 	reason_other: "List of Other reasons", 
            	// },
            	// otherPrimaryKey: "id",
            	// otherDataUrl: "/admin/reports/is-helpful/other-response",
            };
        },

        created(){
            this.getSites();
			this.getReasonSites();
			this.getOreasonSites();
        },

        methods: {
			getSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
            },
			
			getSiteName: function(event) {
					var option_text = event.target[event.target.selectedIndex].text; 
					this.site_name_temp = (option_text == 'Select Site' || !option_text)?'All':option_text;
			},

            reportModal: function() {
                this.filter.site_id = '';
				this.filter.start_date ='';
				this.filter.end_date ='';
				$('#filterModal').modal('show');
            },

			filterReport: function() { 
				this.site_name = (this.filter.site_id == "")? 'All': this.site_name_temp; 
				this.date_range = (this.filter.start_date == "" || this.filter.end_date == "" || this.filter.start_date == null || this.filter.end_date == null)? '' :'From: '+ this.filter.start_date +' To: '+ this.filter.end_date;
				this.filter.site_name = this.site_name; 
				this.$refs.dataTable.filters = this.filter;
				this.$refs.dataTable.fetchData();
				var filter = this.filter; 
				$('#filterModal').modal('hide'); 
			},

            downloadCsv: function() {
              this.filter.site_name = (this.filter.site_id == "")? 'All': this.site_name_temp;
				axios.get('/admin/reports/is-helpful/download-csv', { params: { filters: this.filter } })
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
            },
			
			getReasonSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.reason_sites = response.data.data);
            },
			getReasonSiteName: function(event) {
					var option_text = event.target[event.target.selectedIndex].text; 
					this.reason_site_name_temp = (option_text == 'Select Site' || !option_text)?'All':option_text;
			},
            reasonReportModal: function() {
                this.filter.reason_site_id = '';
				this.filter.reason_start_date ='';
				this.filter.reason_end_date ='';
				$('#reasonFilterModal').modal('show');
            },

			reasonFilterReport: function() { 
				this.reason_site_name = (this.filter.reason_site_id == "")? 'All': this.reason_site_name_temp; 
				this.reason_date_range = (this.filter.reason_start_date == "" || this.filter.reason_end_date == "" || this.filter.reason_start_date == null || this.filter.reason_end_date == null)? '' :'From: '+ this.filter.reason_start_date +' To: '+ this.filter.reason_end_date;
				this.filter.reason_site_name = this.reason_site_name; 
				this.$refs.responseDataTable.filters = this.filter;
				this.$refs.responseDataTable.fetchData();
				var filter = this.filter; 
				$('#reasonFilterModal').modal('hide'); 
			},
			
            reasonDownloadCsv: function() {
              this.filter.reason_site_name = (this.filter.reason_site_id == "")? 'All': this.reason_site_name_temp;
				axios.get('/admin/reports/is-helpful/response/download-csv', { params: { filters: this.filter } })
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
            },
			
			getOreasonSites: function() {
                axios.get('/admin/site/get-all')
                .then(response => this.oreason_sites = response.data.data);
            },
			getOreasonSiteName: function(event) {
					var option_text = event.target[event.target.selectedIndex].text; 
					this.oreason_site_name_temp = (option_text == 'Select Site' || !option_text)?'All':option_text;
			},
            oreasonReportModal: function() {
                this.filter.oreason_site_id = '';
				this.filter.oreason_start_date ='';
				this.filter.oreason_end_date ='';
				$('#oreasonFilterModal').modal('show');
            },

			oreasonFilterReport: function() { 
				this.oreason_site_name = (this.filter.oreason_site_id == "")? 'All': this.oreason_site_name_temp; 
				this.oreason_date_range = (this.filter.oreason_start_date == "" || this.filter.oreason_end_date == "" || this.filter.oreason_start_date == null || this.filter.oreason_end_date == null)? '' :'From: '+ this.filter.oreason_start_date +' To: '+ this.filter.oreason_end_date;
				this.filter.oreason_site_name = this.oreason_site_name; 
				this.$refs.otherDataTable.filters = this.filter;
				this.$refs.otherDataTable.fetchData();
				var filter = this.filter; 
				$('#oreasonFilterModal').modal('hide'); 
			},
			
            oreasonDownloadCsv: function() {
              this.filter.oreason_site_name = (this.filter.oreason_site_id == "")? 'All': this.oreason_site_name_temp;
				axios.get('/admin/reports/is-helpful/other-response/download-csv', { params: { filters: this.filter } })
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
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
        },

		mounted() {
           this.filterChart();
        },

        components: {
        	Table,
			datePicker
 	   }
    };
</script> 

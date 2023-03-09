<template>
	<div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <h3 class="card-title">Monthly Usage</h3>
                    <form class="form-inline ml-auto input-group-sm">
                        <select class="custom-select mr-2" v-model="filter.site_id" @change="filterChart()">
                            <option value="">Select Site</option>
                            <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                        </select>
                        <a href="/portal/reports/monthly-usage">
                            <button class="btn btn-outline-primary btn-sm" type="button">View Report</button>
                        </a>
                    </form>
                </div>
            </nav>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart-responsive">
                        <canvas id="stackedBarChart" style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script> 
	export default {
        name: "Dashboard_Merchant_Population",
        data() {
            return {
                filter: {
                    site_id: '',
                },
                sites: [],
            }
        },

        created(){
            this.getSites();
            this.filterChart();
        },

        methods: {
            getSites: function() {
                axios.get('/portal/site/get-all')
                .then(response => this.sites = response.data.data);
            },

			filterChart: function() {
				var filter = this.filter;
				$(function() {
					$.get( "/portal/reports/monthly-usage/list", filter, function( data ) {
						let datasets = [];
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
							let background_color = dynamicColors();
							datasets.push({
								label               : value.page,
								backgroundColor     : background_color,
								borderColor         : background_color,
								pointRadius         : false,
								pointColor          : '#3b8bba',
								pointStrokeColor    : background_color,
								pointHighlightFill  : '#fff',
								pointHighlightStroke: background_color,
								data                : [value.jan_count, value.feb_count, value.mar_count, value.apr_count, value.may_count, value.jun_count, value.jul_count, value.aug_count, value.sep_count, value.oct_count, value.nov_count, value.dec_count]
							});
						});

						var areaChartData = {
							labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
							datasets: datasets
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
					
				});
			},
        },


    };
</script> 

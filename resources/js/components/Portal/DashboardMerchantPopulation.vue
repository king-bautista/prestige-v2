<template>
	<div>
        <div class="card">
          <div class="card-header border-0">
            <nav class="navbar">
                <div class="container-xl m-0 p-0">
                    <h3 class="card-title"><i class="nav-icon fas fa-chart-pie"></i> Merchant Population</h3>
                    <form class="form-inline ml-auto input-group-sm m-0">
                        <div class="input-group m-0">
                            <select class="form-select form-select-sm me-2" v-model="filter.site_id" @change="filterChart()">
                                <option value="">Select Site</option>
                                <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                            </select>
                            <a href="/portal/reports/merchant-population">
                                <button class="btn btn-outline-primary btn-sm" type="button">View Report</button>
                            </a>
                        </div>
                    </form>
                </div>
            </nav>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart-responsive">
                        <canvas id="pieChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
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
                axios.get('/portal/property-details/get-all')
                .then(response => this.sites = response.data.data);
            },

			filterChart: function() {
                var filter = this.filter;
				$(function() {
					$.get( "/portal/reports/merchant-population/list", filter, function( data ) {
						let labels = [];
						let data_value = [];
						// let randomBackgroundColor = [];
						// let usedColors = new Set();

						// let dynamicColors = function() {
						// 	let r = Math.floor(Math.random() * 255);
						// 	let g = Math.floor(Math.random() * 255);
						// 	let b = Math.floor(Math.random() * 255);
						// 	let color = "rgb(" + r + "," + g + "," + b + ")";

						// 	if (!usedColors.has(color)) {
						// 		usedColors.add(color);
						// 		return color;
						// 	} else {
						// 		return dynamicColors();
						// 	}
						// };

						if(data.data.length > 0) {
                            $.each(data.data, function(key,value) {
                                labels.push(value.category_parent_name);
                                data_value.push(value.tenant_count);
                                //randomBackgroundColor.push(dynamicColors());
                            });
                        }
                        else {
                            labels = ['Empty']
                            data_value = [1];
                            //randomBackgroundColor = ['#d2d6de'];
                        }
                        
						var donutData = {
							labels: labels,
							datasets: [
								{
									data: data_value,
									backgroundColor : ['#FE5E80', '#899AE8', '#353535', '#d6ddea', '#a59fa2'],
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
        },


    };
</script> 

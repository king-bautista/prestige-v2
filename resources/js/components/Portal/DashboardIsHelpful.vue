<template>
	<div>
        <div class="card">
            <div class="card-header border-0">
                <nav class="navbar">
                    <div class="container-xl m-0 p-0">
                        <h3 class="card-title"><i class="nav-icon fas fa-question-circle"></i> Is this Helpful?</h3>
                    </div>
                </nav>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart-responsive">
                        <canvas id="donutChart" style="min-height: 250px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script> 
	export default {
        name: "Dashboard_Is_Helpful",
        data() {
            return {
                filter: {
                    site_id: '',
                },
                sites: [],
            }
        },

        created(){
            this.filterChart();
        },

        methods: {
			filterChart: function() {
				var filter = this.filter;
				$(function() {
					$.get( "/portal/reports/is-helpful/list", filter, function( data ) {
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

						var myChart = new Chart(pieChartCanvas, {
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


    };
</script> 

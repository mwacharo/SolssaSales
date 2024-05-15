<template>
  <div>
    <div ref="chart"></div>
  </div>
</template>

<script>
import ApexCharts from 'apexcharts';
import axios from 'axios';

export default {
  mounted() {
    this.fetchChartData();
  },
  methods: {
    fetchChartData() {
      axios.get('/api/v1/dealsAnnually')
        .then(response => {
          const chartData = response.data;

          const options = {
            series: [
              {
                name: 'In Progress',
                data: chartData.series[0]
              },
              {
                name: 'Won',
                data: chartData.series[1]
              },
              {
                name: 'Lost',
                data: chartData.series[2]
              }
            ],
            chart: {
              type: 'bar',
              height: 350
            },
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
            },
            xaxis: {
              categories: chartData.xaxis.categories
            },
            yaxis: {
              title: {
                text: 'Count'
              }
            },
            fill: {
              opacity: 1
            },
            tooltip: {
              y: {
                formatter: function (val) {
                  return val;
                }

              }
            }
          };

          this.chart = new ApexCharts(this.$refs.chart, options);
          this.chart.render();
        })
        .catch(error => {
          console.error('Error fetching chart data:', error);
        });
    }
  }
};
</script>

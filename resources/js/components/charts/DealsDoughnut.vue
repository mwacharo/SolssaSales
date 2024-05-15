<template>
  <div>
    <div ref="doughnutChart"></div>
  </div>
</template>

<script>
import ApexCharts from 'apexcharts';
import axios from 'axios';

export default {
  data() {
    return {
      dealStatus: []
    };
  },
  mounted() {
    this.fetchDealStatusData();
  },
  methods: {
    fetchDealStatusData() {
      axios.get('/api/v1/dealStatusCounts')
        .then(response => {
          this.dealStatus = response.data;
          this.initDoughnutChart();
        })
        .catch(error => {
          console.error('Error fetching deal status data:', error);
        });
    },
    initDoughnutChart() {
      const chartOptions = {
        chart: {
          type: 'donut'
        },
        series: this.dealStatus.map(status => status.value),
        labels: this.dealStatus.map(status => status.status),
        dataLabels: {
          enabled: false
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      };

      this.chart = new ApexCharts(this.$refs.doughnutChart, chartOptions);
      this.chart.render();
    }
  }
};
</script>

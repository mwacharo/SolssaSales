<template>
  <div>
    <div ref="chart"></div>
  </div>
</template>

<script>
import ApexCharts from 'apexcharts';

export default {
  data() {
    return {
      previousYearDeals: 0,
      currentYearDeals: 0,
      growthRate: 0
    };
  },
  mounted() {
    // Fetch data for previous and current year
    this.fetchDeals();
  },
  methods: {
    fetchDeals() {
      // Assume you have methods to fetch data for both years from API or other source
      // For demo purpose, let's assume you set the data manually
      this.previousYearDeals = 1000; // Replace with fetched data
      this.currentYearDeals = 1500;  // Replace with fetched data

      // Calculate growth rate
      this.calculateGrowthRate();

      // Initialize chart after data is fetched
      this.initChart();
    },
    calculateGrowthRate() {
      const growth = this.currentYearDeals - this.previousYearDeals;
      this.growthRate = ((growth / this.previousYearDeals) * 100).toFixed(2);
    },
    initChart() {
      const options = {
        series: [this.growthRate],
        chart: {
          height: 350,
          type: 'radialBar',
          offsetY: -10
        },
        plotOptions: {
          radialBar: {
            startAngle: -135,
            endAngle: 135,
            dataLabels: {
              name: {
                fontSize: '16px',
                color: undefined,
                offsetY: 120
              },
              value: {
                offsetY: 76,
                fontSize: '22px',
                color: undefined,
                formatter: function (val) {
                  return val + "%";
                }
              }
            }
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.15,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
          }
        },
        stroke: {
          dashArray: 4
        },
        labels: ['Growth Rate']
      };

      this.chart = new ApexCharts(this.$refs.chart, options);
      this.chart.render();
    }
  }
};
</script>

<template>
  <canvas :id="chartId"></canvas>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'CpiView',
  mounted() {
    this.setupChart();
  },
  data() {
    return {
      chartId: 'cpi-timeline-chart',
    };
  },
  computed: {
    ...mapGetters('cpi', {
      cpiList: 'getList',
    }),
  },
  methods: {
    setupChart() {
      let cpiData = this.cpiList.map(item => ({
        x: item.date,
        y: item.value,
      }));

      const data = {
        datasets: [{
          data: cpiData,
          backgroundColor: 'red',
        }]
      };

      const config = {
        type: 'line',
        data,
        options: {
          indexAxis: 'x',
          scales: {
            x: {
              type: 'time',
              time: {
                unit: 'year',
              },
            },
            y: {
              beginAtZero: false,
            }
          },
          plugins: {
            legend: {
              display: false,
            },
          }
        },
      };

      new Chart(
        document.getElementById(this.chartId),
        config
      );
    },
  },
};
</script>

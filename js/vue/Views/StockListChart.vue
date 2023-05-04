<template>
<div class="stock-list-chart-component">
  <canvas id="stock-list-chart"></canvas>
</div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'StockListChart',
  computed: {
    ...mapGetters('stock', {
      stocks: 'getList',
    }),
  },
  data() {
    return {
      labels: [],
      dateRanges: [],
      minDate: null,
      colors: [
        'rgba(222, 26, 104, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(0, 0, 0, 1)'
      ],
      itemColors: [],
    };
  },
  mounted() {
    this.stocks.forEach((stock, index) => {
      this.labels.push(stock.name);

      this.dateRanges.push([stock.ipoDate, stock.delistingDate ? stock.delistingDate : luxon.DateTime.now().toFormat('yyyy-MM-dd')]);

      if(this.minDate === null || stock.ipoDate < this.minDate) {
        this.minDate = stock.ipoDate;
      }

      this.itemColors.push(this.colors[index % this.colors.length]);
    });

    const data = {
      labels: this.labels,
      datasets: [{
        data: this.dateRanges,
        backgroundColor: this.itemColors,
        barPercentage: 0.1,
      }]
    };

    const config = {
      type: 'bar',
      data,
      options: {
        indexAxis: 'y',
        scales: {
          x: {
            min: this.minDate,
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
      document.getElementById('stock-list-chart'),
      config
    );
  },
};
</script>

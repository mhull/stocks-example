<template>
<div class="stock-price-chart-component">
  <div class="stock-price-chart-controls">
    <div class="form-field-container">
      <label for="number-of-days">Number of days:</label>
      <input id="number-of-days" type="number" v-model="numberOfDays">
    </div>
    <div class="form-field-container">
      <input type="submit" value="Go" @click="clickNumberOfDays">
    </div>
  </div>

  <div class="cpi-chart-container">
    <canvas :id="cpiChartId"></canvas>
  </div>
  <div class="closing-price-chart-container">
    <canvas :id="closingPriceChartId"></canvas>
  </div>
  <div class="price-velocity-chart-container">
    <canvas :id="priceVelocityChartId"></canvas>
  </div>
  <div class="volume-chart-container">
    <canvas :id="volumeChartId"></canvas>
  </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex';

import StockPriceList from "stocks/models/StockPrice/StockPriceList";
import StockPriceVelocity from "stocks/models/StockPriceVelocity";

import HasStockPriceSma from "stocks/vue/Mixins/Stock/HasStockPriceSma";

export default {
  name: 'StockPriceChart',
  mixins: [ HasStockPriceSma ],
  props: {
    stock: {
      type: Object,
      default: () => ({}),
    },
  },
  mounted() {
    this.setupChart();
  },
  data() {
    return {
      numberOfDays: null,
      cpiChart: null,
      priceChart: null,
      priceVelocityChart: null,
      volumeChart: null,
    };
  },
  computed: {
    ...mapGetters('cpi', {
      cpiList: 'getList',
    }),
    ...mapGetters('stockPrice', {
      getStockPrice: 'getStockPrice',
    }),
    ...mapGetters('stockPriceVelocity', {
      getStockPriceVelocity: 'getStockPriceVelocity',
    }),
    routeStockId() {
      return parseInt(this.$route.params.stockId) || 0;
    },
    stockPrice() {
      return this.getStockPrice(this.stock.id) || new StockPriceList();
    },
    stockPriceVelocity() {
      return this.getStockPriceVelocity(this.stock.id) || new StockPriceVelocity();
    },
    cpiChartId() {
      return 'cpi-chart';
    },
    closingPriceChartId() {
      return `stock-price-chart-${this.stock.id}`;
    },
    priceVelocityChartId() {
      return `stock-price-velocity-chart-${this.stock.id}`;
    },
    volumeChartId() {
      return `stock-volume-chart-${this.stock.id}`;
    },
  },
  methods: {
    setupChart() {
      this.destroyCharts();

      let closingPrices = [];
      let adjustedClosingPrices = [];

      let minDate = null;
      let maxDate = null;

      let countItems = 0;

      this.stockPrice.items.forEach((priceItem, index) => {
        countItems++;
        if(this.numberOfDays && countItems > this.numberOfDays) {
          return;
        }

        closingPrices.push({x: priceItem.date, y: priceItem.close});
        adjustedClosingPrices.push({x: priceItem.date, y: priceItem.adjustedClose});

        if(!minDate || priceItem.date < minDate) {
          minDate = priceItem.date;
        }

        if(!maxDate || maxDate < priceItem.date) {
          maxDate = priceItem.date;
        }
      });

      let cpiData = [];
      let cpiMinDate = null;
      let cpiMaxDate = null;
      let cpiCurrentValue = null;

      this.cpiList.forEach(cpiItem => {
        if(cpiItem.date >= minDate ||
          (
            cpiItem.date.substr(0,4) === minDate.substr(0,4) &&
            cpiItem.date.substr(5,2) === minDate.substr(5,2)
          )
        ) {
          cpiData.push({
            x: cpiItem.date,
            y: cpiItem.value,
          });

          if(!cpiMinDate || cpiItem.date < cpiMinDate ) {
            cpiMinDate = cpiItem.date;
          }

          if(!cpiMaxDate || cpiMaxDate < cpiItem.date) {
            cpiMaxDate = cpiItem.date;
            cpiCurrentValue = cpiItem.value;
          }
        }
      });

      if(cpiData.length === 0) {
        cpiData.push({
          x: this.cpiList[0].date,
          y: this.cpiList[0].value,
        });
      }

      cpiCurrentValue = parseFloat(cpiCurrentValue);

      const currentDate = new Date();
      const currentYear = currentDate.getFullYear();
      const currentMonth = currentDate.getMonth();

      let cpiAdjustedClosingPrices = [];
      closingPrices.forEach(closingItem => {
        const cpiCompareDate = closingItem.x.replace(/-\d\d$/, '-01');
        let cpiItem = cpiData.find(possibleCpiItem => {
          return possibleCpiItem.x === cpiCompareDate;
        });

        if(!cpiItem &&
          (
            closingItem.x.substr(0, 4) === currentYear.toString() ||
            parseInt(closingItem.x.substr(0, 4)) + 1 === currentYear
          ) &&
          (
            (parseInt(closingItem.x.substr(5, 2)) - 1) === currentMonth ||
            (parseInt(closingItem.x.substr(5, 2)) - 1) === (currentMonth - 1) % 12
          )
        ) {
          cpiItem = cpiData[0];
        }

        const price = parseFloat(closingItem.y);
        const cpi = parseFloat(cpiItem.y);

        const cpiAdjust = (cpiCurrentValue / cpi);

        const cpiAdjustedPrice = price * cpiAdjust;

        cpiAdjustedClosingPrices.push({
          x: closingItem.x,
          y: cpiAdjustedPrice,
        });
      });

      let sma100Data = [];
      let sma100StdUpData = [];
      let sma100StdDownData = [];

      let countSma100Items = 0;

      this.stockPriceSma100.items.forEach(sma100Item => {
        countSma100Items++;

        if(this.numberOfDays && countSma100Items > this.numberOfDays) {
          return;
        }

        sma100Data.push({
          x: sma100Item.date,
          y: sma100Item.mean,
        });
        sma100StdUpData.push({
          x: sma100Item.date,
          y: parseFloat(sma100Item.mean) + parseFloat(sma100Item.stdDev),
        });
        sma100StdDownData.push({
          x: sma100Item.date,
          y: parseFloat(sma100Item.mean) - parseFloat(sma100Item.stdDev),
        });
      });

      let sma10Data = [];
      let sma10StdUpData = [];
      let sma10StdDownData = [];

      let countSma10Items = 0;

      this.stockPriceSma10.items.forEach(sma10Item => {
        countSma10Items++;

        if(this.numberOfDays && countSma10Items > this.numberOfDays) {
          return;
        }

        sma10Data.push({
          x: sma10Item.date,
          y: sma10Item.mean,
        });
        sma10StdUpData.push({
          x: sma10Item.date,
          y: parseFloat(sma10Item.mean) + parseFloat(sma10Item.stdDev),
        });
        sma10StdDownData.push({
          x: sma10Item.date,
          y: parseFloat(sma10Item.mean) - parseFloat(sma10Item.stdDev),
        });
      });

      let volumes = [];
      countItems = 0;

      this.stockPrice.items.forEach((priceItem, index) => {
        countItems++;
        if(this.numberOfDays && countItems > this.numberOfDays) {
          return;
        }

        volumes.push({x: priceItem.date, y: priceItem.volume});

        if(!minDate || priceItem.date < minDate) {
          minDate = priceItem.date;
        }

        if(!maxDate || maxDate < priceItem.date) {
          maxDate = priceItem.date;
        }
      });

      let priceVelocities = [];
      countItems = 0;

      this.stockPriceVelocity.items.forEach((velocityItem, index) => {
        countItems++;
        if(this.numberOfDays && countItems > this.numberOfDays) {
          return;
        }

        priceVelocities.push({x: velocityItem.date, y: velocityItem.adjustedClose});

        if(!minDate || velocityItem.date < minDate) {
          minDate = velocityItem.date;
        }

        if(!maxDate || maxDate < velocityItem.date) {
          maxDate = velocityItem.date;
        }
      });

      const cpiChartData = {
        datasets: [
          {
            data: cpiData,
            backgroundColor: 'red',
          },
          {
            data: [
              {
                x: minDate,
                y: null,
              },
              {
                x: maxDate,
                y: null,
              },
            ],
          }
        ],
      };

      const closingPriceChartData = {
        datasets: [
          {
            data: closingPrices,
            backgroundColor: 'blue',
          },
          {
            data: adjustedClosingPrices,
            backgroundColor: 'rebeccapurple',
            borderColor: 'rebeccapurple',
            borderWidth: 2,
          },
          {
            data: cpiAdjustedClosingPrices,
            backgroundColor: 'green',
          },
          {
            data: sma100Data,
            backgroundColor: 'orange',
            borderColor: 'orange',
            borderWidth: 2,
          },
          {
            data: sma100StdUpData,
            backgroundColor: 'yellow',
            borderColor: 'yellow',
            borderWidth: 1,
          },
          {
            data: sma100StdDownData,
            backgroundColor: 'yellow',
            borderColor: 'yellow',
            borderWidth: 1,
          },
          {
            data: sma10Data,
            backgroundColor: 'red',
            borderColor: 'red',
            borderWidth: 2,
          },
          {
            data: sma10StdUpData,
            backgroundColor: 'turquoise',
            borderColor: 'turquoise',
            borderWidth: 1,
          },
          {
            data: sma10StdDownData,
            backgroundColor: 'turquoise',
            borderColor: 'turquoise',
            borderWidth: 1,
          },
          // Adjustments to make sure timeline matches CPI
          {
            data: [
              {
                x: cpiMinDate,
                y: null,
              },
              {
                x: cpiMaxDate,
                y: null,
              },
            ],
          },
        ],
      };

      const volumeChartData = {
        datasets: [
          {
            data: volumes,
            backgroundColor: 'green',
          },
          // Adjustments to make sure timeline matches CPI
          {
            data: [
              {
                x: cpiMinDate,
                y: null,
              },
              {
                x: cpiMaxDate,
                y: null,
              },
            ],
          },
        ],
      };

      const priceVelocityChartData = {
        datasets: [
          {
            data: priceVelocities,
            elements: {
              point: {
                radius: 3,
                borderWidth: 0,
              },
              line: {
                borderWidth: 1,
                borderColor: 'black',
              },
            },
            // pointRadius: 3,
            // lineWidth: 1,
            // lineColor: 'black',
            backgroundColor: (item) => {
              const value = item?.raw?.y ?? 0;
              return value >= 0 ? 'green' : 'red';
            },
          },
          // Adjustments to make sure timeline matches CPI
          {
            data: [
              {
                x: cpiMinDate,
                y: null,
              },
              {
                x: cpiMaxDate,
                y: null,
              },
            ],
          },
        ],
      };

      this.cpiChart = new Chart(
        document.getElementById(this.cpiChartId),
        {
          ...this.getGenericConfig(),
          data: cpiChartData,
        }
      );

      this.priceChart = new Chart(
        document.getElementById(this.closingPriceChartId),
        {
          ...this.getGenericConfig(),
          data: closingPriceChartData,
        }
      );

      this.volumeChart = new Chart(
        document.getElementById(this.volumeChartId),
        {
          ...this.getGenericConfig(),
          data: volumeChartData,
        }
      );

      let priceVelocityChartSettings = {
        ...this.getGenericConfig(),
        data: priceVelocityChartData,
      };
      priceVelocityChartSettings.options.scales.x.grid = {borderWidth: 2, borderColor: 'black'};
      priceVelocityChartSettings.options.scales.x.position = {y: 0};

      this.priceVelocityChart = new Chart(
        document.getElementById(this.priceVelocityChartId),
        priceVelocityChartSettings
      );
    },

    destroyCharts() {
      if(this.cpiChart) {
        this.cpiChart.destroy();
      }
      if(this.priceChart) {
        this.priceChart.destroy();
      }
      if(this.priceVelocityChart) {
        this.priceVelocityChart.destroy();
      }
      if(this.volumeChart) {
        this.volumeChart.destroy();
      }
    },
    getGenericConfig() {
      return {
        type: 'line',
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
              ticks: {
                display: false,
              },
            }
          },
          plugins: {
            legend: {
              display: false,
            },
          },
          responsive: true,
          maintainAspectRatio: false,
        },
      };
    },
    clickNumberOfDays() {
      this.setupChart();
    },
  },
};
</script>

<style lang="scss">
.stock-price-chart-controls {
  display: flex;
  align-items: flex-end;

  .form-field-container {
    margin-right: 5px;
  }
}

.cpi-chart-container {
  position: relative;
  height: 100px;
}
.closing-price-chart-container {
  position: relative;
}
.price-velocity-chart-container {
  height: 75px;
}
.volume-chart-container {
  height: 100px;
}
</style>

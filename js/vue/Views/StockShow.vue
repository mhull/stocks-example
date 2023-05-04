<template>
<div class="stock-show-component">
  <h1>{{ heading }}</h1>

  <p v-if="message">{{ message }}</p>

  <div class="stock-status" v-if="stock.id">
    <div class="latest">
      <span class="date">{{ latestDate }}</span><br/>
      <span class="close">{{ formatUsd(latestClose) }}</span><br />
      <span class="change" :class="latestChangeCssClass">{{ formatUsd(latestChange) }} ({{ latestChangePercent }}%)</span>
    </div>

    <div class="action-items">
      <exchange-label :exchange="exchange"></exchange-label>
      <p>IPO: {{ stock.getFullIpoDate() }}
      </p>
      <button
        @click="clickSyncStockPrice"
      >Sync stock data</button>
    </div>
  </div>

  <template v-if="stock.id">
    <stock-price-chart
      v-if="!isLoadingStockPrice && hasStockPrice &&
        !isLoadingStockPriceSma100 &&
        !isLoadingStockPriceSma10"
      :stock="stock"
    ></stock-price-chart>
  </template>

  <div class="stock-fundamentals" v-if="hasCompanyInformation">
    <div class="row">
      <div class="latest-quarter">Latest quarter:<br />{{ companyInformation.latestQuarter }}</div>

      <div class="analyst-target-price">Analyst target price:<br />
        <span :class="analystTargetPriceDifferenceCssClass">
          ${{ companyInformation.analystTargetPrice }}
          ({{formatUsd(analystTargetPriceDifference)}})
        </span>
      </div>

      <div class="price-earnings">
        P/E ratio:<br />
        {{ companyInformation.peRatio }}
      </div>

      <div class="forward-price-earnings">
        Forward P/E ratio:<br />
        {{ companyInformation.forwardPeRatio }}
      </div>

      <div class="price-earnings-growth">
        P/E/G ratio:<br />
        {{ companyInformation.pegRatio }}
      </div>

      <div class="book-value">
        Book value:<br />
        {{ companyInformation.bookValue }}
      </div>

      <button @click="clickSyncCompanyInformation">Sync company information</button>
    </div><!-- .row -->

    <div class="row">
      <div class="quarterly-earnings-growth-yoy">
        QEGYOY:<br />
        {{ companyInformation.quarterlyEarningsGrowthYoy }}
      </div>
      <div class="quarterly-revenue-growth-yoy">
        QRGYOY:<br />
        {{ companyInformation.quarterlyRevenueGrowthYoy }}
      </div>
    </div>

    <div class="row">
      <div class="annual-high">
        Annual high:<br />
        {{ formatUsd(companyInformation.annualHigh) }}
      </div>

      <div class="annual-low">
        Annual low:<br />
        {{ formatUsd(companyInformation.annualLow) }}
      </div>

      <div class="sma-50">
        SMA 50 day:<br />
        {{ formatUsd(companyInformation.sma50Day) }}
      </div>

      <div class="sma-200">
        SMA 200 day:<br />
        {{ formatUsd(companyInformation.sma200Day) }}
      </div>
    </div>

    <div class="row">
      <div class="company-description">{{ companyInformation.description }}</div>
    </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import StockPriceChart from "stocks/vue/Components/Stock/StockPriceChart";
import ExchangeLabel from 'stocks/vue/Components/Exchange/ExchangeLabel';

import HasStockPriceSma from 'stocks/vue/Mixins/Stock/HasStockPriceSma';
import HasExchange from 'stocks/vue/Mixins/Exchange/HasExchange';

import DateModel from 'stocks/models/Date';
import Stock from 'stocks/models/Stock';
import StockPriceList from 'stocks/models/StockPrice/StockPriceList';
import StockPriceVelocity from 'stocks/models/StockPriceVelocity';
import StockPriceSma from 'stocks/models/StockPriceSma';
import CompanyInformation from 'stocks/models/CompanyInformation';

import { formatUsd } from 'stocks/models/Currency';

export default {
  name: 'StockShow',
  mixins: [ HasStockPriceSma, HasExchange ],
  components: { StockPriceChart, ExchangeLabel },
  mounted() {
    if(this.hasStock && this.hasStockPrice && this.hasStockPriceVelocity && this.hasStockPriceSma100 && this.hasCompanyInformation) {
      this.setTitle();
      return;
    }

    if(!this.hasStock) {
      this.isLoadingNewStock = true;

      this.fetchStock(this.routeStockId)
        .then(data => {
          if(data.id) {
            this.addStockToList(new Stock(data));
            this.setTitle();
          }
          else {
            if(data.message) {
              this.message = data.message;
            }
          }
        })
        .finally(() => {
          this.isLoadingNewStock = false;
        });
    }

    if(!this.hasStockPrice) {
      this.isLoadingStockPrice = true;

      this.fetchStockPrice(this.routeStockId)
        .then(data => {
          const listItem = new StockPriceList({
            stockId: this.routeStockId,
            items: data,
          });
          this.addStockPriceToList(listItem);
          this.setTitle();
        })
        .finally(() => {
          this.isLoadingStockPrice = false;
        });
    }

    if(!this.hasStockPriceVelocity) {
      this.isLoadingStockPriceVelocity = true;

      this.fetchStockPriceVelocity(this.routeStockId)
        .then(data => {
          const listItem = new StockPriceVelocity({
            stockId: this.routeStockId,
            items: data,
          });
          this.addStockPriceVelocityToList(listItem);
          this.setTitle();
        })
        .finally(() => {
          this.isLoadingStockPriceVelocity = false;
        });
    }

    if(!this.hasStockPriceSma100) {
      this.setLoadingStockPriceSma100(true);

      this.fetchStockPriceSma100({
        stockId: this.routeStockId,
        period: 100,
      })
        .then(data => {
          const stockPriceSma100 = new StockPriceSma({
            stockId: this.routeStockId,
            items: data,
          })
          this.addStockPriceSma100ToList(stockPriceSma100);
        })
        .finally(() => {
          this.setLoadingStockPriceSma100(false);
        });
    }

    if(!this.hasStockPriceSma10) {
      this.setLoadingStockPriceSma10(true);

      this.fetchStockPriceSma10({
        stockId: this.routeStockId,
        period: 10,
      })
        .then(data => {
          const stockPriceSma10 = new StockPriceSma({
            stockId: this.routeStockId,
            items: data,
          })
          this.addStockPriceSma10ToList(stockPriceSma10);
        })
        .finally(() => {
          this.setLoadingStockPriceSma10(false);
        });
    }

    if(!this.hasCompanyInformation) {
      this.setLoadingCompanyInformation(true);

      this.fetchCompanyInformation(this.routeStockId)
        .then(data => {
          this.addCompanyInformationToList(new CompanyInformation(data));
        })
        .finally(() => {
          this.setLoadingCompanyInformation(false);
        });
    }
  },
  beforeUnmount() {
    document.title = 'Stocks';
  },
  data() {
    return {
      message: '',
      isLoadingNewStock: false,
      isLoadingStockPrice: false,
      isLoadingStockPriceVelocity: false,
    };
  },
  computed: {
    ...mapGetters('companyInformation', {
      getCompanyInformation: 'getByStockId',
    }),
    ...mapGetters('stock', {
      getStock: 'getById',
      isLoading: 'getLoading',
    }),
    ...mapGetters('stockPrice', {
      getStockPrice: 'getStockPrice',
    }),
    ...mapGetters('stockPriceVelocity', {
      getStockPriceVelocity: 'getStockPriceVelocity',
    }),
    latest() {
      return this.stockPrice.getLatestItem();
    },
    latestClose() {
      return this.latest?.close ?? '';
    },
    latestDate() {
      return new DateModel({iso: this?.latest?.date}).getFull();
    },
    latestChange() {
      return this.latest?.close ?
        parseFloat(this.latest.close) - parseFloat(this.previous.close) :
        '';
    },
    latestChangePercent() {
      return this.latestChange ?
        ((this.latestChange / parseFloat(this.previous.close))*100).toFixed(2) :
        ''
    },
    latestChangeCssClass() {
      return this.latestChange ?
        (this.latestChange > 0 ? 'positive' : 'negative') :
        '';
    },
    previous() {
      return this.stockPrice.getPreviousItem(this.latest.date);
    },
    stockPrice() {
      return new StockPriceList(this.getStockPrice(this.routeStockId));
    },
    hasStockPrice() {
      return this.stockPrice.items.length > 0;
    },
    stockPriceVelocity() {
      return new StockPriceVelocity(this.getStockPriceVelocity(this.routeStockId));
    },
    hasStockPriceVelocity() {
      return this.stockPriceVelocity.items.length > 0;
    },
    heading() {
      if(this.stock.id) {
        return `${this.stock.name} (${this.stock.symbol})`;
      }
      if(this.isLoadingNewStock) {
        return ' ';
      }
      return 'Stock not found';
    },
    routeStockId() {
      return parseInt(this.$route.params.stockId) || 0;
    },
    stock() {
      return this.getStock(this.routeStockId) || {};
    },
    hasStock() {
      return Boolean(this.stock.id);
    },
    companyInformation() {
      return new CompanyInformation(this.getCompanyInformation(this.routeStockId));
    },
    hasCompanyInformation() {
      return Boolean(this.companyInformation.stockId);
    },
    analystTargetPriceDifference() {
      const price = this.latest?.close ?? 0;
      return this.companyInformation.getAnalystTargetPriceDifference(price);
    },
    analystTargetPriceDifferenceCssClass() {
      return this.analystTargetPriceDifference >= 0 ? 'negative' : 'positive';
    },
  },
  methods: {
    ...mapActions('companyInformation', {
      fetchCompanyInformation: 'fetch',
      syncCompanyInformation: 'syncCompanyInformation',
    }),
    ...mapActions('stock', {
      fetchStock: 'fetchStock',
    }),
    ...mapActions('stockPrice', {
      fetchStockPrice: 'fetchStockPrice',
      syncStockPrice: 'syncStockPrice',
    }),
    ...mapActions('stockPriceVelocity', {
      fetchStockPriceVelocity: 'fetchStockPriceVelocity',
    }),
    ...mapMutations('companyInformation', {
      addCompanyInformationToList: 'addToList',
      setLoadingCompanyInformation: 'setLoadingList',
    }),
    ...mapMutations('stock', {
      addStockToList: 'addListItem',
    }),
    ...mapMutations('stockPrice', {
      addStockPriceToList: 'addToList',
    }),
    ...mapMutations('stockPriceVelocity', {
      addStockPriceVelocityToList: 'addToList',
    }),
    formatUsd,
    setTitle() {
      if(!this.stock.symbol || !this.latestClose) {
        return;
      }
      document.title = `${this.stock.symbol} - ${this.formatUsd(this.latestClose)}`;
    },
    clickSyncStockPrice() {
      this.syncStockPrice(this.stock.id)
        .then(data => {
          const listItem = new StockPriceList({
            stockId: this.routeStockId,
            items: data,
          });
          this.addStockPriceToList(listItem);
        })
    },
    clickSyncCompanyInformation() {
      this.syncCompanyInformation(this.stock.id)
        .then(data => {

        });
    }
  },
};
</script>

<style lang='scss'>
.stock-show-component {
  .stock-status {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.5rem;
  }

  .stock-fundamentals {
    .row {
      display: flex;
      column-gap: 30px;

      margin-bottom: 2rem;

      button {
        margin-left: auto;
        align-self: flex-start;
      }
    }
  }

  span.close {
    font-size: 1.4rem;
  }

  span.positive {
    color: green;
  }

  span.negative {
    color: red;
  }
}
</style>

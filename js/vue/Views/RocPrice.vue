<template>
<div class="roc-price-view-component">
  <menu-roc-price></menu-roc-price>

  <div class="roc-report-form">
    <div class="form-field-container">
      <label for="rocDate">Show ROC for date:</label>
      <input id="rocDate" type="date" v-model="rocDate" />
    </div>
    <div class="form-field-container">
      <label for="rocNumber">Number of results:</label>
      <input id="rocNumber" type="number" v-model="rocNumber" />
    </div>
    <button @click="clickSubmitRocDate">Go</button>
  </div>

  <loading v-if="isLoadingDailyPriceRocList"></loading>

  <template v-if="!isLoadingDailyPriceRocList && hasDailyPriceRocData">
    <h2>Biggest gain - {{ metric }}</h2>
    <stock-list-table-roc :rocItems="biggestGainItems"></stock-list-table-roc>

    <h2>Biggest loss - {{ metric }}</h2>
    <stock-list-table-roc :rocItems="biggestLossItems"></stock-list-table-roc>
  </template>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import Loading from 'stocks/vue/Components/Loading';
import MenuRocPrice from 'stocks/vue/Components/Menu/MenuRocPrice';

import StockListTableRoc from 'stocks/vue/Components/Stock/StockListTableRoc';

export default {
  name: 'RocPriceView',
  components: { Loading, MenuRocPrice, StockListTableRoc },
  data() {
    return {
      rocDate: null,
      rocNumber: 10,
    };
  },
  computed: {
    ...mapGetters('stock', {
      stocks: 'getList',
    }),
    ...mapGetters('roc', {
      dailyPriceRocList: 'getDailyPriceRocList',
      isLoadingDailyPriceRocList: 'getLoadingDailyPriceRocList',
    }),
    metric() {
      return this.$route.params.metric ?? '';
    },
    hasDailyPriceRocData() {
      return this.dailyPriceRocList.hasOwnProperty('gain');
    },
    biggestGainItems() {
      return this.dailyPriceRocList?.gain ?? [];
    },
    biggestLossItems() {
      return this.dailyPriceRocList?.loss ?? [];
    },
  },
  methods: {
    ...mapActions('roc', {
      fetchDailyPriceRocList: 'fetchDailyPriceRocList',
    }),
    ...mapMutations('roc', {
      setDailyPriceRoc: 'setDailyPriceRocList',
      setLoadingDailyPriceRoc: 'setLoadingDailyPriceRocList',
    }),
    clickSubmitRocDate() {
      this.setLoadingDailyPriceRoc(true);

      this.fetchDailyPriceRocList({
        date: this.rocDate,
        metric: this.$route.params.metric ?? '',
        number: this.rocNumber,
      })
        .then(data => this.setDailyPriceRoc(data))
        .finally(() => {
          this.setLoadingDailyPriceRoc(false);
        });
    }
  },
};
</script>

<style lang="scss">
.stock-list-table-roc-component {
  margin-bottom: 2rem;
}
</style>

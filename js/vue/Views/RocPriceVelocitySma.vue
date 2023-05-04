<template>
<div class="roc-price-velocity-sma-view-component">

  <menu-roc-sma></menu-roc-sma>

  <div class="roc-report-form">
    <div class="form-field-container">
      <label for="rocDate">Show Price Velocity SMA for date:</label>
      <input id="rocDate" type="date" v-model="rocDate" />
    </div>
    <div class="form-field-container">
      <label for="rocNumber">Number of results:</label>
      <input id="rocNumber" type="number" v-model="rocNumber">
    </div>
    <button @click="clickSubmitRocDate">Go</button>
  </div>

  <loading v-if="isLoadingList"></loading>

  <template v-if="!isLoadingList && hasItems">
    <stock-list-table-roc-sma :items="biggestGainItems"></stock-list-table-roc-sma>
  </template>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import StockListTableRocSma from "stocks/vue/Components/Stock/StockListTableRocSma";

import Loading from 'stocks/vue/components/Loading';
import MenuRocSma from 'stocks/vue/components/Menu/MenuRocSma';

export default {
  name: 'RocPriceVelocitySmaView',
  components: { StockListTableRocSma, Loading, MenuRocSma },
  data() {
    return {
      rocDate: '',
      rocNumber: 10,
    };
  },
  computed: {
    ...mapGetters('roc', {
      list: 'getPriceVelocitySmaDailyList',
      isLoadingList: 'getLoadingPriceVelocitySmaDailyList',
    }),
    hasItems() {
      return this.list.hasOwnProperty('gain');
    },
    metric() {
      return this.$route?.params?.metric ?? '';
    },
    biggestGainItems() {
      return this.list?.gain ?? [];
    },
  },
  methods: {
    ...mapActions('roc', {
      fetchList: 'fetchPriceVelocitySmaDailyList',
    }),
    ...mapMutations('roc', {
      setList: 'setPriceVelocitySmaDailyList',
      setLoading: 'setLoadingPriceVelocitySmaDailyList',
    }),
    clickSubmitRocDate() {
      this.setLoading(true);

      this.fetchList({
        date: this.rocDate,
        number: this.rocNumber,
        metric: this.metric,
      })
        .then(data => {
          this.setList(data);
        })
        .finally(() => {
          this.setLoading(false);
        });
    },
  },
};
</script>

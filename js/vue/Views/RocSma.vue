<template>
<div class="roc-sma-view-component">
  <menu-roc-sma></menu-roc-sma>

  <div class="roc-report-form">
    <div class="form-field-container">
      <label for="rocDate">Show ROC for date:</label>
      <input id="rocDate" type="date" v-model="rocDate" />
    </div>
    <div class="form-field-container">
      <label for="rocNumber">Number of results:</label>
      <input id="rocNumber" type="number" v-model="rocNumber">
    </div>
    <button @click="clickSubmitRocDate">Go</button>
  </div>

  <loading v-if="isLoadingRocList"></loading>

  <template v-if="!isLoadingRocList && hasRocItems">
    <h2>Biggest gain - {{ metric }}</h2>
    <stock-list-table-roc-sma :items="biggestGainItems"></stock-list-table-roc-sma>
  </template>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import Loading from "stocks/vue/Components/Loading";
import MenuRocSma from "stocks/vue/Components/Menu/MenuRocSma";

import StockListTableRocSma from "stocks/vue/Components/Stock/StockListTableRocSma";

export default {
  name: 'RocSmaView',
  components: { Loading, MenuRocSma, StockListTableRocSma },
  data() {
    return {
      rocDate: '',
      rocNumber: 10,
    };
  },
  computed: {
    ...mapGetters('roc', {
      rocList: 'getDailySmaRocList',
      isLoadingRocList: 'getLoadingDailySmaRocList',
    }),
    hasRocItems() {
      return this.rocList.hasOwnProperty('gain');
    },
    metric() {
      return this.$route.params.metric ?? '';
    },
    biggestGainItems() {
      return this.rocList?.gain ?? [];
    },
  },
  methods: {
    ...mapActions('roc', {
      fetchRoc: 'fetchDailySmaRocList',
    }),
    ...mapMutations('roc', {
      setRocList: 'setDailySmaRocList',
      setLoadingRocList: 'setLoadingDailySmaRocList',
    }),
    clickSubmitRocDate() {
      this.setLoadingRocList(true);

      this.fetchRoc({
        date: this.rocDate,
        metric: this.metric,
        number: this.rocNumber,
      })
        .then(data => {
          this.setRocList(data);
        })
        .finally(() => {
          this.setLoadingRocList(false);
        });
    },
  },
};
</script>

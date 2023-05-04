<template>
<div id="main-component">
  <menu-main></menu-main>

  <loading v-if="isLoading"></loading>
  <router-view v-if="!isLoading"></router-view>
</div>
</template>

<script>
import Loading from './Components/Loading.vue';
import MenuMain from './Components/Menu/MenuMain';

import { mapGetters, mapMutations, mapActions } from 'vuex';

import Stock from 'stocks/models/Stock';
import StockShare from 'stocks/models/StockShare';

export default {
  name: 'MainComponent',
  components: { Loading, MenuMain },
  mounted() {
    this.setLoadingExchanges(true);
    this.getExchanges()
      .then(data => {
        this.setExchangeList(data);
        this.setLoadingExchanges(false);
      });

    this.setLoadingCpi(true);

    this.getCpi()
      .then(data => {
        this.setCpiList(data);
      })
      .finally(() => {
        this.setLoadingCpi(false);
      });

    this.setLoadingStockShares(true);

    this.getShares()
      .then(data => {
        let shares = data.map(item => new StockShare(item));
        this.setShares(shares);
      })
      .finally(() => {
        this.setLoadingStockShares(false);
      });
  },
  computed: {
    ...mapGetters('cpi', {
      isLoadingCpi: 'getLoading',
    }),
    ...mapGetters('stockShare', {
      isLoadingStockShares: 'getLoading',
    }),
    ...mapGetters('exchange', {
      isLoadingExchanges: 'getLoading',
    }),
    isLoading() {
      return this.isLoadingExchanges ||
        this.isLoadingCpi ||
        this.isLoadingStockShares;
    },
  },
  methods: {
    ...mapMutations('cpi', {
      setLoadingCpi: 'setLoading',
      setCpiList: 'setList',
    }),
    ...mapMutations('stockShare', {
      setShares: 'setList',
      setLoadingStockShares: 'setLoading',
    }),
    ...mapMutations('exchange', {
      setLoadingExchanges: 'setLoading',
      setExchangeList: 'setList',
    }),
    ...mapActions('cpi', {
      getCpi: 'fetchList',
    }),
    ...mapActions('stock', {
      getStocks: 'fetchList',
    }),
    ...mapActions('stockShare', {
      getShares: 'fetchList',
    }),
    ...mapActions('exchange', {
      getExchanges: 'getList',
    }),
  },
}
</script>

<style lang="scss">
@import 'sass/_variables.scss';

label {
  font-weight: bold;
}

.form-field-container {
  margin-bottom: 1rem;

  label {
    display: block;
  }
}

table {
  table-layout: fixed;
  border: solid 1px $black;
  border-collapse: collapse;
  width: 100%;

  th, td {
    text-align: left;
    &.date {
      width: 10%;
    }
    &.active {
      width: 5%;
    }
    &.symbol {
      width: 10%;
    }
    &.isWarrant {
      width: 6%;
      text-align: center;
    }
  }

  th, td {
    border: solid 1px $black;
    padding: 5px;

    &.active,
    &.symbol {
      text-align: center;
    }
  }
}

#main-component {
  padding: 10px 20px;
}
</style>

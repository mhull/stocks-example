<template>
  <div class="home-view-component">
    <div class="search-container">
      <search></search>
    </div>

    <stock-list-table
      v-if="filteredStocks.length"
      :stocks="filteredStocks"></stock-list-table>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

import Search from 'stocks/vue/Components/Search';
import StockListTable from "stocks/vue/Components/Stock/StockListTable";

export default {
  name: 'HomeView',
  components: { Search, StockListTable },
  computed: {
    ...mapGetters('stock', {
      stocks: 'getList',
      searchParams: 'getSearchParams',
    }),
    filteredStocks() {
      let searchName = this.searchParams?.name ?? '';
      if(!searchName) {
        return this.stocks;
      }

      return this.stocks.filter(stock => {
        return stock.name.toLowerCase()
            .includes(searchName.toLowerCase()) ||
          stock.symbol.toLowerCase() === searchName.toLowerCase();
      });
    },
  },
};
</script>

<style lang="scss">
.home-view-component {
  .search-container {
    margin: 10px 0 20px;
  }
}
</style>

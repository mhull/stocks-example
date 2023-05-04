<template>
<div class="stock-share-list-table-component">
  <table>
    <tr>
      <th>Symbol</th>
      <th>Number of shares</th>
      <th>Date purchased</th>
      <th>Purchase price</th>
    </tr>

    <tr v-for="share in stockShares" :key="share.id">
      <td>{{ getStockForShare(share).symbol }}</td>
      <td>{{ share.numberShares }}</td>
      <td>{{ share.datePurchased }}</td>
      <td>{{ share.purchasePrice }}</td>
    </tr>
  </table>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import Stock from 'stocks/models/Stock';

export default {
  name: 'StockShareListTable',
  props: {
    stockShares: {
      type: Array,
      default: () => [],
    },
  },
  mounted() {
    this.stockShares.forEach(share => {
      const stockId = share.stockId;
      const foundStock = this.getStockForShare(share);

      if(!foundStock.id) {
        this.fetchStock(stockId)
          .then(data => this.addStockToList(new Stock(data)));
      }
    });
  },
  computed: {
    ...mapGetters('stock', {
      getStockById: 'getById',
    }),
  },
  methods: {
    ...mapActions('stock', {
      fetchStock: 'fetchStock',
    }),
    ...mapMutations('stock', {
      addStockToList: 'addListItem',
    }),
    getStockForShare(stockShare) {
      return this.getStockById(stockShare.stockId) || new Stock();
    },
  },
};
</script>

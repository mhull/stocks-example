<template>
<tr class="stock-list-table-roc-sma-item-component">
  <td class="date">{{ item.date }}</td>
  <td class="active">
    <active-indicator :isActive="stock.active"></active-indicator>
  </td>
  <td class="symbol">
    <router-link :to="{name: 'stock-show', params: {stockId: stock.id}}">{{ stock.symbol }}</router-link>
  </td>
  <td class="roc">
    <table class="roc-details">
      <tr>
        <td class="mean">{{ item.mean }}</td>
        <td class="median">{{ item.median }}</td>
        <td class="stdDev">{{ item.stdDev }}</td>
      </tr>
    </table>
  </td>
</tr>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import ActiveIndicator from "stocks/vue/Components/ActiveIndicator";

import Stock from 'stocks/models/Stock';

export default {
  name: 'StockListTableRocSmaItem',
  components: { ActiveIndicator },
  props: {
    item: {
      type: Object,
      default: () => ({}),
    },
  },
  mounted() {
    if(!this.stock.id) {
      this.fetchStock(this.item.stockId)
        .then(data => {
          this.addStockToList(new Stock(data));
        });
    }
  },
  computed: {
    ...mapGetters('stock', {
      getStock: 'getById',
    }),
    stock() {
      const stockId = parseInt(this.item?.stockId);
      return this.getStock(stockId) ?? new Stock();
    },
  },
  methods: {
    ...mapActions('stock', {
      fetchStock: 'fetchStock',
    }),
    ...mapMutations('stock', {
      addStockToList: 'addListItem',
    }),
  },
};
</script>

<template>
<div class="stock-list-table-component">
  <table>
    <tr>
      <th class="active">Active</th>
      <th class="symbol">Symbol</th>
      <th class="name">Name</th>
      <th class="type">Type</th>
      <th class="exchange">Exchange</th>
      <th class="ipo">IPO</th>
      <th class="delisting">Delisting</th>
    </tr>
    <stock-list-table-item
        v-for="stock in stocks"
        :stock="stock"
    ></stock-list-table-item>
  </table>
</div>
</template>

<script>
import StockListTableItem from './StockListTableItem.vue';

import { mapGetters } from 'vuex';

export default {
  name: 'StockListTable',
  components: { StockListTableItem },
  props: {
    stocks: {
      type: Array,
      default: () => [],
    },
  },
};
</script>

<style lang="scss">
@import 'sass/_variables.scss';

.stock-list-table-component {
  table {
    th {
      &.type {
        width: 5%;
      }
    }

    th, td {
      &.ipo,
      &.delisting {
        width: 8%;
      }
    }
  }
}
</style>

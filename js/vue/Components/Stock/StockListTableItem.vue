<template>
<tr class="stock-list-table-item-component">
  <td class="active"><active-indicator :isActive="stock.active"></active-indicator></td>
  <td class="symbol">{{ stock.symbol }}</td>
  <td class="name">
    <router-link :to="{name: 'stock-show', params: {stockId: stock.id}}">{{ stock.name }}</router-link></td>
  <td class="type">{{ stock.assetType }}</td>
  <td class="exchange"><span :class="exchange.slug">{{ exchange.name }}</span></td>
  <td class="ipo">{{ stock.ipoDate }}</td>
  <td class="delisting">{{ stock.delistingDate }}</td>
</tr>
</template>

<script>
import { mapGetters } from 'vuex';
import ActiveIndicator from '../ActiveIndicator.vue'

import Exchange from 'stocks/models/Exchange';

export default {
  name: 'StockListTableItem',
  components: { ActiveIndicator },
  props: {
    stock: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
    ...mapGetters('exchange', {
      getExchange: 'getById',
    }),
    exchange() {
      return new Exchange(this.getExchange(this.stock.exchange));
    },
  },
};
</script>

<style lang='scss'>
.stock-list-table-item-component {
  td {
    position: relative;
  }

  .exchange {
    & > span {
      padding: 4px 5px 2px;
      border-radius: 3px;

      &.nyse {
        background: coral;
      }

      &.nasdaq {
        background: cornflowerblue;
      }

      &.nyse-arca {
        background: burlywood;
      }

      &.bats {
        background: darkseagreen;
      }

      &.nyse-mkt {
        background: plum;
      }
    }
  }
}
</style>

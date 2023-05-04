<template>
<div class="stock-list-table-roc-sma-component">
  <table :class="$route.params.metric">
    <thead>
      <tr>
        <th class="date">Date</th>
        <th class="active">Active</th>
        <th class="symbol">Symbol</th>
        <th class="roc">
          ROC (x100%)
          <table class="roc-details">
            <tr>
              <th class="mean">Mean</th>
              <th class="median">Median</th>
              <th class="stdDev">Std Dev</th>
            </tr>
          </table>
        </th>
      </tr>
    </thead>

    <tbody>
      <template v-for="item in items">
        <stock-list-table-roc-sma-item :item="item"></stock-list-table-roc-sma-item>
      </template>
    </tbody>
  </table>
</div>
</template>

<script>
import StockListTableRocSmaItem from "stocks/vue/Components/Stock/StockListTableRocSmaItem";

export default {
  name: 'StockListTableRocSma',
  components: { StockListTableRocSmaItem },
  props: {
    items: {
      type: Array,
      default: () => [],
    },
  },
};
</script>

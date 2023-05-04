<template>
<div class="stock-list-table-roc-component">
  <table :class="$route.params.metric">
    <thead>
      <tr>
        <th class="date">Date</th>
        <th class="active">Active</th>
        <th class="symbol">Symbol</th>
        <th class="isWarrant">Warrant</th>
        <th class="roc">ROC (x100%)
          <table class='roc-details'>
            <tr>
              <th class="median">Median</th>
              <th class="mean">Mean</th>
              <th class="close">Close</th>
              <th class="adjustedClose">Adj close</th>
              <th class="adjustedMedian">Adj median</th>
              <th class="adjustedMean">Adj mean</th>
              <th class="open">Open</th>
              <th class="high">High</th>
              <th class="low">Low</th>
            </tr>
          </table>
        </th>
      </tr>
    </thead>
    <tbody>
      <template v-for="rocItem in rocItems">
        <stock-list-table-roc-item :item="rocItem"></stock-list-table-roc-item>
      </template>
    </tbody>
  </table>
</div>
</template>

<script>

import StockListTableRocItem from 'stocks/vue/Components/Stock/StockListTableRocItem';

export default {
  name: 'StockListTableRoc',
  components: { StockListTableRocItem },
  props: {
    rocItems: {
      type: Array,
      default: () => [],
    },
  },
};
</script>

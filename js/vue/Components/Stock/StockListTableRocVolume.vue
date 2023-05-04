<template>
<div class="stock-list-table-roc-volume-component">
  <table>
    <thead>
    <tr>
      <th class="date">Date</th>
      <th class="active">Active</th>
      <th class="symbol">Symbol</th>
      <th class="isWarrant">Warrant</th>
      <th class="roc">ROC (x100%)
        <table class='roc-details'>
          <tr>
            <th class="volume">Volume</th>
            <th class="adjustedClose">Adj close</th>
            <th class="adjustedMedian">Adj median</th>
          </tr>
        </table>
      </th>
    </tr>
    </thead>
    <tbody>
    <template v-for="item in items">
      <stock-list-table-roc-volume-item :item="item"></stock-list-table-roc-volume-item>
    </template>
    </tbody>
  </table>
</div>
</template>

<script>
import StockListTableRocVolumeItem from './StockListTableRocVolumeItem';

export default {
  name: 'StockListTableRocVolume',
  components: { StockListTableRocVolumeItem },
  props: {
    items: {
      type: Array,
      default: () => [],
    },
  },
};
</script>

<template>
<div class="roc-view-component">
  <menu-roc></menu-roc>
  <router-view></router-view>
</div>
</template>

<script>
import MenuRoc from 'stocks/vue/Components/Menu/MenuRoc';

export default {
  name: 'RocView',
  components: { MenuRoc },
};
</script>

<style lang="scss">

.roc-report-form {
  margin: 1rem 0;
}

.stock-list-table-roc-component,
.stock-list-table-roc-sma-component,
.stock-list-table-roc-volume-component {
  table {
    thead {
      background: #fff;
      position: sticky;
      inset-block-start: 0;
      z-index: 1;

      th {
        border: solid 2px black;
      }
    }

    &.roc-details {
      border: none;

      td, th {
        border: none;
      }
    }

    &.median td.median,
    &.mean td.mean,
    &.stdDev td.stdDev,
    &.close td.close,
    &.adjustedClose td.adjustedClose,
    &.adjustedMedian td.adjustedMedian,
    &.adjustedMean td.adjustedMean,
    &.open td.open,
    &.high td.high,
    &.low td.low {
      font-size: 1.2rem;
    }
  }
}
</style>

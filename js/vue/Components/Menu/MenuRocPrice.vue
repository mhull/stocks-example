<template>
<div class="menu-roc-price-component menu-component">
  <router-link to="/roc/price/median">Median</router-link>
  <router-link to="/roc/price/mean">Mean</router-link>
  <router-link to="/roc/price/close">Close</router-link>
  <router-link to="/roc/price/adjustedClose">Adjusted close</router-link>
  <router-link to="/roc/price/adjustedMedian">Adjusted median</router-link>
  <router-link to="/roc/price/adjustedMean">Adjusted mean</router-link>
  <router-link to="/roc/price/open">Open</router-link>
  <router-link to="/roc/price/high">High</router-link>
  <router-link to="/roc/price/low">Low</router-link>
</div>
</template>

<script>
export default {
  name: 'MenuRocPrice',
};
</script>


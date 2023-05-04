<template>
<div class="menu-roc-component menu-component">
  <router-link to="/roc/price">Price</router-link>
  <router-link to="/roc/sma">SMA</router-link>
  <router-link to="/roc/price/velocity/sma">Price Velocity SMA</router-link>
  <router-link to="/roc/volume">Volume</router-link>
</div>
</template>

<script>
import MenuMixin from 'stocks/vue/Mixins/Menu';

export default {
  name: 'MenuRoc',
  mixins: [ MenuMixin ],
};
</script>

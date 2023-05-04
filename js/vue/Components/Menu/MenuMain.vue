<template>
<div class="menu-main-component menu-component">
  <router-link to="/">Home</router-link>
  <router-link to="/shares">Shares</router-link>
  <router-link to="/roc">ROC</router-link>
  <router-link to="/timeline">Timeline</router-link>
  <router-link to="/cpi">CPI</router-link>
</div>
</template>

<script>
import MenuMixin from 'stocks/vue/Mixins/Menu';

export default {
  name: 'MenuMain',
};
</script>

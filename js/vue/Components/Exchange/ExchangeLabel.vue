<template>
<div class="exchange-label-component">
  <span :class="exchange.slug">{{ exchange.name }}</span>
</div>
</template>

<script>

export default {
  name: 'ExchangeLabel',
  props: {
    exchange: {
      type: Object,
      default: () => ({}),
    },
  },
};
</script>

<style lang="scss">
.exchange-label-component span {
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
</style>

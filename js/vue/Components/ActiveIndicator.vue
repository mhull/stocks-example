<template>
<div class="active-indicator-component">
  <div class="indicator-container" :class="{active: isActive}"></div>
</div>
</template>

<script>
export default {
  name: 'ActiveIndicator',
  props: {
    isActive: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style lang="scss">
@import 'sass/_variables';

.active-indicator-component {
  position: relative;

  .indicator-container {
    background: $red;
    &.active {
      background: $green;
    }
    width: 10px;
    height: 10px;
    border-radius: 50%;

    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
  }
}
</style>

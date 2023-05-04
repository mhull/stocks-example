<template>
<div class="search-component">
  <label for="search-name">Search:</label>
  <input id="search-name" type="text" @input="updateName" :value="name" />
  <input type="submit" value="Go" @click="clickSubmit"/>

  <loading v-if="isLoading"></loading>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import Loading from 'stocks/vue/components/Loading';

import Stock from 'stocks/models/Stock';

export default {
  name: 'Search',
  components: { Loading },
  computed: {
    ...mapGetters('stock', {
      searchParams: 'getSearchParams',
      isLoading: 'isSearching',
    }),
    name() {
      return this.searchParams?.name ?? '';
    },
  },
  methods: {
    ...mapActions('stock', {
      getSearchResults: 'search'
    }),
    ...mapMutations('stock', {
      updateSearchParam: 'updateSearchParam',
      addStockToList: 'addListItem',
      setSearching: 'setSearching',
    }),
    updateName($event) {
      this.updateSearchParam({param: 'name', value: $event.target.value});
    },
    clickSubmit() {
      this.setSearching(true);

      this.getSearchResults({name: this.name})
        .then(data => {
          if(!data.length) {
            return;
          }
          data.forEach(item => {
            this.addStockToList(new Stock(item));
          });
        })
        .finally(() => {
          this.setSearching(false);
        });
    },
  },
};
</script>

<style lang="scss">
.search-component {
  label {
    display: block;
  }

  input {
    margin-right: 2px;
  }
}
</style>

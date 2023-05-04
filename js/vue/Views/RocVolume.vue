<template>
  <div class="roc-volume-component">

    <div class="roc-report-form">
      <div class="form-field-container">
        <label for="rocDate">Show ROC for date:</label>
        <input id="rocDate" type="date" v-model="rocDate" />
      </div>
      <div class="form-field-container">
        <label for="rocNumber">Number of results:</label>
        <input id="rocNumber" type="number" v-model="rocNumber" />
      </div>
      <div class="form-field-container">
        <label for="rocAbsMin">|roc| &#8805;</label>
        <input id="rocAbsMin" type="number" v-model="rocAbsMin" />
      </div>

      <button @click="clickSubmitRocDate">Go</button>
    </div>

    <loading v-if="isLoading"></loading>

    <template v-if="!isLoading && hasList">
      <h2>Biggest gain - volume</h2>
      <stock-list-table-roc-volume :items="list"></stock-list-table-roc-volume>
    </template>
  </div>
</template>

<script>
import {mapGetters, mapActions, mapMutations} from 'vuex';

import Loading from 'stocks/vue/Components/Loading';
import StockListTableRocVolume from 'stocks/vue/Components/Stock/StockListTableRocVolume';

export default {
  name: 'RocVolume',
  components: { Loading, StockListTableRocVolume },
  data() {
    return {
      rocDate: '',
      rocNumber: 100,
      rocAbsMin: 0.01,
      isLoadingVolumeRocList: false,
    };
  },
  computed: {
    ...mapGetters('roc', {
      list: 'getVolumeVelocityDailyList',
      isLoading: 'isLoadingVolumeVelocityDailyList',
    }),
    hasList() {
      return this.list.length > 0;
    },
  },
  methods: {
    ...mapActions('roc', {
      fetchList: 'fetchVolumeVelocityDailyList',
    }),
    ...mapMutations('roc', {
      setList: 'setVolumeVelocityDailyList',
      setLoading: 'setLoadingVolumeVelocityDailyList',
    }),
    clickSubmitRocDate() {
      this.setLoading(true);

      this.fetchList({
        date: this.rocDate,
        number: this.rocNumber,
        absMin: this.rocAbsMin,
      })
        .then(data => {
          this.setList(data);
        })
        .finally(() => {
          this.setLoading(false);
        });
    },
  },
};
</script>

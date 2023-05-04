<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import StockPriceSma from "stocks/models/StockPriceSma";

export default {
  computed: {
    ...mapGetters('stockPriceSma10', {
      getStockPriceSma10: 'getStockPriceSma',
      isLoadingStockPriceSma10: 'getLoading',
    }),
    ...mapGetters('stockPriceSma100', {
      getStockPriceSma100: 'getStockPriceSma',
      isLoadingStockPriceSma100: 'getLoading',
    }),
    stockPriceSma10() {
      return new StockPriceSma(this.getStockPriceSma10(this.routeStockId));
    },
    stockPriceSma100() {
      return new StockPriceSma(this.getStockPriceSma100(this.routeStockId));
    },
    hasStockPriceSma10() {
      return this.stockPriceSma10.items.length > 0;
    },
    hasStockPriceSma100() {
      return this.stockPriceSma100.items.length > 0;
    },
  },
  methods: {
    ...mapActions('stockPriceSma10', {
      fetchStockPriceSma10: 'fetchStockPriceSma',
    }),
    ...mapActions('stockPriceSma100', {
      fetchStockPriceSma100: 'fetchStockPriceSma',
    }),
    ...mapMutations('stockPriceSma10', {
      addStockPriceSma10ToList: 'addToList',
      setLoadingStockPriceSma10: 'setLoading',
    }),
    ...mapMutations('stockPriceSma100', {
      addStockPriceSma100ToList: 'addToList',
      setLoadingStockPriceSma100: 'setLoading',
    }),
  },
};
</script>

<script>
import { mapGetters } from 'vuex';

import Exchange from 'stocks/models/Exchange';

export default {
  computed: {
    ...mapGetters('exchange', {
      getExchange: 'getById',
    }),
    exchange() {
      return new Exchange(this.getExchange(this.stock.exchange));
    },
  },
};
</script>

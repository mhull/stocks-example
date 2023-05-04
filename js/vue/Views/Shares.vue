<template>
<div class="shares-view-component">
  <h2>Shares Owned</h2>

  <stock-share-list-table :stockShares="stockShares"></stock-share-list-table>

  <h2>Add Share</h2>

  <div class="form-field-container">
    <label for="add-share--symbol">Symbol:</label>
    <input type="text" id="add-share--symbol" v-model="newShare.symbol" />
  </div>

  <div class="form-field-container">
    <label for="add-share--number-shares">Number of shares:</label>
    <input type="text" id="add-share--number-shares" v-model="newShare.numberShares" />
  </div>

  <div class="form-field-container">
    <label for="add-share--date-purchased">Date purchased:</label>
    <input type="text" id="add-share--date-purchased" v-model="newShare.datePurchased" />
  </div>

  <div class="form-field-container">
    <label for="add-share--date-purchased">Purchase price:</label>
    <input type="text" id="add-share--purchase-price" v-model="newShare.purchasePrice" />
  </div>

  <div class="form-field-container">
    <input type="submit" value="Add Share" @click="clickAddNewShareButton" />
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

import StockShareListTable from "stocks/vue/Components/StockShare/StockShareListTable";

import Stock from 'stocks/models/Stock';
import StockShare from 'stocks/models/StockShare';

export default {
  name: 'SharesView',
  components: { StockShareListTable },
  data() {
    return {
      newShare: new StockShare(),
    };
  },
  computed: {
    ...mapGetters('stock', {
      getStock: 'getById',
    }),
    ...mapGetters('stockShare', {
      stockShares: 'getList',
    }),
  },
  methods: {
    ...mapActions('stock', {
      fetchStock: 'fetchStock',
    }),
    ...mapMutations('stock', {
      addStockToList: 'addListItem',
    }),
    ...mapActions('stockShare', {
      createShare: 'create',
    }),
    ...mapMutations('stockShare', {
      addShareToList: 'addListItem',
    }),
    clickAddNewShareButton() {
      this.createShare(this.newShare)
        .then(data => {
          if(data.id) {
            const share = new StockShare(data);
            this.addShareToList(share);

            const stock = this.getStock(share.stockId);
            if(!stock) {
              this.fetchStock(share.stockId)
                .then(data => {
                  this.addStockToList(new Stock(data));
                });
            }
          }
        });
    },
  },
};
</script>

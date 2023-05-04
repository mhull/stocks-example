export default {
	getStockPriceSma: state => stockId => {
		return state.list.find(item => item.stockId === stockId);
	},
	getLoading: state => state.isLoading,
};

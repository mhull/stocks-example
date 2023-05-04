export default {
	getList(state) {
		return state.list;
	},
	getStockPriceVelocity: state => stockId => {
		return state.list.find(item => item.stockId === stockId);
	},
};

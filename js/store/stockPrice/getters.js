export default {
	getList(state) {
		return state.list;
	},
	getStockPrice: state => stockId => {
		return state.list.find(item => item.stockId === stockId);
	},
};

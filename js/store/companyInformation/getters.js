export default {
	list: state => state.list,
	isLoadingList: state => state.isLoadingList,

	getByStockId: state => stockId => {
		return state.list.find(item => item.stockId === stockId);
	},
};

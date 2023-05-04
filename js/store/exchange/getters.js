export default {
	getLoading(state) {
		return state.isLoading;
	},
	getList(state) {
		return state.list;
	},
	getById: state => id => {
		return state.list.find(exchange => exchange.id === id);
	}
};

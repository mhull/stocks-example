export default {
	getLoading(state) {
		return state.isLoading;
	},
	getList(state) {
		return state.list;
	},
	getById: state => id => {
		return state.list.find(item => item.id === id);
	},

	getSearchParams(state) {
		return state.searchParams;
	},
	isSearching(state) {
		return state.isSearching;
	}
};

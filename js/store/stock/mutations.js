export default {
	setLoading(state, isLoading) {
		state.isLoading = isLoading;
	},
	setList(state, list) {
		state.list = list;
	},
	addListItem(state, item) {
		state.list.push(item);
	},
	updateSearchParam(state, {param, value}) {
		state.searchParams[param] = value;
	},
	setSearching(state, isSearching) {
		state.isSearching = isSearching;
	},
};

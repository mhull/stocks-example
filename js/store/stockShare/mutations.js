export default {
	setList(state, list) {
		state.list = list;
	},
	addListItem(state, item) {
		state.list.push(item);
	},
	setLoading(state, isLoading) {
		state.isLoading = isLoading;
	},
};

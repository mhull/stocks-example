export default {
	addToList(state, item) {
		state.list.push(item);
	},
	setLoading(state, value) {
		state.isLoading = value;
	},
};

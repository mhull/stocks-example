export default {
	setList(state, list) {
		state.list = list;
	},
	addToList(state, item) {
		state.list.push(item);
	},
	setLoadingList(state, value) {
		state.isLoadingList = Boolean(value);
	},
};

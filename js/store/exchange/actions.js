export default {
	getList(context) {
		const url = `${context.rootState.restUrl}/stocks/v1/exchange`;
		return fetch(url)
			.then(response => response.json());
	},
};

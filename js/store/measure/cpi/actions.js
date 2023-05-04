export default {
	fetchList(context) {
		const url = `${context.rootState.restUrl}/stocks/v1/cpi`;
		return fetch(url)
			.then(response => response.json());
	},
};

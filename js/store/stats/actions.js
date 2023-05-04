export default {
	getAllTimeStats(context) {
		const url = `${context.rootState.restUrl}/stocks/v1/stats/all-time`;
		return fetch(url)
			.then(response => response.json());
	},
};

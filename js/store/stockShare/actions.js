export default {
	fetchList(context) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock-share`;

		return fetch(url)
			.then(response => response.json());
	},

	create(context, share) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock-share`;

		return fetch(url, {
			method: 'POST',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(share),
		})
			.then(response => response.json());
	},
};

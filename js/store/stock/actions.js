export default {
	fetchList(context) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/get-listing`;
		return fetch(url)
			.then(response => response.json());
	},
	fetchStock(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}`;
		return fetch(url)
			.then(response => response.json())
	},
	search(context, searchParams) {
		const name = searchParams?.name ?? '';

		const url = `${context.rootState.restUrl}/stocks/v1/stock/search`;

		return fetch(url, {
			method: 'POST',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				name,
			}),
		})
			.then(response => response.json());

	},
	saveAsWarrant(context, {stockId, isWarrant}) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}/warrant`;

		return fetch(url, {
			method: 'PATCH',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({isWarrant}),
		})
			.then(response => response.json());
	},
};

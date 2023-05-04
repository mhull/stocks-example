export default {
	fetchStockPrice(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}/price`;
		return fetch(url)
			.then(response => response.json());
	},
	syncStockPrice(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}/sync-price`;
		return fetch(url, {
			method: 'POST',
		})
			.then(response => response.json())
	},
};

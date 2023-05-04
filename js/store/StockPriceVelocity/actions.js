export default {
	fetchStockPriceVelocity(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}/price/velocity`;

		return fetch(url)
			.then(response => response.json());
	},
};

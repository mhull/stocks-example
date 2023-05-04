export default {
	fetchStockPriceSma(context, {stockId, period}) {
		if(!period) {
			period = 100;
		}
		const url = `${context.rootState.restUrl}/stocks/v1/stock/${stockId}/price-sma/${period}`;

		return fetch(url)
			.then(response => response.json());
	},
};

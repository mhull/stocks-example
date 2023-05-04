export default {
	fetchDailyPriceRocList(context, {date, metric, number}) {
		const url = `${context.rootState.restUrl}/stocks/v1/roc/price/daily/${date}/${metric}/n/${number}`;
		return fetch(url)
			.then(response => response.json());
	},
	fetchDailySmaRocList(context, {date, metric, number}) {
		const url = `${context.rootState.restUrl}/stocks/v1/roc/sma/daily/${date}/${metric}/n/${number}`;
		return fetch(url)
			.then(response => response.json());
	},
	fetchPriceVelocitySmaDailyList(context, {date, metric, number}) {
		const url = `${context.rootState.restUrl}/stocks/v1/roc/price/velocity/sma/daily/${date}/${metric}/n/${number}`;
		return fetch(url)
			.then(response => response.json());
	},
	fetchVolumeVelocityDailyList(context, {date, number, absMin}) {
		const url = `${context.rootState.restUrl}/stocks/v1/roc/volume/daily/${date}/n/${number}/abs-min/${absMin}`;
		return fetch(url)
			.then(response => response.json());
	},
};

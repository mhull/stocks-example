export default {
	fetch(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/company-information/${stockId}`;

		return fetch(url)
			.then(response => response.json())
	},
	syncCompanyInformation(context, stockId) {
		const url = `${context.rootState.restUrl}/stocks/v1/company-information/${stockId}/sync`;

		return fetch(url, {
			method: 'POST',
		})
			.then(response => response.json());
	},
};

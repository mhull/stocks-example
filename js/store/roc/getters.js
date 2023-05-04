export default {
	getDailyPriceRocList(state) {
		return state.dailyPriceRocList;
	},
	getLoadingDailyPriceRocList(state) {
		return state.isLoadingDailyPriceRocList;
	},

	getDailySmaRocList(state) {
		return state.dailySmaRocList;
	},
	getLoadingDailySmaRocList(state) {
		return state.isLoadingDailySmaRocList;
	},

	getPriceVelocitySmaDailyList(state) {
		return state.priceVelocitySmaDailyList;
	},
	getLoadingPriceVelocitySmaDailyList(state) {
		return state.isLoadingPriceVelocitySmaDailyList;
	},

	getVolumeVelocityDailyList(state) {
		return state.volumeVelocityDailyList;
	},
	isLoadingVolumeVelocityDailyList(state) {
		return state.isLoadingVolumeVelocityDailyList;
	},
};

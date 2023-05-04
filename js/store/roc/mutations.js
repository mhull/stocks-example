export default {
	setDailyPriceRocList(state, list) {
		state.dailyPriceRocList = list;
	},
	setLoadingDailyPriceRocList(state, isLoading) {
		state.isLoadingDailyPriceRocList = isLoading;
	},

	setDailySmaRocList(state, list) {
		state.dailySmaRocList = list;
	},
	setLoadingDailySmaRocList(state, isLoading) {
		state.isLoadingDailySmaRocList = isLoading;
	},

	setPriceVelocitySmaDailyList(state, list) {
		state.priceVelocitySmaDailyList = list;
	},
	setLoadingPriceVelocitySmaDailyList(state, isLoading) {
		state.isLoadingPriceVelocitySmaDailyList = isLoading;
	},

	setVolumeVelocityDailyList(state, list) {
		state.volumeVelocityDailyList = list;
	},
	setLoadingVolumeVelocityDailyList(state, isLoading) {
		state.isLoadingVolumeVelocityDailyList = isLoading;
	},
};

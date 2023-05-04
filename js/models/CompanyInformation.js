export default class {
	constructor(data) {
		this.stockId = parseInt(data?.stockId ?? 0);
		this.description = data?.description ?? '';
		this.latestQuarter = data?.latestQuarter ?? '';
		this.analystTargetPrice = parseFloat(data?.analystTargetPrice ?? 0);
		this.peRatio = parseFloat(data?.peRatio ?? 0);
		this.forwardPeRatio = parseFloat(data?.forwardPeRatio ?? 0);
		this.pegRatio = parseFloat(data?.pegRatio ?? 0);
		this.bookValue = parseFloat(data?.bookValue ?? 0);
		this.quarterlyEarningsGrowthYoy = parseFloat(data?.quarterlyEarningsGrowthYoy ?? 0);
		this.quarterlyRevenueGrowthYoy = parseFloat(data?.quarterlyRevenueGrowthYoy ?? 0);
		this.annualHigh = parseFloat(data?.annualHigh ?? 0);
		this.annualLow = parseFloat(data?.annualLow ?? 0);
		this.sma50Day = parseFloat(data?.sma50Day ?? 0);
		this.sma200Day = parseFloat(data?.sma200Day ?? 0);
	}

	getAnalystTargetPriceDifference(price) {
		if(!price) {
			return 0;
		}
		price = parseFloat(price);
		return price - this.analystTargetPrice;
	}
};

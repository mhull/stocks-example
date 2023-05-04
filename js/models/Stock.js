import DateModel from 'stocks/models/Date';

export default class {
	constructor(data) {
		this.id = parseInt(data?.id ?? 0);
		this.name = data?.name ?? '';
		this.symbol = data?.symbol ?? '';
		this.exchange = parseInt(data?.exchange ?? 0);
		this.assetType = data?.assetType ?? '';
		this.ipoDate = data?.ipoDate ?? '';
		this.delistingDate = data?.delistingDate ?? '';
		this.active = Boolean(data?.active ?? false);
	}

	getFullIpoDate() {
		return new DateModel({iso: this.ipoDate}).getFull();
	}

};

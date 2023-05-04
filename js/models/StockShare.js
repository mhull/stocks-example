export default class {
	constructor(data) {
		this.id = parseInt(data?.id ?? 0);
		this.stockId = parseInt(data?.stockId ?? 0);
		this.numberShares = parseFloat(data?.numberShares ?? 0.0 );
		this.datePurchased = data?.datePurchased ?? '';
		this.purchasePrice = parseFloat(data?.purchasePrice ?? 0.0);

		if(!this.stockId) {
			this.stockId = parseInt(data?.stock_id ?? 0);
		}

		if(!this.numberShares) {
			this.numberShares = parseFloat(data?.number_shares ?? 0.0 );
		}

		if(!this.datePurchased) {
			this.datePurchased = data?.date_purchased ?? '';
		}

		if(!this.purchasePrice) {
			this.purchasePrice = parseFloat(data?.purchase_price ?? 0.0);
		}

	}
};

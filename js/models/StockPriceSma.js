export default class {
	constructor(data) {
		this.stockId = parseInt(data?.stockId ?? 0);
		this.items = data?.items ?? [];
	}
}

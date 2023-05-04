export default class {
	constructor(data) {
		this.items = data?.items ?? [];
		this.stockId = data?.stockId ?? 0;
	}
}

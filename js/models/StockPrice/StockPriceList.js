export default class {
	constructor(data) {
		this.stockId = parseInt(data?.stockId ?? 0);
		this.items = data?.items ?? [];
	}

	getLatestItem() {
		let latestItem = null;

		this.items.forEach(item => {

			if(!latestItem || latestItem.date < item.date) {
				latestItem = item;
			}
		});

		return latestItem;
	}

	getPreviousItem(date) {
		let previousItem = null;

		this.items.forEach(item => {
			if(
				(!previousItem && item.date < date) ||
				(previousItem && previousItem.date < item.date && item.date < date)
			) {
				previousItem = item;
			}
		});

		return previousItem;
	}
}

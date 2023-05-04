export default class {
	constructor(data) {
		this.iso = data?.iso ?? '';
		if(this.iso) {
			this.luxon = luxon.DateTime.fromISO(this.iso);
		}
	}

	getFull() {
		return this.luxon?.toFormat('MMMM dd, yyyy') ?? '';
	}
}

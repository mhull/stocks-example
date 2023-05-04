export default class {
	constructor(data) {
		this.id = parseInt(data?.id ?? 0);
		this.name = data?.name ?? '';
		this.slug = data?.slug ?? '';
	}
}

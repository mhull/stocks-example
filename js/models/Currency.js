export function formatUsd(amount) {
	amount = parseFloat(amount);
	if(isNaN(amount)) {
		return '';
	}

	return Intl.NumberFormat("en-US", {
		style: "currency",
		currency: "USD",
	}).format(amount);
}

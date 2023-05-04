<?php

namespace StocksWp\Rest\Stock;

use StocksWp\DataAccess\StockPriceAccess;

class GetPrice {
	public static function execute(\WP_REST_Request $request) {
		$stockId = absint($request->get_param('id') ?? 0);
		return StockPriceAccess::getItems($stockId);
	}
}

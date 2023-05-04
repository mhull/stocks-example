<?php

namespace StocksWp\Rest\StockShare;

use StocksWp\DataAccess\StockShareAccess;

class GetStockShares {
	public static function execute(\WP_REST_Request $request) {
		return StockShareAccess::getAll();
	}
}

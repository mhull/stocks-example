<?php

namespace StocksWp\Rest\Stock;

use StocksWp\DataAccess\StockPriceVelocityAccess;

class GetPriceVelocity {
	public static function execute(\WP_REST_Request $request) {
		$stockId = $request->get_param('id');

		return StockPriceVelocityAccess::getAll($stockId);
	}
}

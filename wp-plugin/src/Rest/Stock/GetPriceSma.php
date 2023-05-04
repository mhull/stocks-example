<?php

namespace StocksWp\Rest\Stock;

use StocksWp\DataAccess\StockPriceSma10Access;
use StocksWp\DataAccess\StockPriceSma100Access;

class GetPriceSma {
	public static function execute(\WP_REST_Request $request) {
		$stockId = $request->get_param('id');

		$period = absint($request->get_param('period') ?: 100);

		if($period === 10) {
			return StockPriceSma10Access::getItems($stockId);
		}

		return StockPriceSma100Access::getItems($stockId);
	}
}

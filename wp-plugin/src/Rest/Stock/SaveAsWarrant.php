<?php

namespace StocksWp\Rest\Stock;

use StocksWp\DataAccess\StockAccess;

class SaveAsWarrant {
	public static function execute(\WP_REST_Request $request) {
		$stockId = $request->get_param('id');
		$isWarrant = rest_sanitize_boolean($request->get_param('isWarrant'));

		if(!$stockId) {
			return false;
		}

		StockAccess::saveAsWarrant($stockId, $isWarrant);

		return StockAccess::isWarrant($stockId);
	}
}

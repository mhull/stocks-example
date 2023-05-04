<?php

namespace StocksWp\Rest\Stock;

use StocksWp\DataAccess\StockAccess;

class Search {
	public static function execute(\WP_REST_Request $request) {
		$name = $request->get_param('name');

		return StockAccess::getSearchResults([
			'name' => $name,
		]);
	}
}

<?php

namespace StocksWp\Rest\Stock;

use Stocks\Stock\StockFactory;

class GetStock {
	public static function execute(\WP_REST_Request $request) {
		$stock_id = absint($request->get_param('id'));

		$post = get_post($stock_id);
		if(!$post) {
			return new \WP_Error('stocks_not_found', 'We couldn\'t find the stock you are looking for', ['status' => 404]);
		}
		return StockFactory::createFromWpPost($post);
	}
}

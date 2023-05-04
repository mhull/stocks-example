<?php

namespace StocksWp\Rest\Roc;

use StocksWp\DataAccess\StockPriceRocAccess;

class GetRocPriceDaily {
	public static function execute(\WP_REST_Request $request) {
		$date = $request->get_param('date');
		$metric = $request->get_param('metric');
		$number = $request->get_param('number');

		return [
			'gain' => StockPriceRocAccess::getBiggestDailyGains($date, $metric, $number),
			'loss' => StockPriceRocAccess::getBiggestDailyLosses($date, $metric, $number),
		];
	}
}

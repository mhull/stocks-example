<?php

namespace StocksWp\Rest\Roc;

use StocksWp\DataAccess\StockPriceVelocitySma100Access;

class GetPriceVelocitySmaDaily {
	public static function execute(\WP_REST_Request $request) {
		$date = $request->get_param('date');
		$metric = $request->get_param('metric');
		$number = $request->get_param('number');

		return [
			'gain' => StockPriceVelocitySma100Access::getBiggestDailyGains($date, $metric, $number)
		];
	}
}

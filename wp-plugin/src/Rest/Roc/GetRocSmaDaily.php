<?php

namespace StocksWp\Rest\Roc;

use StocksWp\DataAccess\StockSmaVelocityAccess;

class GetRocSmaDaily {
	public static function execute(\WP_REST_Request $request) {
		$date = $request->get_param('date');
		$metric = $request->get_param('metric');
		$number = $request->get_param('number');

		return [
			'gain' => StockSmaVelocityAccess::getBiggestDailyGains($date, $metric, $number),
		];
	}
}

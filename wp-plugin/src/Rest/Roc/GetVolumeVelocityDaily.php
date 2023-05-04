<?php

namespace StocksWp\Rest\Roc;

use StocksWp\DataAccess\StockPriceRocAccess;

class GetVolumeVelocityDaily {
	public static function execute(\WP_REST_Request $request) {
		$date = $request->get_param('date');
		$number = $request->get_param('number');
		$absMin = $request->get_param('absMin');

		return StockPriceRocAccess::getBiggestVolumeGains($date, $number, $absMin);
	}
}

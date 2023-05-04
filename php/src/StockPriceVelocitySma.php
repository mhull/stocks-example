<?php

namespace Stocks;

class StockPriceVelocitySma {
	protected static array $metrics = [
		'mean' => [
			'key' => 'mean',
			'label' => 'Mean',
		],
		'median' => [
			'key' => 'median',
			'label' => 'Median',
		],
	];

	public static function isMetric($metric) {
		return in_array($metric, array_keys(static::$metrics));
	}
}

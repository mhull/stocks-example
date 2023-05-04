<?php

namespace Stocks;

class StockSma {
	protected static array $metrics = [
		'mean' => [
			'key' => 'mean',
			'label' => 'Mean',
		],
		'median' => [
			'key' => 'median',
			'label' => 'Median',
		],
		'stdDev' => [
			'key' => 'stdDev',
			'label' => 'Std Dev',
		],
	];

	public static function isMetric($metric) {
		return in_array($metric, array_keys(static::$metrics));
	}
}

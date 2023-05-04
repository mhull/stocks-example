<?php

namespace Stocks;

class StockPrice {
	protected static array $metrics = [
		'median' => [
			'key' => 'median',
			'label' => 'Median',
		],
		'mean' => [
			'key' => 'mean',
			'label' => 'Mean',
		],
		'close' => [
			'key' => 'close',
			'label' => 'Close',
		],
		'adjustedClose' => [
			'key' => 'adjustedClose',
			'label' => 'Adjusted close',
		],
		'adjustedMedian' => [
			'key' => 'adjustedMedian',
			'label' => 'Adjusted median',
		],
		'adjustedMean' => [
			'key' => 'adjustedMean',
			'label' => 'Adjusted mean',
		],
		'open' => [
			'key' => 'open',
			'label' => 'Open',
		],
		'high' => [
			'key' => 'high',
			'label' => 'High',
		],
		'low' => [
			'key' => 'low',
			'label' => 'Low',
		],
	];

	public static function isMetric($metric) {
		return in_array($metric, array_keys(static::$metrics));
	}
}

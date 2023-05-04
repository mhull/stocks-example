<?php

namespace StocksWp\DataAccess;

use StocksWp\DataAccess\StockPriceSmaAccessBase;

class StockPriceSma10Access extends StockPriceSmaAccessBase {
	private const TABLE_NAME = 'stocks__stock_price_sma_10';

	protected static function getTableName() {
		global $wpdb;
		return $wpdb->prefix . static::TABLE_NAME;
	}
}

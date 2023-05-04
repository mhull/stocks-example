<?php

namespace StocksWp;

use StocksWp\DataAccess\CompanyInformationAccess;
use StocksWp\DataAccess\CpiAccess;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceAccelerationAccess;
use StocksWp\DataAccess\StockPriceAccess;
use StocksWp\DataAccess\StockPriceVelocityAccess;
use StocksWp\DataAccess\StockPriceVelocitySma100Access;
use StocksWp\DataAccess\StockPriceSma10Access;
use StocksWp\DataAccess\StockPriceSma100Access;
use StocksWp\DataAccess\StockSmaVelocityAccess;
use StocksWp\DataAccess\StockShareAccess;

class Db {
	public const DB_VERSION_OPTION = 'stocks_db_version';
	public const DB_VERSION = '1.11.0';

	public static function getTableName($tableName) {
		global $wpdb;
		return $wpdb->prefix . $tableName;
	}

	public static function checkVersion() {
		$installed_db_version = get_option(static::DB_VERSION_OPTION, '0.0.0');
		$current_db_version = static::DB_VERSION;

		$needs_update = version_compare($installed_db_version, $current_db_version, '<');

		if($needs_update) {
			static::sync();
		}
	}

	public static function sync() {
		CompanyInformationAccess::syncTable();
		StockAccess::syncTable();
		StockPriceAccess::syncTable();
		StockPriceVelocityAccess::syncTable();
		StockPriceVelocitySma100Access::syncTable();
		StockPriceAccelerationAccess::syncTable();
		StockPriceSma10Access::syncTable();
		StockPriceSma100Access::syncTable();
		StockSmaVelocityAccess::syncTable();
		StockShareAccess::syncTable();
		CpiAccess::syncTable();

		update_option(static::DB_VERSION_OPTION, static::DB_VERSION);
	}
}

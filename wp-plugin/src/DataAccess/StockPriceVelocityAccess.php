<?php

namespace StocksWp\DataAccess;

class StockPriceVelocityAccess {
	private const TABLE_NAME = 'stocks__stock_price_velocity';

	public static function getTableName() {
		global $wpdb;
		return $wpdb->prefix . static::TABLE_NAME;
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`stockId` bigint(20) unsigned NOT NULL,
				`date` date NOT NULL,
				`intraday` float NOT NULL,
				`open` float NOT NULL,
				`high` float NOT NULL,
				`low` float NOT NULL,
				`close` float NOT NULL,
				`adjustedClose` float NOT NULL,
				`median` float NOT NULL,
				`adjustedMedian` float NOT NULL,
				`mean` float NOT NULL,
				`adjustedMean` float NOT NULL,
				`volume` float NOT NULL,
				PRIMARY KEY (`stockId`, `date`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getAll($stockId) {
		global $wpdb;
		$stockId = absint($stockId);

		if(!$stockId) {
			return [];
		}

		$tableName = static::getTableName();

		$query = "SELECT * FROM `$tableName` WHERE `stockId`=%d ORDER BY `date` DESC";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_results($query);
	}

	public static function getLatestDate($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		$query = "SELECT MAX(`date`) FROM {$tableName} WHERE `stockId`=%d";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_var($query);

	}

	public static function getEarliestDate($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		$query = "SELECT MIN(`date`) FROM {$tableName} WHERE `stockId`=%d";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_var($query);

	}

	public static function insertItem($item) {
		if(empty($item['stockId'])) {
			return 0;
		}

		global $wpdb;

		$item = [
			'stockId' => absint($item['stockId'] ?? 0),
			'date' => sanitize_text_field($item['date'] ?? ''),
			'intraday' => (float) $item['intraday'],
			'open' => (float) $item['open'],
			'high' => (float) $item['high'],
			'low' => (float) $item['low'],
			'close' => (float) $item['close'],
			'adjustedClose' => (float) $item['adjustedClose'],
			'median' => (float) $item['median'],
			'adjustedMedian' => (float) $item['adjustedMedian'],
			'mean' => (float) $item['mean'],
			'adjustedMean' => (float) $item['adjustedMean'],
			'volume' => (float) $item['volume'],
		];

		$wpdb->insert(
			static::getTableName(),
			$item,
			['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
		);

		return $wpdb->insert_id;
	}

	public static function deleteItems($stockId) {
		global $wpdb;

		$stockId = absint($stockId);
		if(!$stockId) {
			return;
		}

		$wpdb->delete(
			static::getTableName(),
			['stockId' => $stockId],
			'%d'
		);
	}
}

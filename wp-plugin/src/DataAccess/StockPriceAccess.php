<?php

namespace StocksWp\DataAccess;

use StocksWp\Db;

class StockPriceAccess {
	private const TABLE_NAME = 'stocks__stock_price';

	private static function getTableName() {
		return Db::getTableName(static::TABLE_NAME);
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`stockId` bigint(20) unsigned NOT NULL,
				`date` date NOT NULL,
				`open` float NOT NULL,
				`high` float NOT NULL,
				`low` float NOT NULL,
				`close` float NOT NULL,
				`adjustedClose` float NOT NULL,
				`volume` bigint(20) unsigned NOT NULL,
				`dividendAmount` float NOT NULL,
				`splitCoefficient` float NOT NULL,
				PRIMARY KEY (`stockId`, `date`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getItems(int $stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$query = "SELECT * FROM `{$tableName}` WHERE `stockId`=%d ORDER BY `date` DESC";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_results($query);
	}

	public static function countItems($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		if(!$stockId) {
			return 0;
		}

		$query = "
			SELECT count(`stockId`)
			FROM `{$tableName}`
			WHERE `stockId`=%d
		";

		$query = $wpdb->prepare($query, $stockId);

		return absint($wpdb->get_var($query));
	}

	public static function deleteItems(int $stockId) {
		global $wpdb;

		return $wpdb->delete(
			static::getTableName(),
			['stockId' => $stockId],
			'%d'
		);
	}

	public static function insertItem($item) {
		global $wpdb;

		$data = [
			'stockId' => absint($item['stockId']),
			'date' => sanitize_text_field($item['date']),
			'open' => (float) $item['open'],
			'high' => (float) $item['high'],
			'low' => (float) $item['low'],
			'close' => (float) $item['close'],
			'adjustedClose' => (float) $item['adjustedClose'],
			'volume' => (int) $item['volume'],
			'dividendAmount' => (float) $item['dividendAmount'],
			'splitCoefficient' => (float) $item['splitCoefficient'],
		];

		$formats = [
			'%d', // stock_id
			'%s', // date
			'%s', // open
			'%s', // high
			'%s', // low
			'%s', // close
			'%s', // adjustedClose
			'%d', // volume
			'%s', // dividendAmount
			'%s', // splitCoefficient
		];

		$wpdb->insert(
			static::getTableName(),
			$data,
			$formats
		);

		return $wpdb->insert_id;
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

	public static function getItemsByMaxClosingPrice() {
		global $wpdb;
		$tableName = static::getTableName();

		$query = "SELECT * FROM `{$tableName}`
			WHERE `close`=(SELECT MAX(`close`) FROM `{$tableName}`)";

		return $wpdb->get_results($query);
	}

	public static function getItemsByMinClosingPrice() {
		global $wpdb;
		$tableName = static::getTableName();

		$query = "SELECT * FROM `{$tableName}`
			WHERE `close`=(SELECT MIN(`close`) FROM `{$tableName}`)";

		return $wpdb->get_results($query);
	}
}

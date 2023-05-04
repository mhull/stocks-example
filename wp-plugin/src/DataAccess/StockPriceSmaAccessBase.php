<?php

namespace StocksWp\DataAccess;

abstract class StockPriceSmaAccessBase {
	abstract protected static function getTableName();

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`stockId` bigint(20) unsigned NOT NULL,
				`date` date NOT NULL,
				`mean` float NOT NULL,
				`median` float NOT NULL,
				`stdDev` float NOT NULL,
				PRIMARY KEY (`stockId`, `date`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getLatestDate($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		$query = "SELECT MAX(date) FROM `{$tableName}` WHERE `stockId`=%d";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_var($query);
	}

	public static function getEarliestDate($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		$query = "SELECT MIN(date) FROM `{$tableName}` WHERE `stockId`=%d";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_var($query);
	}

	public static function getItems($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		$query = "
			SELECT * FROM `{$tableName}`
			WHERE `stockId`=%d
			ORDER BY `date` DESC
		";

		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_results($query);
	}

	public static function insertItem($item) {
		global $wpdb;

		$stockId = absint($item['stockId'] ?? 0);
		$date = sanitize_text_field($item['date'] ?? '');
		$mean = (float) ($item['mean'] ?? null);
		$median = (float) ($item['median'] ?? null);
		$stdDev = (float) ($item['stdDev'] ?? null);

		$wpdb->insert(
			static::getTableName(),
			[
				'stockId' => $stockId,
				'date' => $date,
				'mean' => $mean,
				'median' => $median,
				'stdDev' => $stdDev,
			],
			['%d', '%s', '%s', '%s', '%s']
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

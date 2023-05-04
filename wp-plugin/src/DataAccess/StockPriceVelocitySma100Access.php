<?php

namespace StocksWp\DataAccess;

use Stocks\StockPriceVelocitySma;

class StockPriceVelocitySma100Access {
	private const TABLE_NAME = 'stocks__stock_price_velocity_sma_100';

	private static function getTableName() {
		global $wpdb;
		return $wpdb->prefix . static::TABLE_NAME;
	}

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

		$query = "
			SELECT MAX(`date`)
			FROM {$tableName}
			WHERE `stockId`=%d
		";

		$query = $wpdb->prepare($query, $stockId);
		return $wpdb->get_var($query);
	}

	public static function getEarliestDate($stockId) {
		global $wpdb;
		$tableName = static::getTableName();
		$stockId = absint($stockId);

		$query = "
			SELECT MIN(`date`)
			FROM {$tableName}
			WHERE `stockId`=%d
		";

		$query = $wpdb->prepare($query, $stockId);
		return $wpdb->get_var($query);
	}

	public static function insertItem($item) {
		global $wpdb;

		$wpdb->insert(
			static::getTableName(),
			[
				'stockId' => absint($item['stockId'] ?? 0),
				'date' => sanitize_text_field($item['date'] ?? ''),
				'mean' => (float) ($item['mean'] ?? null),
				'median' => (float) ($item['median'] ?? null),
				'stdDev' => (float) ($item['stdDev'] ?? null),
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

	public static function getBiggestDailyGains($date, $metric, $number) {
		global $wpdb;
		$tableName = static::getTableName();

		$date = sanitize_text_field($date);
		$metric = sanitize_text_field($metric);
		$number = absint($number);
		if(!$number) {
			$number = 10;
		}

		if(!StockPriceVelocitySma::isMetric($metric)) {
			return [];
		}

		$query = "
			SELECT * FROM {$tableName}
			WHERE `date`=%s
			AND `${metric}`>0
			ORDER BY `{$metric}` DESC
			LIMIT %d
		";

		$query = $wpdb->prepare($query, $date, $number);
		return $wpdb->get_results($query);
	}
}

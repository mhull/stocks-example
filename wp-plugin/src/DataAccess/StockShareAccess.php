<?php

namespace StocksWp\DataAccess;

use StocksWp\Db;

class StockShareAccess {
	private const TABLE_NAME = 'stocks__stock_shares';

	private function getTableName() {
		return Db::getTableName(static::TABLE_NAME);
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`stockId` bigint(20) unsigned NOT NULL,
				`numberShares` float NOT NULL,
				`datePurchased` date NOT NULL,
				`purchasePrice` float NOT NULL,
				PRIMARY KEY (`id`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getAll() {
		global $wpdb;

		$tableName = static::getTableName();

		$query = "
			SELECT * FROM {$tableName}
			WHERE 1
			ORDER BY `datePurchased` ASC
		";

		return $wpdb->get_results($query);
	}

	public static function getById($id) {
		$id = absint($id);

		global $wpdb;
		$tableName = static::getTableName();

		$query = "SELECT * FROM `{$tableName}` WHERE `id`=%d";

		$query = $wpdb->prepare($query, $id);

		return $wpdb->get_row($query);
	}

	public static function insertItem($item) {
		global $wpdb;

		$item = [
			'stockId' => absint($item['stockId'] ?? 0),
			'numberShares' => (float) ($item['numberShares'] ?? 0),
			'datePurchased' => sanitize_text_field($item['datePurchased']),
			'purchasePrice' => (float) ($item['purchasePrice'] ?? 0),
		];

		$wpdb->insert(
			static::getTableName(),
			$item,
			['%d', '%s', '%s', '%s']
		);

		return $wpdb->insert_id;
	}
}

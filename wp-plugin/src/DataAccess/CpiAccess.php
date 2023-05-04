<?php

namespace StocksWp\DataAccess;

use StocksWp\Db;

class CpiAccess {
	private const TABLE_NAME = 'stocks__cpi';

	private static function getTableName() {
		return Db::getTableName(static::TABLE_NAME);
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`date` date NOT NULL,
				`value` float NOT NULL,
				PRIMARY KEY (`date`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getLatestDate() {
		global $wpdb;

		$tableName = static::getTableName();
		return $wpdb->get_var("SELECT MAX(`date`) FROM {$tableName}");
	}

	public static function insertItem($item) {
		global $wpdb;

		$item = [
			'date' => sanitize_text_field($item['date']),
			'value' => (float) $item['value'],
		];

		$wpdb->insert(
			static::getTableName(),
			$item,
		);

		return $wpdb->insert_id;
	}

	public static function getAll() {
		global $wpdb;

		$tableName = static::getTableName();
		$query = "
			SELECT * FROM {$tableName}
			WHERE 1
			ORDER BY `date` DESC
		";

		return $wpdb->get_results($query);
	}
}

<?php

namespace StocksWp\DataAccess;

use Stocks\Stock\StockFactory;

class StockAccess {
	public static function getTableName() {
		global $wpdb;
		return $wpdb->prefix . 'stocks__stock';
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`id` bigint(20) unsigned NOT NULL,
				`active` tinyint(1) unsigned NOT NULL,
				`exchange` bigint(20) unsigned NOT NULL,
				`symbol` tinytext NOT NULL,
				`assetType` tinytext NOT NULL,
				`ipoDate` date NOT NULL,
				`delistingDate` date NOT NULL,
				`isWarrant` tinyint(1) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getAll() {
		return get_posts([
			'post_type' => 'stock',
			'posts_per_page' => -1,
			'orderby' => 'ID',
			'order' => 'asc',
		]);
	}

	public static function getAllIds() {
		global $wpdb;
		$tableName = $wpdb->prefix . 'posts';

		$query = "
			SELECT ID FROM `{$tableName}`
			WHERE `post_type`='stock'
			ORDER BY ID ASC
		";

		return $wpdb->get_col($query);
	}

	public static function getSymbolById($stockId) {
		global $wpdb;
		$tableName = $wpdb->prefix . 'postmeta';

		$stockId = absint($stockId);

		$query = "SELECT `meta_value`
			FROM {$tableName}
			WHERE `meta_key`='symbol'
			AND `post_id`=%d
		";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_var($query);
	}

	public static function insertItem($item) {
		$name = sanitize_text_field($item['name'] ?? '');

		$post_id = wp_insert_post([
			'post_type' => 'stock',
			'post_title' => $name,
			'post_status' => 'publish',
		]);

		$item['id'] = $post_id;

		static::updateItem($item);

		return $post_id;
	}

	public static function updateItem($item) {
		$stock_id = absint($item['id'] ?? 0);
		$name = sanitize_text_field($item['name'] ?? '');
		$active = ((bool) $item['active'] ?? '') ? 1 : 0;
		$exchange_id = absint($item['exchange'] ?? 0);
		$symbol = sanitize_text_field($item['symbol'] ?? '');
		$assetType = sanitize_text_field($item['assetType'] ?? '');
		$ipoDate = sanitize_text_field($item['ipoDate'] ?? '');
		$delistingDate = sanitize_text_field($item['delistingDate'] ?? '');

		wp_update_post([
			'ID' => $stock_id,
			'post_title' => $name,
		]);

		update_post_meta($stock_id, 'active', $active);
		update_post_meta($stock_id, 'exchange', $exchange_id);
		update_post_meta($stock_id, 'symbol', $symbol);
		update_post_meta($stock_id, 'assetType', $assetType);
		update_post_meta($stock_id, 'ipoDate', $ipoDate);

		if($delistingDate) {
			update_post_meta($stock_id, 'delistingDate', $delistingDate);
		}
	}

	public static function getSearchResults($args) {
		global $wpdb;

		$name = sanitize_text_field($args['name']);

		$query = "
			SELECT DISTINCT ID
			FROM `{$wpdb->prefix}posts` as posts
				JOIN `{$wpdb->prefix}postmeta` as meta
				ON posts.ID=meta.post_id
			WHERE posts.post_title LIKE '%%%s%%'
			OR (
				meta.meta_key='symbol'
				AND meta.meta_value='%s'
			)
			LIMIT 100;
		";

		$query = $wpdb->prepare($query, $name, $name);


		$post_ids = $wpdb->get_col($query);

		if(!$post_ids) {
			return [];
		}

		return array_map(function($post_id) {
			return StockFactory::createFromWpPostId($post_id);
		}, $post_ids);
	}

	public static function getStockIdBySymbol($symbol) {
		global $wpdb;
		$tableName = $wpdb->prefix . 'postmeta';

		$symbol = sanitize_text_field($symbol);

		$query = "SELECT post_id FROM {$tableName} WHERE meta_key='symbol' AND meta_value=%s";
		$query = $wpdb->prepare($query, $symbol);

		return $wpdb->get_var($query);
	}

	public static function isWarrant($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$query = "SELECT `isWarrant` FROM {$tableName} WHERE `id`=%d";
		$query = $wpdb->prepare($query, $stockId);

		return (bool) $wpdb->get_var($query);
	}

	public static function saveAsWarrant($stockId, $isWarrant) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);
		$isWarrant = (bool) $isWarrant ? 1 : 0;

		if(!$stockId) {
			return;
		}

		$row_exists_query = $wpdb->prepare("SELECT `id` FROM {$tableName} WHERE `id`=%d", $stockId);
		$row_exists = (bool) $wpdb->get_var($row_exists_query);

		if($row_exists) {
			$wpdb->update(
				$tableName,
				['isWarrant' => $isWarrant],
				['id' => $stockId],
				'%d'
			);
			return;
		}

		$wpdb->insert(
			$tableName,
			['id' => $stockId, 'isWarrant' => $isWarrant],
			['%d', '%d']
		);
	}
}

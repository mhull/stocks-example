<?php

namespace StocksWp\DataAccess;

use Stocks\StockPrice;

class StockPriceRocAccess {
	protected static function getStockTable() {
		global $wpdb;
		return StockAccess::getTableName();
	}

	protected static function getVelocityTable() {
		return StockPriceVelocityAccess::getTableName();
	}

	protected static function getAccelerationTable() {
		return StockPriceAccelerationAccess::getTableName();
	}

	public static function getBiggestDailyGains($date, $metric, $number) {
		global $wpdb;
		$stocksTable = StockAccess::getTableName();
		$velocityTable = static::getVelocityTable();

		$date = sanitize_text_field($date);
		$metric = sanitize_text_field($metric);
		$number = absint($number);
		if(!$number) {
			$number = 10;
		}

		if(!StockPrice::isMetric($metric)) {
			return [];
		}

		$query = "
			SELECT *
			FROM {$velocityTable}
			WHERE
			`stockId` NOT IN (SELECT `id` FROM `{$stocksTable}` WHERE `isWarrant` = 1)
			AND `date`=%s
			AND `{$metric}` > 0
			ORDER BY `{$metric}` DESC
			LIMIT %d";

		$query = $wpdb->prepare($query, $date, $number);

		return $wpdb->get_results($query);
	}

	public static function getBiggestDailyLosses($date, $metric, $number) {
		global $wpdb;
		$velocityTable = static::getVelocityTable();
		$stocksTable = StockAccess::getTableName();

		$date = sanitize_text_field($date);
		$metric = sanitize_text_field($metric);
		$number = absint($number);
		if(!$number) {
			$number = 10;
		}

		if(!StockPrice::isMetric($metric)) {
			return [];
		}

		$query = "
			SELECT *
			FROM {$velocityTable}
			WHERE `stockId` NOT IN (SELECT `id` FROM {$stocksTable} WHERE `isWarrant` = 1)
			AND `date`=%s
			AND `{$metric}` < 0
			ORDER BY `{$metric}` ASC
			LIMIT %d";

		$query = $wpdb->prepare($query, $date, $number);

		return $wpdb->get_results($query);
	}

	public static function getBiggestVolumeGains($date, $number, $absMin) {
		global $wpdb;
		$stockTable = static::getStockTable();
		$velocityTable = static::getVelocityTable();

		$date = sanitize_text_field($date);
		$number = (int) $number;
		$absMin = (float) $absMin;

		if(!$date || !$number || !$absMin) {
			return [];
		}

		$query = "
			SELECT * FROM `{$velocityTable}`
			WHERE
			`stockId` NOT IN (SELECT `id` FROM {$stockTable} WHERE `isWarrant`=1)
			AND `date`=%s
			AND (`adjustedClose` >= {$absMin} OR `adjustedClose` <= -{$absMin})
			AND `volume` > 0
			ORDER BY volume DESC
			LIMIT %d
		";

		$query = $wpdb->prepare($query, $date, $number);

		return $wpdb->get_results($query);
	}
}

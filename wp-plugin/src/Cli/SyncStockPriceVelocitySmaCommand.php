<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStockPriceVelocitySmaService;

class SyncStockPriceVelocitySmaCommand {
	/**
	 * Sync stock price velocity SMA
	 *
	 * ## OPTIONS
	 *
	 * [--all]
	 * : Sync price velocity SMA for all stocks (big operation)
	 *
	 * [--latest]
	 * : Sync only the right tail (time-wise) when syncing all
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stock-price-velocity-sma --all --latest
	 */
	public static function execute($_1, $_2) {
		$syncAll = $_2['all'] ?? false;
		$latest = $_2['latest'] ?? false;
		$startId = $_2['start-id'] ?? 0;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockPriceVelocitySmaService::syncAll([
				'latest' => $latest,
				'startId' => $startId,
			]);
		}
	}
}

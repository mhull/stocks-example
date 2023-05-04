<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStockPriceAccelerationService;

class SyncStockPriceAccelerationCommand {
	/**
	 * Sync stock price accelerations
	 *
	 * ## OPTIONS
	 *
	 * [--id=<id>]
	 * : The ID of the stock to sync price acceleration
	 *
	 * [--all]
	 * : Sync price accelerations for all stocks (big operation)
	 *
	 * [--latest]
	 * : Sync only the right tail (time-wise) when syncing all
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stock-price-acceleration --id=123
	 *     wp stocks sync-stock-price-acceleration --all
	 */
	public static function execute($_1, $_2) {
		$stockId = $_2['id'] ?? 0;
		$syncAll = $_2['all'] ?? false;
		$startId = $_2['start-id'] ?? 0;
		$latest = $_2['latest'] ?? false;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockPriceAccelerationService::syncAll([
				'startId' => $startId,
				'latest' => $latest,
			]);
			return;
		}

		$syncService = new SyncStockPriceAccelerationService(['id' => $stockId]);
		$syncService->sync();
	}
}

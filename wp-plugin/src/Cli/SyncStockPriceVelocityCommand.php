<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStockPriceVelocityService;

class SyncStockPriceVelocityCommand {
	/**
	 * Sync stock price velocities
	 *
	 * ## OPTIONS
	 *
	 * [--id=<id>]
	 * : The ID of the stock to sync price velocity
	 *
	 * [--all]
	 * : Sync price velocities for all stocks (big operation)
	 *
	 * [--latest]
	 * : Sync only the right tail (time-wise) when syncing all
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stock-price-velocity --id=123
	 *     wp stocks sync-stock-price-velocity --all
	 */
	public static function execute($_1, $_2) {
		$id = $_2['id'] ?? 0;
		$syncAll = $_2['all'] ?? false;
		$startId = $_2['start-id'] ?? 0;
		$latest = $_2['latest'] ?? false;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockPriceVelocityService::syncAll([
				'startId' => $startId,
				'latest' => $latest,
			]);
			return;
		}

		$syncService = new SyncStockPriceVelocityService(['id' => $id]);
		$syncService->sync();
	}
}

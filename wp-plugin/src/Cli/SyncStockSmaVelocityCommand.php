<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStockSmaVelocityService;

class SyncStockSmaVelocityCommand {
	/**
	 * Sync stock SMA (Simple moving average) velocity
	 *
	 * ## OPTIONS
	 *
	 * [--all]
	 * : Sync SMA velocity for all stocks (big operation)
	 *
	 * [--latest]
	 * : Sync only latest (right tail on the timeline) SMA velocity when syncing all
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * @param $_1
	 * @param $_2
	 */
	public static function execute($_1, $_2) {
		$syncAll = $_2['all'] ?? false;
		$latest = $_2['latest'] ?? false;
		$startId = $_2['start-id'] ?? 0;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockSmaVelocityService::syncAll([
				'startId' => $startId,
				'latest' => $latest,
			]);
			return;
		}
	}
}

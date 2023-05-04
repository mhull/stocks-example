<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStockPriceSmaService;

class SyncStockPriceSmaCommand {
	/**
	 * Sync stock price SMA (Simple moving average)
	 *
	 * ## OPTIONS
	 *
	 * [--period=<10|100>]
	 * : The number of days to use in calculating price SMA (default: 100)
	 *
	 * [--id=<id>]
	 * : The ID of the stock to sync SMA data for
	 *
	 * [--symbol=<symbol>]
	 * : The symbol of the stock to sync SMA data for
	 *
	 * [--all]
	 * : Sync SMA for all stocks (big operation)
	 *
	 * [--latest]
	 * : Sync only latest (right tail on the timeline) SMA when syncing all
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * [--download]
	 * : Whether to download SMA data from API (as opposed to calculating based on price data)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stock-price-sma --id=123
	 */
	public static function execute($_1, $_2) {
		$id = $_2['id'] ?? 0;
		$symbol = $_2['symbol'] ?? '';
		$syncAll = $_2['all'] ?? false;
		$latest = $_2['latest'] ?? false;
		$startId = $_2['start-id'] ?? 0;
		$download = $_2['download'] ?? false;

		$period = $_2['period'] ?? 100;

		if(!in_array($period, [10, 100])) {
			\WP_CLI::error('Period must be 10 or 100');
			return;
		}

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockPriceSmaService::syncAll([
				'period' => $period,
				'startId' => $startId,
				'download' => $download,
				'latest' => $latest,
			]);
			return;
		}

		$syncService = new SyncStockPriceSmaService([
			'period' => $period,
			'id' => $id,
			'symbol' => $symbol,
			'download' => $download,
		]);
		$syncService->sync();
	}
}

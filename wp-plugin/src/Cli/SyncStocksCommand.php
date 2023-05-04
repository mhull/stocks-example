<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncStocksService;

class SyncStocksCommand {
	/**
	 * Sync stocks and exchanges data
	 *
	 * ## OPTIONS
	 *
	 * [--skip-download]
	 * : Whether to skip downloading the stocks listing CSV file and use the current one instead
	 *
	 * [--latest]
	 * : Whether to bypass updating stock basic data (which probably hasn't changed) and only insert new stocks
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stocks
	 */
	public static function execute($_1, $_2) {
		$skipDownload = $_2['skip-download'] ?? false;
		$latest = $_2['latest'] ?? false;

		echo "Syncing stocks..." . PHP_EOL;

		$syncService = new SyncStocksService([
			'skipDownload' => $skipDownload,
			'latest' => $latest,
		]);

		$syncService->sync();
	}
}

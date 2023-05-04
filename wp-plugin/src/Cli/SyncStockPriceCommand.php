<?php

namespace StocksWp\Cli;

use Stocks\Stock\StockFactory;
use Stocks\Service\SyncStockPriceService;

class SyncStockPriceCommand {
	/**
	 * Get stock historical price data
	 *
	 * ## OPTIONS
	 *
	 * [--id=<id>]
	 * : The ID of the stock to get the price history for
	 *
	 * [--symbol=<symbol>]
	 * : The symbol of the stock to get the price history for. Ignored if --id is given (case-insensitive)
	 *
	 * [--compact]
	 * : Fetch the compact version of the price history (as opposed to full by default)
	 *
	 * [--number=<number>]
	 * : How many days' worth of prices to sync. If set, then
	 *     * --compact is ignored
	 *     * Price tails are synced (i.e. before and after current data points) but no gaps within current data are filled
	 *
	 * [--all]
	 * : Sync price histories for all stocks (big operation). Note this forces --compact to be true
	 *
	 * [--latest]
	 * : Whether to sync only the right tail and not the left, timeline-wise, when syncing all (saves a lot of processing time if left tail has been synced)
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-stock-price --symbol=ibm --compact
	 */
	public static function execute($_1, $_2) {
		$id = $_2['id'] ?? 0;
		$symbol = $_2['symbol'] ?? '';
		$compact = $_2['compact'] ?? false;
		$number = $_2['number'] ?? 0;
		$latest = $_2['latest'] ?? 0;

		$syncAll = $_2['all'] ?? false;
		$startId = $_2['start-id'] ?? 0;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');
			SyncStockPriceService::syncAll([
				'startId' => $startId,
				'number' => $number,
				'latest' => $latest,
			]);
			return;
		}

		$stock = null;

		if($id) {
			$stock = StockFactory::createFromWPPostId($id);
		} elseif($symbol) {
			$stock = StockFactory::createFromSymbol($symbol);
		}

		if(!$stock->id) {
			\WP_CLI::error("Stock not found. Try syncing stocks first using `wp stocks sync-stocks`");
			return;
		}

		echo "Syncing stock price for {$stock->symbol}..." . PHP_EOL;

		$syncPriceService = new SyncStockPriceService([
			'id' => $stock->id,
			'compact' => $compact,
			'number' => $number,
		]);
		$syncPriceService->sync();
	}
}

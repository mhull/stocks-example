<?php

namespace Stocks\Service;

use Stocks\Exchange\ExchangeFactory;
use Stocks\Stock\StockFactory;
use Stocks\VendorApi\AlphaVantageSyncStocks;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\ExchangeAccess;

class SyncStocksService {
	/**
	 * @var AlphaVantageSyncStocks
	 */
	protected $apiService = null;

	/**
	 * @var bool
	 */
	protected $skipDownload = false;

	/** @var bool  */
	protected $latest = false;

	public function __construct($_1) {
		$this->apiService = new AlphaVantageSyncStocks;
		$this->skipDownload = (bool) ($_1['skipDownload'] ?? false);
		$this->latest = (bool) ($_1['latest'] ?? false);
	}

	public function sync() {
		if(!$this->skipDownload) {
			$this->apiService->sync();
		}

		$activeStocks = $this->apiService->getActiveStocksData();

		$progress = null;

		if(stocks_is_cli()) {
			$progress = \WP_CLI\Utils\make_progress_bar( 'Syncing active stocks...' . PHP_EOL, count($activeStocks) );
		}

		$new_stocks = [];
		$new_exchanges = [];

		foreach($activeStocks as $stockCsv) {
			$exchange = ExchangeFactory::createFromName($stockCsv['exchange']);

			if(!$exchange->id) {
				$exchange_id = ExchangeAccess::insertItem(['name' => $stockCsv['exchange']]);
				$exchange = ExchangeFactory::createFromWpPostId($exchange_id);

				$new_exchanges[] = $exchange;
			}

			$stockId = StockAccess::getStockIdBySymbol($stockCsv['symbol']);

//			$stock = StockFactory::createFromWpPostMeta([
//				'symbol' => $stockCsv['symbol'],
				// Commented out for now since symbols are unique and this can cause stocks to get re-inserted if exchange
				// has been removed for any reason (e.g. deleting the post)
//				'exchange' => $exchange->id,
//			]);

			$sync_data = [
				'id' => $stockId,
				'name' => $stockCsv['name'] ?: $stockCsv['symbol'],
				'active' => $stockCsv['status'] === 'Active',
				'exchange' => $exchange->id,
				'symbol' => $stockCsv['symbol'],
				'assetType' => $stockCsv['assetType'],
				'ipoDate' => $stockCsv['ipoDate'],
			];

			if('null' !== $stockCsv['delistingDate']) {
				$sync_data['delistingDate'] = $stockCsv['delistingDate'];
			}

			if(!$stockId) {
				$stockId = StockAccess::insertItem($sync_data);
				$stock = StockFactory::createFromWPPostId($stockId);

				$new_stocks[] = $stock;
			} else {
				if(!$this->latest) {
					StockAccess::updateItem($sync_data);
				}
			}

			if(stocks_is_cli()) {
				$progress->tick();
			}
		}

		if(stocks_is_cli()) {
			$progress->finish();
		}

		$inactiveStocks = $this->apiService->getInactiveStocksData();

		$delistedStocks = [];

		if(stocks_is_cli()) {
			$progress = \WP_CLI\Utils\make_progress_bar('Syncing delisted stocks...' . PHP_EOL, count($inactiveStocks));
		}

		foreach($inactiveStocks as $stockCsv) {
			if(stocks_is_cli()) {
				$progress->tick();
			}

			if(
				'delisted' !== strtolower($stockCsv['status']) ||
				'null' === strtolower($stockCsv['delistingDate'])
			) {
				continue;
			}

			$delistedStockId = StockAccess::getStockIdBySymbol($stockCsv['symbol']);

			if(!$delistedStockId) {
				continue;
			}

			// Fixing issue where too many stocks got marked as inactive due to old symbols matching up
			update_post_meta($delistedStockId, 'active', 1);
			delete_post_meta($delistedStockId, 'delistingDate');

			// Using ipoDate to make sure stocks actually match on not just the symbol
			$delistedStock = StockFactory::createFromWpPostMeta([
				'symbol' => $stockCsv['symbol'],
				'ipoDate' => $stockCsv['ipoDate']
			]);

			if(!$delistedStock->id) {
				continue;
			}

			$delistedStocks[] = $delistedStock;

			update_post_meta($delistedStock->id, 'active', 0);
			update_post_meta($delistedStock->id, 'delistingDate', $stockCsv['delistingDate']);
		}

		if(!stocks_is_cli()) {
			return;
		}

		$progress->finish();

		$count_new_stocks = count($new_stocks);
		$count_new_exchanges = count($new_exchanges);
		$countDelistedStocks = count($delistedStocks);

		echo "New stocks: {$count_new_stocks}" . PHP_EOL;
		if($new_stocks) {
			foreach($new_stocks as $new_stock) {
				echo "{$new_stock->name} ({$new_stock->id})" . PHP_EOL;
			}
		}

		echo "New exchanges: {$count_new_exchanges}" . PHP_EOL;
		if($new_exchanges) {
			foreach($new_exchanges as $new_exchange) {
				echo "{$new_exchange->name} ({$new_exchange->id})" . PHP_EOL;
			}
		}

		echo "Delisted stocks: {$countDelistedStocks}" . PHP_EOL;
		if($delistedStocks) {
			foreach($delistedStocks as $delistedStock) {
				echo "{$delistedStock->name} ({$delistedStock->id})" . PHP_EOL;
			}
		}
	}
}

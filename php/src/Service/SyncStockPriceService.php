<?php

namespace Stocks\Service;

use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceAccess;
use Stocks\VendorApi\AlphaVantageGetStockPrice;

class SyncStockPriceService {
	private $id = 0;
	private $symbol = '';
	private $number = 0;
	private $refresh = false;
	private $compact = 0;
	private $latest = false;
	private $apiService = null;

	public function __construct($_1) {
		$this->id = $_1['id'] ?? 0;
		$this->symbol = StockAccess::getSymbolById($this->id);
		$this->number = (int) ($_1['number'] ?? 0);
		$this->refresh = (bool) ($_1['refresh'] ?? false);
		$this->compact = (bool) ($_1['compact'] ?? false);
		$this->latest = (bool) ($_1['latest'] ?? false);

		$this->apiService = new AlphaVantageGetStockPrice([
			'symbol' => $this->symbol,
			'compact' => $this->compact,
		]);
	}

	public function sync() {
		if(!$this->id || !$this->symbol) {
			return;
		}

		$priceData = $this->apiService->getData();

		if(!$priceData) {
			return;
		}

		if(is_string($priceData)) {
			error_log('Issue with price data: ' . $this->symbol . ' (' . $this->id . ') : ' . $priceData);
			return;
		}

		if($this->refresh) {
			StockPriceAccess::deleteItems($this->id);
		}

		if($this->number) {
			$priceData = array_slice($priceData, 0, $this->number);
		}

		$latestDate = StockPriceAccess::getLatestDate($this->id);
		$earliestDate = null;

		if(!$this->latest) {
			$earliestDate = StockPriceAccess::getEarliestDate($this->id);
		}

		foreach($priceData as $priceItem) {
			// If --number is set, then only add new items beyond current endpoints
			if($this->number) {
				if(
					$earliestDate && $latestDate &&
					$earliestDate <= $priceItem['date'] && $priceItem['date'] <= $latestDate
				) {
					continue;
				}
			}

			// If --latest is set, then only add latest items
			if($this->latest && $latestDate && $priceItem['date'] <= $latestDate) {
				break;
			}

			$priceItem['stockId'] = $this->id;
			StockPriceAccess::insertItem($priceItem);
		}
	}

	public static function syncAll($args = []) {
		$startId = (int) ($args['startId'] ?? 0);
		$number = (int) ($args['number'] ?? 0);
		$latest = (bool) ($args['latest'] ?? false);

		$stockIds = StockAccess::getAllIds();

		foreach($stockIds as $stockId) {
			if($startId && $stockId < $startId) {
				continue;
			}

			echo PHP_EOL . "Syncing stock price: {$stockId}" . PHP_EOL;

			$static = new static([
				'id' => $stockId,
				'compact' => ! (bool) $number,
				'number' => $number,
				'latest' => $latest,
			]);
			$static->sync();
			$static->apiService->sleep();
		}
	}
}

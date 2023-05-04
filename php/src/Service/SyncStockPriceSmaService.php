<?php

namespace Stocks\Service;

use Stocks\NumberSet;
use Stocks\VendorApi\AlphaVantageSyncStockSma;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceAccess;
use StocksWp\DataAccess\StockPriceSma100Access;
use StocksWp\DataAccess\StockPriceSma10Access;

class SyncStockPriceSmaService {
	private $period = 100;
	private $id = 0;
	private $symbol = '';
	private $refresh = false;
	private $download = false;
	private $latest = false;

	/** @var AlphaVantageSyncStockSma */
	private $apiService;

	public function __construct($_1) {
		$this->period = (int) ($_1['period'] ?? 100);
		$this->id = $_1['id'] ?? 0;
		$this->symbol = $_1['symbol'] ?? '';
		$this->refresh = (bool) ($_1['refresh'] ?? false);
		$this->download = $_1['download'] ?? false;
		$this->latest = (bool) ($_1['latest'] ?? false);

		if(!$this->id && $this->symbol) {
			$this->id = StockAccess::getStockIdBySymbol($this->symbol);
		}
		if(!$this->symbol && $this->id) {
			$this->symbol = get_post_meta($this->id, 'symbol', true);
		}

		$this->apiService = new AlphaVantageSyncStockSma(['symbol' => $this->symbol]);
	}

	public function sync() {
		if($this->download) {
			$this->syncFromApi();
			return;
		}

		$this->syncFromPriceData();
	}

	public static function syncAll($_1) {
		$period = (int) ($_1['period'] ?? 100);
		$startId = absint($_1['startId'] ?? 0);
		$download = absint($_1['download'] ?? false);
		$latest = (bool) ($_1['latest'] ?? false);

		if(!$period) {
			return;
		}

		$stockIds = StockAccess::getAllIds();

		foreach($stockIds as $stockId) {
			if($startId && $stockId < $startId) {
				continue;
			}

			echo PHP_EOL . "Syncing stock price SMA: {$stockId}" . PHP_EOL;

			$syncService = new static([
				'period' => $period,
				'id' => $stockId,
				'download' => $download,
				'latest' => $latest,
			]);
			$syncService->sync();

			if($download) {
				usleep($syncService->apiService::API_MIN_REQUEST_TIME);
			}
		}
	}

	public function syncFromApi() {
		$smaData = $this->apiService->getData();

		if(!$smaData) {
			return;
		}

		if(is_string($smaData)) {
			error_log('Issue with SMA data: ' . $this->symbol . ' (' . $this->id . ') : ' . $smaData);
			return;
		}

		// Only deal with the last {$period} items, since the API endpoint has no 'compact' option
		$smaData = array_slice($smaData, 0, $this->period);

		$latestDate = StockPriceSma100Access::getLatestDate($this->id);

		foreach($smaData as $item) {
			if($latestDate && $item['date'] <= $latestDate) {
				break;
			}

			$item['stockId'] = $this->id;
			$item['mean'] = $item['value'];
			StockPriceSma100Access::insertItem($item);
		}
	}

	public function syncFromPriceData() {
		if(!$this->period) {
			return;
		}

		$prices = StockPriceAccess::getItems($this->id);

		if(!$prices) {
			return;
		}

		$accessClass = StockPriceSma100Access::class;

		if($this->period === 10) {
			$accessClass = StockPriceSma10Access::class;
		}

		if($this->refresh) {
			$accessClass::deleteItems($this->id);
		}

		$latestDate = $accessClass::getLatestDate($this->id);
		$earliestDate = null;

		if(!$this->latest) {
			$earliestDate = $accessClass::getEarliestDate($this->id);
		}

		while(count($prices) >= $this->period) {
			$calcPrices = array_slice($prices, 0, $this->period);
			$priceItem = array_shift($prices);
			$date = $priceItem->date;

			if(
				$earliestDate && $latestDate &&
				$earliestDate <= $date && $date <= $latestDate
			) {
				continue;
			}

			if($this->latest && $latestDate && $date <= $latestDate) {
				break;
			}

			$calcValues = array_map('floatval', array_column($calcPrices, 'adjustedClose'));

			$numberSet = new NumberSet(['numbers' => $calcValues]);

			$mean = $numberSet->getMean();
			$median = $numberSet->getMedian();
			$stdDev = $numberSet->getStdDev();

			$accessClass::insertItem([
				'stockId' => $this->id,
				'date' => $date,
				'mean' => $mean,
				'median' => $median,
				'stdDev' => $stdDev,
			]);
		}
	}
}

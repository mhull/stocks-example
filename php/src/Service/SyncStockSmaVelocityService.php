<?php

namespace Stocks\Service;

use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceSma100Access;
use StocksWp\DataAccess\StockSmaVelocityAccess;

class SyncStockSmaVelocityService {
	private $id = 0;
	private $startId = 0;
	private $latest = false;

	public function __construct($_1) {
		$this->id = (int) ($_1['id'] ?? 0);
		$this->refresh = (bool) ($_1['refresh'] ?? false);
		$this->startId = (int) ($_1['startId'] ?? 0);
		$this->latest = (bool) ($_1['latest'] ?? 0);
	}

	public function sync() {
		if($this->startId && $this->id < $this->startId) {
			return;
		}

		$smaItems = StockPriceSma100Access::getItems($this->id);

		if(!$smaItems) {
			return;
		}

		if($this->refresh) {
			StockSmaVelocityAccess::deleteItems($this->id);
		}

		$latestDate = StockSmaVelocityAccess::getLatestDate($this->id);
		$earliestDate = null;

		if(!$this->latest) {
			$earliestDate = StockSmaVelocityAccess::getEarliestDate($this->id);
		}

		foreach($smaItems as $index => $smaItem) {
			$smaItem = (array) $smaItem;

			if(
				$earliestDate && $latestDate &&
				$earliestDate <= $smaItem['date'] &&
				$smaItem['date'] <= $latestDate
			) {
				continue;
			}

			if($this->latest && $latestDate && $smaItem['date'] <= $latestDate) {
				break;
			}

			$previousItem = $smaItems[$index+1] ?? false;

			if(!$previousItem) {
				break;
			}

			$previousItem = (array) $previousItem;

			$date = $smaItem['date'];
			$median = (float) $smaItem['median'];
			$mean = (float) $smaItem['mean'];
			$stdDev = (float) $smaItem['stdDev'];

			$previousMedian = (float) $previousItem['median'];
			$previousMean = (float) $previousItem['mean'];
			$previousStdDev = (float) $previousItem['stdDev'];

			$medianRoc = $previousMedian === 0.0 ?
				1 :
				($median - $previousMedian) / $previousMedian;

			$meanRoc = $previousMean === 0.0 ?
				1 :
				($mean - $previousMean) / $previousMean;

			$stdDevRoc = $previousStdDev === 0.0 ?
				1 :
				($stdDev - $previousStdDev) / $previousStdDev;

			StockSmaVelocityAccess::insertItem([
				'stockId' => $this->id,
				'date' => $date,
				'mean' => $meanRoc,
				'median' => $medianRoc,
				'stdDev' => $stdDevRoc,
			]);
		}
	}

	public static function syncAll($_1) {
		$stockIds = StockAccess::getAllIds();

		foreach($stockIds as $stockId) {
			$_1['id'] = $stockId;

			echo PHP_EOL . "Syncing stock SMA velocity: {$stockId}" . PHP_EOL;

			$syncService = new static($_1);
			$syncService->sync();
		}
	}
}

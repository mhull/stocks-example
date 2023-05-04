<?php

namespace Stocks\Service;

use Stocks\NumberSet;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceVelocityAccess;
use StocksWp\DataAccess\StockPriceVelocitySma100Access;

class SyncStockPriceVelocitySmaService {
	private $id = 0;
	private $startId = 0;
	private $latest = false;

	public function __construct($_1) {
		$this->id = (int) ($_1['id'] ?? 0);
		$this->refresh = (bool) ($_1['refresh'] ?? false);
		$this->startId = (int) ($_1['startId'] ?? 0);
		$this->latest = (bool) ($_1['latest'] ?? false);
	}

	public function sync() {
		if($this->startId && $this->id < $this->startId) {
			return;
		}

		$velocityItems = StockPriceVelocityAccess::getAll($this->id);

		if(!$velocityItems) {
			return;
		}

		if($this->refresh) {
			StockPriceVelocitySma100Access::deleteItems($this->id);
		}

		$latestDate = StockPriceVelocitySma100Access::getLatestDate($this->id);
		$earliestDate = null;

		if(!$this->latest) {
			$earliestDate = StockPriceVelocitySma100Access::getEarliestDate($this->id);
		}

		while(count($velocityItems) >= 100) {
			$calcVelocities = array_slice($velocityItems, 0, 100);
			$velocityItem = array_shift($velocityItems);
			$velocityItem = (array) $velocityItem;

			if(
				$earliestDate && $latestDate &&
				$earliestDate <= $velocityItem['date'] &&
				$velocityItem['date'] <= $latestDate
			) {
				continue;
			}

			if($this->latest && $latestDate && $velocityItem['date'] <= $latestDate) {
				break;
			}

			$calcVelocities = array_map('floatval', array_column($calcVelocities, 'adjustedMean'));

			$numberSet = new NumberSet(['numbers' => $calcVelocities]);

			$mean = $numberSet->getMean();
			$median = $numberSet->getMedian();
			$stdDev = $numberSet->getStdDev();

			StockPriceVelocitySma100Access::insertItem([
				'stockId' => $this->id,
				'date' => $velocityItem['date'],
				'mean' => $mean,
				'median' => $median,
				'stdDev' => $stdDev,
			]);
		}
	}

	public static function syncAll($_1) {
		$stockIds = StockAccess::getAllIds();

		foreach($stockIds as $stockId) {

			echo PHP_EOL . "Syncing stock price velocity SMA: {$stockId}" . PHP_EOL;

			$_1['id'] = $stockId;
			$syncService = new static($_1);
			$syncService->sync();
		}
	}
}

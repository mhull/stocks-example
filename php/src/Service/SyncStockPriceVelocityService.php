<?php

namespace Stocks\Service;

use Stocks\NumberSet;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceAccess;
use StocksWp\DataAccess\StockPriceVelocityAccess;

class SyncStockPriceVelocityService {
	protected $stockId = 0;
	protected $refresh = false;
	protected $latest = false;

	public function __construct($_1) {
		$this->stockId = absint($_1['id'] ?? 0);
		$this->refresh = (bool) ($_1['refresh'] ?? false);
		$this->latest = (bool) ($_1['latest'] ?? false);
	}

	public function sync() {
		if(!$this->stockId) {
			return;
		}

		$priceItems = $this->getPriceItems();
		if(!$priceItems) {
			return;
		}

		if($this->refresh) {
			StockPriceVelocityAccess::deleteItems($this->stockId);
		}

		$latestRocDate = StockPriceVelocityAccess::getLatestDate($this->stockId);
		$earliestRocDate = null;

		if(!$this->latest) {
			$earliestRocDate = StockPriceVelocityAccess::getEarliestDate($this->stockId);
		}

		foreach($priceItems as $index => $day) {
			$day = (array) $day;

			if(
				$latestRocDate && $earliestRocDate &&
				$earliestRocDate <= $day['date'] &&
				$day['date'] <= $latestRocDate
			) {
				continue;
			}

			if($this->latest && $latestRocDate && $day['date'] <= $latestRocDate) {
				break;
			}

			$date = $day['date'];
			$open = (float) $day['open'];
			$high = (float) $day['high'];
			$low = (float) $day['low'];
			$close = (float) $day['close'];
			$adjustedClose = (float) $day['adjustedClose'];
			$volume = (float) $day['volume'];

			$previousDay = $priceItems[$index + 1] ?? false;

			if(!$previousDay) {
				continue;
			}

			$previousDay = (array) $previousDay;

			$previousOpen = (float) $previousDay['open'];
			$previousHigh = (float) $previousDay['high'];
			$previousLow = (float) $previousDay['low'];
			$previousClose = (float) $previousDay['close'];
			$previousAdjustedClose = (float) $previousDay['adjustedClose'];
			$previousVolume = (int) $previousDay['volume'];

			$intradayRoc = $open === 0.0 ?
				1 :
				($close - $open) / $open;

			$openRoc = $previousOpen === 0.0 ?
				1 :
				($open - $previousOpen) / $previousOpen;

			$highRoc = $previousHigh === 0.0 ?
				1 :
				($high - $previousHigh) / $previousHigh;

			$lowRoc = $previousLow === 0.0 ?
				1 :
				($low - $previousLow) / $previousLow;

			$closeRoc = $previousClose === 0.0 ?
				1 :
				($close - $previousClose) / $previousClose;

			$adjustedCloseRoc = $adjustedClose === 0.0 ?
				1 :
				($adjustedClose - $previousAdjustedClose) / $previousAdjustedClose;

			$volumeRoc = $previousVolume === 0 ?
				1 :
				($volume - $previousVolume) / $previousVolume;

			$rawSet = new NumberSet([
				'numbers' => [$intradayRoc, $openRoc, $highRoc, $lowRoc, $closeRoc]
			]);

			$medianRoc = $rawSet->getMedian();
			$meanRoc = $rawSet->getMean();

			if($adjustedCloseRoc === $closeRoc) {
				$adjustedMedianRoc = $medianRoc;
				$adjustedMeanRoc = $meanRoc;
			} else {
				$adjustedSet = new NumberSet([
					'numbers' => [$intradayRoc, $openRoc, $highRoc, $lowRoc, $adjustedCloseRoc]
				]);
				$adjustedMedianRoc = $adjustedSet->getMedian();
				$adjustedMeanRoc = $adjustedSet->getMean();
			}

			StockPriceVelocityAccess::insertItem([
				'stockId' => $this->stockId,
				'date' => $date,
				'intraday' => $intradayRoc,
				'open' => $openRoc,
				'high' => $highRoc,
				'low' => $lowRoc,
				'close' => $closeRoc,
				'adjustedClose' => $adjustedCloseRoc,
				'median' => $medianRoc,
				'adjustedMedian' => $adjustedMedianRoc,
				'mean' => $meanRoc,
				'adjustedMean' => $adjustedMeanRoc,
				'volume' => $volumeRoc,
			]);
		}
	}

	private function getPriceItems() {
		return StockPriceAccess::getItems($this->stockId);
	}

	public static function syncAll($_1 = []) {
		$stockIds = StockAccess::getAllIds();
		$startId = (int) ($_1['startId'] ?? 0);
		$latest = (bool) ($_1['latest'] ?? false);

		foreach($stockIds as $stockId) {
			if($startId && $stockId < $startId) {
				continue;
			}

			echo PHP_EOL . "Syncing stock velocity: {$stockId}" . PHP_EOL;

			$syncService = new static([
				'id' => $stockId,
				'latest' => $latest,
			]);
			$syncService->sync();
		}
	}
}

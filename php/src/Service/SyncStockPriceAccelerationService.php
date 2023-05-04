<?php

namespace Stocks\Service;

use Stocks\NumberSet;
use StocksWp\DataAccess\StockAccess;
use StocksWp\DataAccess\StockPriceVelocityAccess;
use StocksWp\DataAccess\StockPriceAccelerationAccess;

class SyncStockPriceAccelerationService {
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

		$velocityItems = $this->getVelocityItems();
		if(!$velocityItems) {
			return;
		}

		if($this->refresh) {
			StockPriceAccelerationAccess::deleteItems($this->stockId);
		}

		$latestAccelerationDate = StockPriceAccelerationAccess::getLatestDate($this->stockId);
		$earliestAccelerationDate = null;

		if(!$this->latest) {
			$earliestAccelerationDate = StockPriceAccelerationAccess::getEarliestDate($this->stockId);
		}

		foreach($velocityItems as $index => $day) {
			$day = (array) $day;

			if(
				$earliestAccelerationDate && $latestAccelerationDate &&
				$earliestAccelerationDate <= $day['date'] &&
				$day['date'] <= $latestAccelerationDate
			) {
				continue;
			}

			if($this->latest && $latestAccelerationDate && $day['date'] <= $latestAccelerationDate) {
				break;
			}

			$date = $day['date'];
			$intraday = (float) $day['intraday'];
			$open = (float) $day['open'];
			$high = (float) $day['high'];
			$low = (float) $day['low'];
			$close = (float) $day['close'];
			$adjustedClose = (float) $day['adjustedClose'];
			$volume = (float) $day['volume'];

			$previousDay = $velocityItems[$index + 1] ?? false;

			if(!$previousDay) {
				continue;
			}

			$previousDay = (array) $previousDay;

			$previousIntraday = (float) $previousDay['intraday'];
			$previousOpen = (float) $previousDay['open'];
			$previousHigh = (float) $previousDay['high'];
			$previousLow = (float) $previousDay['low'];
			$previousClose = (float) $previousDay['close'];
			$previousAdjustedClose = (float) $previousDay['adjustedClose'];
			$previousVolume = (float) $previousDay['volume'];

			$intradayAcc = $previousIntraday === 0.0 ?
				$intraday :
				($intraday - $previousIntraday) / abs($previousIntraday);

			$openAcc = $previousOpen === 0.0 ?
				$open :
				($open - $previousOpen) / abs($previousOpen);

			$highAcc = $previousHigh === 0.0 ?
				$high :
				($high - $previousHigh) / abs($previousHigh);

			$lowAcc = $previousLow === 0.0 ?
				$low :
				($low - $previousLow) / abs($previousLow);

			$closeAcc = $previousClose === 0.0 ?
				$close :
				($close - $previousClose) / abs($previousClose);

			$adjustedCloseAcc = $previousAdjustedClose === 0.0 ?
				$adjustedClose :
				($adjustedClose - $previousAdjustedClose) / abs($previousAdjustedClose);

			$volumeAcc = $previousVolume === 0.0 ?
				$volume :
				($volume - $previousVolume) / abs($previousVolume);

			$rawSet = new NumberSet([
				'numbers' => [$intradayAcc, $openAcc, $highAcc, $lowAcc, $closeAcc],
			]);

			$medianAcc = $rawSet->getMedian();
			$meanAcc = $rawSet->getMean();

			if($closeAcc === $adjustedCloseAcc) {
				$adjustedMedianAcc = $medianAcc;
				$adjustedMeanAcc = $meanAcc;
			} else {
				$adjustedSet = new NumberSet(['numbers' => [$intradayAcc, $openAcc, $highAcc, $lowAcc, $adjustedCloseAcc]]);
				$adjustedMedianAcc = $adjustedSet->getMedian();
				$adjustedMeanAcc = $adjustedSet->getMean();
			}

			StockPriceAccelerationAccess::insertItem([
				'stockId' => $this->stockId,
				'date' => $date,
				'intraday' => $intradayAcc,
				'open' => $openAcc,
				'high' => $highAcc,
				'low' => $lowAcc,
				'close' => $closeAcc,
				'adjustedClose' => $adjustedCloseAcc,
				'median' => $medianAcc,
				'adjustedMedian' => $adjustedMedianAcc,
				'mean' => $meanAcc,
				'adjustedMean' => $adjustedMeanAcc,
				'volume' => $volumeAcc,
			]);
		}
	}

	private function getVelocityItems() {
		return StockPriceVelocityAccess::getAll($this->stockId);
	}

	public static function syncAll($_1 = []) {
		$stockIds = StockAccess::getAllIds();
		$startId = (int) ($_1['startId'] ?? 0);
		$latest = (bool) ($_1['latest'] ?? false);

		foreach($stockIds as $stockId) {
			if($startId && $stockId < $startId) {
				continue;
			}

			echo PHP_EOL . "Syncing stock acceleration: {$stockId}" . PHP_EOL;

			$syncService = new static([
				'id' => $stockId,
				'latest' => $latest,
			]);
			$syncService->sync();
		}
	}
}
